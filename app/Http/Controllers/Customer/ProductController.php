<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Services\ProductSearchService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $searchService;

    public function __construct(ProductSearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    /**
     * Display a listing of products with advanced filters
     */
    public function index(Request $request)
    {
        $query = $request->input('search');

        $filters = [
            'min_price' => $request->input('min_price'),
            'max_price' => $request->input('max_price'),
            'category' => $request->input('category'),
            'in_stock' => $request->input('in_stock'),
            'brand' => $request->input('brand'),
            'rating' => $request->input('rating'),
            'sort' => $request->input('sort', 'newest'),
        ];

        // Remove null/empty values
        $filters = array_filter($filters, fn($value) => $value !== null && $value !== '');

        // Get products using search service
        $products = $this->searchService->search($query, $filters);

        // Get all categories for filter
        $categories = Category::all();

        // Get price range for slider
        $priceRange = $this->searchService->getPriceRange();

        // Get available brands
        $brands = $this->searchService->getAvailableBrands();

        return view('products.index', compact(
            'products',
            'categories',
            'priceRange',
            'brands',
            'query',
            'filters'
        ));
    }

/**
 * Display the specified product
 */
public function show(Product $product)
{
    $product->load(['category', 'reviews.user']);

    // Get related products (same category, in stock)
    $relatedProducts = Product::where('category_id', $product->category_id)
        ->where('id', '!=', $product->id)
        ->where('stock_quantity', '>', 0)
        ->limit(4)
        ->get();

    // Check if current user has reviewed this product
    $userReview = null;
    if (auth()->check()) {
        $userReview = $product->reviews()
            ->where('user_id', auth()->id())
            ->first();
    }

    return view('products.show', compact('product', 'relatedProducts', 'userReview'));
}
}
