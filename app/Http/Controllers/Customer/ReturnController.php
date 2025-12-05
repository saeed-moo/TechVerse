<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductReturn;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReturnController extends Controller
{
    /**
     * Display user's returns
     */
    public function index()
    {
        $returns = ProductReturn::where('user_id', Auth::id())
            ->with(['order', 'orderItem.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('customer.returns.index', compact('returns'));
    }

    /**
     * Show return creation form
     */
    public function create(Order $order)
    {
        // Check if order belongs to user
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if order is eligible for return (within 30 days)
        $daysSinceDelivery = Carbon::parse($order->delivered_at ?? $order->created_at)->diffInDays(Carbon::now());

        if ($daysSinceDelivery > 30) {
            return back()->withErrors(['error' => 'This order is no longer eligible for returns. Returns must be requested within 30 days.']);
        }

        // Get order items that haven't been returned
        $availableItems = OrderItem::where('order_id', $order->id)
            ->whereDoesntHave('returns', function($query) {
                $query->whereIn('status', ['pending', 'approved']);
            })
            ->with('product')
            ->get();

        if ($availableItems->isEmpty()) {
            return back()->withErrors(['error' => 'All items from this order have already been returned or have pending return requests.']);
        }

        return view('customer.returns.create', compact('order', 'availableItems'));
    }

    /**
     * Store return request
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'order_item_id' => 'required|exists:order_items,id',
            'reason' => 'required|in:defective,wrong_item,not_as_described,changed_mind,damaged,other',
            'description' => 'required|string|max:1000',
            'images.*' => 'nullable|image|max:2048'
        ]);

        // Verify ownership
        $order = Order::findOrFail($request->order_id);
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if item already has pending/approved return
        $existingReturn = ProductReturn::where('order_id', $request->order_id)
            ->where('order_item_id', $request->order_item_id)
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existingReturn) {
            return back()->withErrors(['error' => 'A return request for this item is already pending.']);
        }

        // Create return request
        $return = ProductReturn::create([
            'user_id' => Auth::id(),
            'order_id' => $request->order_id,
            'order_item_id' => $request->order_item_id,
            'reason' => $request->reason,
            'description' => $request->description,
            'status' => 'pending',
            'requested_at' => now()
        ]);

        // Handle image uploads
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('returns', 'public');
                $images[] = $path;
            }
            $return->update(['images' => json_encode($images)]);
        }

        return redirect()->route('returns.index')
            ->with('success', 'Return request submitted successfully! We will review your request within 24-48 hours.');
    }

    /**
     * Show return details
     */
    public function show(ProductReturn $return)
    {
        // Check ownership
        if ($return->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $return->load(['order', 'orderItem.product']);

        return view('customer.returns.show', compact('return'));
    }
}
