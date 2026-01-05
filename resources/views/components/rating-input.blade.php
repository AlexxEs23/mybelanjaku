{{-- 
    Rating Input Component (Interactive Stars for Form)
    Usage: <x-rating-input name="rating" :value="old('rating', 5)" />
--}}

@props(['name' => 'rating', 'value' => 0, 'required' => true])

<div class="flex flex-col gap-2">
    <div class="flex items-center gap-2">
        @for ($i = 1; $i <= 5; $i++)
            <label class="cursor-pointer group">
                <input 
                    type="radio" 
                    name="{{ $name }}" 
                    value="{{ $i }}" 
                    class="sr-only peer"
                    {{ $i == $value ? 'checked' : '' }}
                    {{ $required ? 'required' : '' }}
                >
                <svg class="w-8 h-8 text-gray-300 peer-checked:text-yellow-400 group-hover:text-yellow-300 transition-colors fill-current" viewBox="0 0 20 20">
                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                </svg>
            </label>
        @endfor
    </div>
    
    <div class="text-sm text-gray-600">
        <span id="rating-text">
            @if($value > 0)
                {{ $value }} dari 5 bintang
            @else
                Pilih rating Anda
            @endif
        </span>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const radios = document.querySelectorAll('input[name="{{ $name }}"]');
        const text = document.getElementById('rating-text');
        
        radios.forEach(radio => {
            radio.addEventListener('change', function() {
                const labels = ['Sangat Buruk', 'Buruk', 'Cukup', 'Baik', 'Sangat Baik'];
                text.textContent = `${this.value} dari 5 bintang - ${labels[this.value - 1]}`;
            });
        });
    });
</script>
