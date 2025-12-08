<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('stock_status')) {
            $query->where('stock_status', $request->stock_status);
        }

        $products = $query->paginate(20);
        $categories = Category::all();

        return view('admin.inventory.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.inventory.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only([
            'category_id',
            'name',
            'description',
            'price',
            'stock_quantity',
            'low_stock_threshold'
        ]);

        $data['slug'] = Str::slug($request->name);

        // Handle image
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        } else {
            $data['image'] = 'products/default.jpg';
        }

        // Determine stock status
        if ($data['stock_quantity'] == 0) {
            $data['stock_status'] = 'out_of_stock';
        } elseif ($data['stock_quantity'] <= $data['low_stock_threshold']) {
            $data['stock_status'] = 'low_stock';
        } else {
            $data['stock_status'] = 'in_stock';
        }

        Product::create($data);

        return redirect()->route('admin.inventory.index')
            ->with('success', 'Product created successfully!');
    }

    public function edit(Product $inventory)
    {
        $categories = Category::all();

        return view('admin.inventory.edit', [
            'product' => $inventory,
            'categories' => $categories
        ]);
    }

    public function update(Request $request, Product $inventory)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only([
            'category_id',
            'name',
            'description',
            'price',
            'stock_quantity',
            'low_stock_threshold'
        ]);

        $data['slug'] = Str::slug($request->name);

        // Handle image
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        // Determine stock status
        if ($data['stock_quantity'] == 0) {
            $data['stock_status'] = 'out_of_stock';
        } elseif ($data['stock_quantity'] <= $data['low_stock_threshold']) {
            $data['stock_status'] = 'low_stock';
        } else {
            $data['stock_status'] = 'in_stock';
        }

        $inventory->update($data);

        return redirect()->route('admin.inventory.index')
            ->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $inventory)
    {
        $inventory->delete();

        return redirect()->route('admin.inventory.index')
            ->with('success', 'Product deleted successfully!');
    }
}
