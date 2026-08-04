<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>PAIO Archives Management System</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>

<body>

    @include('partials.sidebar')

    <div class="main-content">

        @include('partials.navbar')

        <main class="content-area">

            @yield('content')

        </main>

    </div>
     @stack('scripts')
</body>

</html>