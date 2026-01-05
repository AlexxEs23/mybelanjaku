{{-- 
    Rating Star Component
    Usage: <x-rating-stars :rating="4.5" />
--}}

@props(['rating' => 0, 'size' => 'md'])

@php
    $fullStars = floor($rating);
    $hasHalfStar = ($rating - $fullStars) >= 0.5;
    $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
    
    $sizeClasses = [
        'sm' => 'w-4 h-4',
        'md' => 'w-5 h-5',
        'lg' => 'w-6 h-6',
        'xl' => 'w-8 h-8',
    ];
    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
@endphp

<div class="flex items-center gap-1">
    {{-- Full Stars --}}
    @for ($i = 0; $i < $fullStars; $i++)
        <svg class="{{ $sizeClass }} text-yellow-400 fill-current" viewBox="0 0 20 20">
            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
        </svg>
    @endfor

    {{-- Half Star --}}
    @if ($hasHalfStar)
        <svg class="{{ $sizeClass }} text-yellow-400" viewBox="0 0 20 20">
            <defs>
                <linearGradient id="half-{{ $rating }}">
                    <stop offset="50%" stop-color="currentColor"/>
                    <stop offset="50%" stop-color="#D1D5DB"/>
                </linearGradient>
            </defs>
            <path fill="url(#half-{{ $rating }})" d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
        </svg>
    @endif

    {{-- Empty Stars --}}
    @for ($i = 0; $i < $emptyStars; $i++)
        <svg class="{{ $sizeClass }} text-gray-300 fill-current" viewBox="0 0 20 20">
            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
        </svg>
    @endfor
</div>
