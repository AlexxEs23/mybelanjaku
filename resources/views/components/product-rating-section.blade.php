{{-- 
    Rating Section untuk Detail Produk
    Include di halaman detail produk
--}}

@props(['produk'])

@php
    $user = auth()->user();
    $averageRating = $produk->averageRating();
    $totalRatings = $produk->totalRatings();
    $distribution = $produk->ratingDistribution();
    
    // Cek eligibility user untuk memberi rating
    $canRate = $user && 
               $user->role === 'user' && 
               $user->hasPurchasedProduct($produk->id) && 
               !$user->hasRatedProduct($produk->id);
    
    $existingRating = $user ? $user->getRatingForProduct($produk->id) : null;
@endphp

<div class="bg-white rounded-lg shadow p-6">
    {{-- Header: Rating Summary --}}
    <div class="border-b pb-6 mb-6">
        <h3 class="text-2xl font-bold mb-4">Rating & Review</h3>
        
        <div class="grid md:grid-cols-2 gap-6">
            {{-- Average Rating --}}
            <div class="flex items-center gap-4">
                <div class="text-center">
                    <div class="text-5xl font-bold text-gray-900">{{ $averageRating }}</div>
                    <div class="flex items-center justify-center mt-2">
                        <x-rating-stars :rating="$averageRating" size="lg" />
                    </div>
                    <div class="text-sm text-gray-600 mt-1">
                        {{ $totalRatings }} {{ $totalRatings === 1 ? 'rating' : 'ratings' }}
                    </div>
                </div>
            </div>

            {{-- Rating Distribution --}}
            <div class="space-y-2">
                @for ($star = 5; $star >= 1; $star--)
                    @php
                        $count = $distribution[$star];
                        $percentage = $totalRatings > 0 ? ($count / $totalRatings * 100) : 0;
                    @endphp
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-600 w-12">{{ $star }} ⭐</span>
                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                            <div class="bg-yellow-400 h-2 rounded-full transition-all" style="width: {{ $percentage }}%"></div>
                        </div>
                        <span class="text-sm text-gray-600 w-12 text-right">{{ $count }}</span>
                    </div>
                @endfor
            </div>
        </div>
    </div>

    {{-- Form: Beri/Edit Rating (jika eligible) --}}
    @if($user)
        @if($canRate)
            {{-- Form untuk memberi rating baru --}}
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <h4 class="font-semibold mb-3">Berikan Rating Anda</h4>
                
                <form action="{{ route('ratings.store', $produk) }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-medium mb-2">Rating</label>
                        <x-rating-input name="rating" :value="old('rating', 5)" />
                        @error('rating')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="review" class="block text-sm font-medium mb-2">
                            Review (Opsional)
                        </label>
                        <textarea 
                            name="review" 
                            id="review" 
                            rows="4"
                            class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="Ceritakan pengalaman Anda dengan produk ini..."
                        >{{ old('review') }}</textarea>
                        @error('review')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <button 
                        type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold transition-colors"
                    >
                        Kirim Rating
                    </button>
                </form>
            </div>
        @elseif($existingRating)
            {{-- Form untuk edit rating yang sudah ada --}}
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <div class="flex justify-between items-start mb-3">
                    <h4 class="font-semibold">Rating Anda</h4>
                    <button 
                        onclick="toggleEditRating()"
                        class="text-blue-600 hover:text-blue-700 text-sm"
                    >
                        Edit
                    </button>
                </div>
                
                {{-- View Mode --}}
                <div id="view-rating">
                    <div class="flex items-center gap-2 mb-2">
                        <x-rating-stars :rating="$existingRating->rating" />
                        <span class="text-sm text-gray-600">{{ $existingRating->created_at->format('d M Y') }}</span>
                    </div>
                    @if($existingRating->review)
                        <p class="text-gray-700">{{ $existingRating->review }}</p>
                    @endif
                </div>
                
                {{-- Edit Mode (Hidden by default) --}}
                <form 
                    id="edit-rating-form" 
                    action="{{ route('ratings.update', $existingRating) }}" 
                    method="POST" 
                    class="space-y-4 hidden"
                >
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label class="block text-sm font-medium mb-2">Rating</label>
                        <x-rating-input name="rating" :value="$existingRating->rating" />
                    </div>
                    
                    <div>
                        <label for="edit-review" class="block text-sm font-medium mb-2">Review</label>
                        <textarea 
                            name="review" 
                            id="edit-review" 
                            rows="4"
                            class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                        >{{ $existingRating->review }}</textarea>
                    </div>
                    
                    <div class="flex gap-2">
                        <button 
                            type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg"
                        >
                            Simpan Perubahan
                        </button>
                        <button 
                            type="button"
                            onclick="toggleEditRating()"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg"
                        >
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        @elseif($user->role === 'user' && !$user->hasPurchasedProduct($produk->id))
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <p class="text-yellow-800">
                    <strong>Info:</strong> Anda harus membeli produk ini terlebih dahulu untuk dapat memberikan rating.
                </p>
            </div>
        @endif
    @else
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
            <p class="text-gray-700">
                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-semibold">
                    Login
                </a> 
                untuk memberikan rating dan review.
            </p>
        </div>
    @endif

    {{-- List: Semua Rating/Review --}}
    <div class="space-y-4">
        <h4 class="font-semibold text-lg">Semua Review ({{ $totalRatings }})</h4>
        
        @forelse($produk->ratings()->with('user')->latest()->get() as $rating)
            <div class="border-b pb-4">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold">
                            {{ substr($rating->user->name, 0, 1) }}
                        </div>
                    </div>
                    
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-1">
                            <h5 class="font-semibold">{{ $rating->user->name }}</h5>
                            <span class="text-sm text-gray-500">{{ $rating->created_at->diffForHumans() }}</span>
                        </div>
                        
                        <div class="flex items-center gap-2 mb-2">
                            <x-rating-stars :rating="$rating->rating" size="sm" />
                            <span class="text-sm text-gray-600">({{ $rating->rating }}/5)</span>
                        </div>
                        
                        @if($rating->review)
                            <p class="text-gray-700 text-sm">{{ $rating->review }}</p>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-8 text-gray-500">
                <svg class="w-16 h-16 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                </svg>
                <p>Belum ada review untuk produk ini.</p>
                <p class="text-sm mt-1">Jadilah yang pertama memberikan review!</p>
            </div>
        @endforelse
    </div>
</div>

<script>
    function toggleEditRating() {
        const viewMode = document.getElementById('view-rating');
        const editMode = document.getElementById('edit-rating-form');
        
        viewMode.classList.toggle('hidden');
        editMode.classList.toggle('hidden');
    }
</script>
