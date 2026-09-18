<!doctype html>
<html lang="uk">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        @yield('title', 'Dungeons & Dragons')
    </title>

    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">

    <link
        rel="icon"
        type="image/png"
        sizes="32x32"
        href="{{ asset('favicon-32x32.png') }}"
    >

    <link
        rel="icon"
        type="image/png"
        sizes="16x16"
        href="{{ asset('favicon-16x16.png') }}"
    >

    <link
        rel="apple-touch-icon"
        sizes="180x180"
        href="{{ asset('apple-touch-icon.png') }}"
    >

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Alegreya:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet"
    >

    @stack('styles')
</head>

<body>

@yield('content')

@stack('scripts')

</body>

</html>
