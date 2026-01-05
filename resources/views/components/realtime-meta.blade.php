{{-- 
    Real-Time Meta Tags & Scripts
    Tambahkan di <head> section layout Anda
--}}

@auth
    {{-- User ID untuk real-time notifications --}}
    <meta name="user-id" content="{{ auth()->id() }}">
    
    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endauth

{{-- Vite Assets --}}
@vite(['resources/css/app.css', 'resources/js/app.js'])

{{-- Optional: Custom CSS for animations --}}
<style>
    @keyframes slide-in {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-slide-in {
        animation: slide-in 0.3s ease-out;
    }
    
    .animate-fade-in {
        animation: fade-in 0.3s ease-out;
    }
</style>
