<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Basket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmation;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PaymentController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Create Stripe checkout session
     */
    public function createCheckoutSession(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string|max:255',
            'shipping_city' => 'required|string|max:100',
            'shipping_postcode' => 'required|string|max:20',
            'contact_phone' => 'required|string|max:20',
        ]);

        $basketItems = Basket::with('product')
            ->where('user_id', auth()->id())
            ->get();

        if ($basketItems->isEmpty()) {
            return redirect()->route('basket.index')
                ->with('error', 'Your basket is empty');
        }

        // Check stock availability
        foreach ($basketItems as $item) {
            if ($item->product->stock_quantity < $item->quantity) {
                return back()->with('error',
                    "Not enough stock for {$item->product->name}");
            }
        }

        // Calculate total
        $total = $basketItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });

        // Create line items for Stripe
        $lineItems = [];
        foreach ($basketItems as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'gbp',
                    'product_data' => [
                        'name' => $item->product->name,
                        'description' => substr($item->product->description, 0, 100),
                    ],
                    'unit_amount' => (int)($item->product->price * 100), // Convert to pence
                ],
                'quantity' => $item->quantity,
            ];
        }

        try {
            // Store shipping info in session
            session([
                'shipping_info' => [
                    'address' => $request->shipping_address,
                    'city' => $request->shipping_city,
                    'postcode' => $request->shipping_postcode,
                    'phone' => $request->contact_phone,
                ]
            ]);

            // Create Stripe Checkout Session
            $checkoutSession = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('payment.cancel'),
                'customer_email' => auth()->user()->email,
            ]);

            return redirect($checkoutSession->url);

        } catch (\Exception $e) {
            return back()->with('error', 'Payment session creation failed. Please try again.');
        }
    }

    /**
     * Payment success callback
     */
    public function success(Request $request)
    {
        $sessionId = $request->query('session_id');

        if (!$sessionId) {
            return redirect()->route('home')
                ->with('error', 'Invalid payment session');
        }

        try {
            // Retrieve the session from Stripe
            $session = Session::retrieve($sessionId);

            if ($session->payment_status !== 'paid') {
                return redirect()->route('basket.index')
                    ->with('error', 'Payment was not completed');
            }

            // Get shipping info from session
            $shippingInfo = session('shipping_info');

            if (!$shippingInfo) {
                return redirect()->route('home')
                    ->with('error', 'Shipping information missing');
            }

            DB::beginTransaction();

            try {
                $basketItems = Basket::with('product')
                    ->where('user_id', auth()->id())
                    ->get();

                if ($basketItems->isEmpty()) {
                    DB::rollBack();
                    return redirect()->route('home')
                        ->with('error', 'Basket is empty');
                }

                // Calculate total
                $total = $basketItems->sum(function($item) {
                    return $item->product->price * $item->quantity;
                });

                // Create order
                $order = Order::create([
                    'user_id' => auth()->id(),
                    'order_number' => 'ORD-' . strtoupper(uniqid()),
                    'total_amount' => $total,
                    'status' => 'processing',
                    'payment_status' => 'paid',
                    'payment_method' => 'stripe',
                    'stripe_session_id' => $sessionId,
                    'shipping_address' => $shippingInfo['address'],
                    'shipping_city' => $shippingInfo['city'],
                    'shipping_postcode' => $shippingInfo['postcode'],
                    'contact_phone' => $shippingInfo['phone'],
                ]);

                // Create order items
                foreach ($basketItems as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'unit_price' => $item->product->price,
                        'subtotal' => $item->product->price * $item->quantity,
                    ]);

                    // Decrease stock
                    $item->product->decrementStock($item->quantity);
                }

                // Clear basket
                Basket::where('user_id', auth()->id())->delete();

                // Clear shipping info from session
                session()->forget('shipping_info');

                // Send order confirmation email
                $order->load(['user', 'items.product']);
                Mail::to($order->user->email)->send(new OrderConfirmation($order));

                DB::commit();

                return view('payment.success', compact('order'));

            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->route('home')
                    ->with('error', 'Order creation failed. Please contact support.');
            }

        } catch (\Exception $e) {
            return redirect()->route('home')
                ->with('error', 'Payment verification failed');
        }
    }

    /**
     * Payment cancelled
     */
    public function cancel()
    {
        return view('payment.cancel');
    }
}
