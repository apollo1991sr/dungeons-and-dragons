<!doctype html>
<html lang="uk">

<head>
    <meta charset="UTF-8">

    <title>
        @yield('title', 'Dungeons & Dragons')
    </title>

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
