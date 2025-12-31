<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AmbuGaz - @yield('title')</title>
    <link rel="shortcut icon" href="{{ asset('cropped-favico-192x192.png') }}" type="image/x-icon">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Heroicons  -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@heroicons/vue@2.0.18/24/outline/index.min.css">
    
    <style>
        /* body {
            background-color: rgb(250, 250, 250);
            font-family: 'Inter', sans-serif;
        } */
        /* config Tailwind */
        @tailwind base;
        @tailwind components;
        @tailwind utilities;
    </style>
    
    @yield('styles')
</head>
<body class="bg-gray-50 font-inter antialiased">
    @yield('content')
    @yield('script')
</body>
</html>
