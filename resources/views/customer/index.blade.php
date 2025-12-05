@extends('layouts.app')

@section('title', 'My Reviews')

@section('content')
<div class="container reviews-page">
    <div class="page-header">
        <h1>My Product Reviews</h1>
        <p>Share your experience with products you've purchased</p>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-error">
        {{ $errors->first() }}
    </div>
    @endif

    <div class="reviews-list">
        @forelse($reviews as $review)
        <div class="review-card">
            <div class="review-product">
                <img src="{{ $review->product->image_url ?? '/images/placeholder.jpg' }}" alt="{{ $review->product->name }}">
                <div class="product-details">
                    <h3>{{ $review->product->name }}</h3>
                    <p class="product-price">${{ number_format($review->product->price, 2) }}</p>
                </div>
            </div>

            <div class="review-content">
                <div class="review-header">
                    <div class="stars">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="star {{ $i <= $review->rating ? 'filled' : '' }}">★</span>
                        @endfor
                    </div>
                    <span class="review-date">{{ $review->created_at->format('M d, Y') }}</span>
                </div>

                <h4>{{ $review->title }}</h4>
                <p class="review-text">{{ $review->comment }}</p>

                @if($review->verified_purchase)
                <span class="verified-badge">✓ Verified Purchase</span>
                @endif

                <span class="review-status status-{{ $review->status }}">
                    {{ ucfirst($review->status) }}
                </span>
            </div>

            <div class="review-actions">
                <button class="btn-edit" onclick="editReview({{ $review->id }})">Edit</button>
                <form action="{{ route('reviews.destroy', $review) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-delete" onclick="return confirm('Delete this review?')">Delete</button>
                </form>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
            </svg>
            <h3>No Reviews Yet</h3>
            <p>You haven't written any reviews. Purchase products to leave reviews!</p>
            <a href="{{ route('products.index') }}" class="btn-primary">Browse Products</a>
        </div>
        @endforelse
    </div>

    @if($reviews->hasPages())
    <div class="pagination">
        {{ $reviews->links() }}
    </div>
    @endif
</div>

<style>
.reviews-page {
    max-width: 900px;
    margin: 0 auto;
    padding: 24px;
}

.page-header {
    margin-bottom: 32px;
}

.page-header h1 {
    font-size: 32px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 8px;
}

.page-header p {
    color: #6b7280;
    font-size: 16px;
}

.alert {
    padding: 16px 20px;
    border-radius: 8px;
    margin-bottom: 24px;
}

.alert-success {
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #10b981;
}

.alert-error {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #ef4444;
}

.reviews-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.review-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 24px;
    display: grid;
    grid-template-columns: 200px 1fr auto;
    gap: 20px;
    align-items: start;
}

.review-product {
    display: flex;
    gap: 12px;
    align-items: center;
}

.review-product img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
}

.product-details h3 {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
    margin: 0 0 4px 0;
}

.product-price {
    font-size: 14px;
    color: #6b7280;
    margin: 0;
}

.review-content {
    flex: 1;
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.stars {
    display: flex;
    gap: 4px;
}

.star {
    font-size: 20px;
    color: #d1d5db;
}

.star.filled {
    color: #fbbf24;
}

.review-date {
    font-size: 13px;
    color: #9ca3af;
}

.review-content h4 {
    font-size: 18px;
    font-weight: 600;
    color: #111827;
    margin: 0 0 8px 0;
}

.review-text {
    color: #4b5563;
    font-size: 14px;
    line-height: 1.6;
    margin-bottom: 12px;
}

.verified-badge {
    display: inline-block;
    padding: 4px 12px;
    background: #d1fae5;
    color: #065f46;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
    margin-right: 8px;
}

.review-status {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
}

.status-approved {
    background: #d1fae5;
    color: #065f46;
}

.status-pending {
    background: #fef3c7;
    color: #92400e;
}

.review-actions {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.btn-edit, .btn-delete {
    padding: 8px 16px;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-edit {
    background: #f3f4f6;
    color: #374151;
}

.btn-edit:hover {
    background: #e5e7eb;
}

.btn-delete {
    background: #fee2e2;
    color: #991b1b;
}

.btn-delete:hover {
    background: #fecaca;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 12px;
    border: 2px dashed #e5e7eb;
}

.empty-state svg {
    width: 64px;
    height: 64px;
    color: #9ca3af;
    margin: 0 auto 20px;
}

.empty-state h3 {
    font-size: 20px;
    font-weight: 600;
    color: #111827;
    margin: 0 0 8px 0;
}

.empty-state p {
    color: #6b7280;
    margin: 0 0 24px 0;
}

.btn-primary {
    display: inline-block;
    padding: 12px 24px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 500;
}

@media (max-width: 768px) {
    .review-card {
        grid-template-columns: 1fr;
    }

    .review-actions {
        flex-direction: row;
    }
}
</style>
@endsection
