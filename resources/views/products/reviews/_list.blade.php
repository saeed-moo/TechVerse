{{-- Reviews List Component --}}
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-xl font-bold text-gray-900 dark:text-white">
            Customer Reviews ({{ $product->review_count }})
        </h3>

        {{-- Average Rating --}}
        @if($product->review_count > 0)
            <div class="flex items-center gap-2">
                <div class="flex items-center">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-5 h-5 {{ $i <= round($product->average_rating) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                    @endfor
                </div>
                <span class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ number_format($product->average_rating, 1) }}/5
                </span>
            </div>
        @endif
    </div>

    {{-- Reviews List --}}
    @if($product->reviews->count() > 0)
        <div class="space-y-4">
            @foreach($product->reviews()->latest()->get() as $review)
                <div class="border-b border-gray-200 dark:border-gray-700 pb-4 last:border-b-0">
                    {{-- Review Header --}}
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                {{-- Stars --}}
                                <div class="flex items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @endfor
                                </div>

                                {{-- Verified Purchase Badge --}}
                                @if($review->is_verified_purchase)
                                    <span class="text-xs bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 px-2 py-1 rounded">
                                        ✓ Verified Purchase
                                    </span>
                                @endif
                            </div>

                            <div class="flex items-center gap-2">
                                <span class="font-semibold text-gray-900 dark:text-white">
                                    {{ $review->user->name }}
                                </span>
                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $review->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>

                        {{-- Edit/Delete Buttons (only for own reviews) --}}
                        @auth
                            @if($review->user_id === auth()->id())
                                <div class="flex gap-2">
                                    <button
                                        onclick="showEditForm()"
                                        class="text-sm text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-500 font-semibold"
                                    >
                                        Edit
                                    </button>
                                    <form action="{{ route('reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Delete this review?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-500 font-semibold">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @endauth
                    </div>

                    {{-- Review Comment --}}
                    @if($review->comment)
                        <p class="text-gray-700 dark:text-gray-300">
                            {{ $review->comment }}
                        </p>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-8">
            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
            </svg>
            <p class="text-gray-600 dark:text-gray-400 text-lg mb-2">No reviews yet</p>
            <p class="text-gray-500 dark:text-gray-500 text-sm">Be the first to review this product!</p>
        </div>
    @endif
</div>

<script>
function showEditForm() {
    const form = document.getElementById('review-form-container');
    if (form) {
        form.classList.remove('hidden');
        form.scrollIntoView({ behavior: 'smooth' });
    }
}
</script>
