<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    {{-- SEO Meta Tags --}}
    <title>@yield('title', 'CheckoutAja - Login atau Daftar')</title>
    <meta name="description" content="@yield('meta_description', 'Masuk atau daftar di CheckoutAja untuk mulai berbelanja produk lokal Indonesia atau menjual produk UMKM Anda.')">
    <meta name="robots" content="noindex, nofollow">
    
    {{-- Canonical URL --}}
    <link rel="canonical" href="{{ url()->current() }}">
    
    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
    @stack('styles')
    
    <style>
        html {
            scroll-behavior: smooth;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }
    </style>
</head>
    
<body class="bg-gradient-to-br from-purple-600 to-purple-800 min-h-screen flex items-center justify-center p-5">
    {{-- Main Content --}}
    <main role="main" class="w-full">
        @yield('content')
    </main>
    
    @stack('scripts')
</body>
</html>
