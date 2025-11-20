<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>UTS PPWL</title>

    {{-- Sneat CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="layout-menu-fixed">
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">

            {{-- SIDEBAR --}}
            @include('components.layout.sidebar')

            {{-- PAGE CONTENT --}}
            <div class="layout-page">

                {{-- NAVBAR --}}
                @include('components.layout.navbar')

                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        @yield('content')
                    </div>

                    {{-- FOOTER --}}
                    @include('components.layout.footer')
                </div>

            </div>

        </div>
    </div>

    {{-- Sneat JS --}}
    <script src="{{ asset('assets/vendor/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

</body>

</html>
