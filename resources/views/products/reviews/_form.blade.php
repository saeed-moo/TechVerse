{{-- Review Form Component --}}
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
        {{ $userReview ? 'Edit Your Review' : 'Write a Review' }}
    </h3>

    <form action="{{ $userReview ? route('reviews.update', $userReview) : route('reviews.store', $product) }}" method="POST">
        @csrf
        @if($userReview)
            @method('PUT')
        @endif

        {{-- Star Rating --}}
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                Rating <span class="text-red-500">*</span>
            </label>
            <div class="flex items-center gap-2">
                @for($i = 1; $i <= 5; $i++)
                    <label class="cursor-pointer">
                        <input
                            type="radio"
                            name="rating"
                            value="{{ $i }}"
                            class="hidden peer"
                            {{ ($userReview && $userReview->rating == $i) || old('rating') == $i ? 'checked' : '' }}
                            required
                        >
                        <svg class="w-8 h-8 text-gray-300 peer-checked:text-yellow-400 hover:text-yellow-400 transition" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                    </label>
                @endfor
            </div>
            @error('rating')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Review Comment --}}
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                Your Review
            </label>
            <textarea
                name="comment"
                rows="4"
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white transition-colors duration-200"
                placeholder="Share your experience with this product..."
            >{{ $userReview ? $userReview->comment : old('comment') }}</textarea>
            @error('comment')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Submit Button --}}
        <div class="flex gap-3">
            <button
                type="submit"
                class="bg-purple-600 dark:bg-purple-500 text-white px-6 py-2 rounded-lg hover:bg-purple-700 dark:hover:bg-purple-600 transition-colors duration-200 font-semibold"
            >
                {{ $userReview ? 'Update Review' : 'Submit Review' }}
            </button>

            @if($userReview)
                <button
                    type="button"
                    onclick="document.getElementById('review-form-container').classList.add('hidden')"
                    class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white px-6 py-2 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors duration-200 font-semibold"
                >
                    Cancel
                </button>
            @endif
        </div>
    </form>
</div>
