<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>@yield('title','Toko Hijau Seller')</title>


{{-- Sneat CSS --}}
<link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}">


@vite(['resources/css/app.css', 'resources/js/app.js'])
@stack('head')
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


{{-- JS includes: load jQuery first, then libraries, then SweetAlert, then stack scripts --}}


{{-- jQuery (use local if present otherwise CDN) --}}
@if (file_exists(public_path('assets/vendor/libs/jquery/jquery.js')))
<script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
@else
<script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
@endif


{{-- Bootstrap & libs --}}
<script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>


{{-- menu / main (Sneat) --}}
<script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>


{{-- SweetAlert2 CDN (1x) --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


{{-- place for views to push scripts --}}
@stack('scripts')
</body>
</html>