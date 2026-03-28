<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
 /**
 * Store a new review
 */
public function store(Request $request, Product $product)
{
    $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string|max:1000',
    ]);

    // Check if user already reviewed this product
    $existingReview = Review::where('user_id', auth()->id())
        ->where('product_id', $product->id)
        ->first();

    if ($existingReview) {
        return redirect()->back()->with('error', 'You have already reviewed this product!');
    }

    // For now, set all reviews as verified (can update later with order checking)
    Review::create([
        'user_id' => auth()->id(),
        'product_id' => $product->id,
        'rating' => $request->rating,
        'comment' => $request->comment,
        'is_verified_purchase' => true, // Set to true for everyone for now
    ]);

    // Update product average rating
    $this->updateProductRating($product);

    return redirect()->back()->with('success', 'Thank you for your review!');
}

    /**
     * Update a review
     */
    public function update(Request $request, Review $review)
    {
        // Check if user owns this review
        if ($review->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Unauthorized action!');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // Update product average rating
        $this->updateProductRating($review->product);

        return redirect()->back()->with('success', 'Review updated successfully!');
    }

    /**
     * Delete a review
     */
    public function destroy(Review $review)
    {
        // Check if user owns this review
        if ($review->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Unauthorized action!');
        }

        $product = $review->product;
        $review->delete();

        // Update product average rating
        $this->updateProductRating($product);

        return redirect()->back()->with('success', 'Review deleted successfully!');
    }

    /**
     * Update product's average rating and review count
     */
    private function updateProductRating(Product $product)
    {
        $averageRating = $product->reviews()->avg('rating');
        $reviewCount = $product->reviews()->count();

        $product->update([
            'average_rating' => $averageRating ? round($averageRating, 2) : 0,
            'review_count' => $reviewCount,
        ]);
    }
}
