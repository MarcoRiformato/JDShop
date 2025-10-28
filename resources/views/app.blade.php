<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title inertia>{{ config('app.name', 'JDOutlet') }}</title>

        <!-- SEO Meta Tags -->
        <meta name="description" content="JDOutlet - Vendita materiali per eventi: audio professionale, arredi vintage, scenografie, specchi e molto altro. Qualità garantita per i tuoi eventi.">
        <meta name="keywords" content="eventi, audio professionale, arredi vintage, scenografie, specchi, materiali eventi, noleggio attrezzature">
        <meta name="author" content="JDOutlet">
        <meta name="robots" content="index, follow">

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:site_name" content="JDOutlet">
        <meta property="og:locale" content="it_IT">
        
        <!-- Twitter Card -->
        <meta name="twitter:card" content="summary_large_image">
        
        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="/favicon.ico">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', 'resources/css/app.css'])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>

