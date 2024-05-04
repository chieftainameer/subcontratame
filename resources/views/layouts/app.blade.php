<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="loaded">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description"
        content="Vuexy admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords"
        content="admin template, Vuexy admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="PIXINVENT">
    <link rel="apple-touch-icon" href="{{ asset('dashboard/app-assets/images/ico/apple-icon-120.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('dashboard/app-assets/images/ico/favicon.ico') }}">
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600"
        rel="stylesheet">
    {{-- Fontawesome --}}
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link href="{{ asset('css/all.css') }}" rel="stylesheet">
    {{-- <link rel="stylesheet" href="{{ asset('dashboard/vendor/bootstrap-daterangepicker/daterangepicker.css') }}"> --}}
    @if (isset($cssStyles))
        @foreach ($cssStyles as $css_style)
            <link href="{{ asset($css_style) }}" rel="stylesheet">
        @endforeach
    @endif
</head>

<body class="vertical-layout vertical-menu-modern  navbar-floating footer-static" data-open="click"
    data-menu="vertical-menu-modern" data-col="">
    @csrf
    <input type="hidden" id="domainHost" value="{{ config('app.url') }}">
    @auth
        <input type="hidden" name="google_maps_key" id="google_maps_key"
            value="{{ cache('settings')->googlemaps_key ?? '' }}">
        <input type="hidden" name="google_maps_libraries" id="google_maps_libraries"
            value="{{ cache('settings')->googlemaps_libraries ?? '' }}">
        @include('layouts.app-header')
        @include('layouts.app-menu')
    @endauth
    <div class="app-content content ecommerce-application">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-body">
                @yield('content')
            </div>
        </div>
    </div>
    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-light">
        <p class="clearfix mb-0">
            <span class="float-md-left d-block d-md-inline-block mt-25">{{ config('app.name') }}
                &copy; 2022
                <a class="ml-25" href="https://4megatech.com" target="_blank">4Megatech Corporation S.A.C</a>
                <span class="d-none d-sm-inline-block">, All rights Reserved</span>
            </span>
            <span class="float-md-right d-none d-md-block">Hand-crafted & Made with<i data-feather="heart"></i></span>
        </p>
    </footer>
    <button class="btn btn-primary btn-icon scroll-top" type="button"><i data-feather="arrow-up"></i></button>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <!-- <script src="https://js.stripe.com/v3/"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.2/moment.min.js"></script>
    <!-- <script
        src="https://www.paypal.com/sdk/js?client-id=AYMuIYMFO3TncZwhj0fFDEXg_qT3jGyWaRvHNkMPdbl5fbRE8PRZVIEiHGlUaGiWnbLZxul-uDEnZPZz&currency=USD">
    </script> -->
    @auth
        <script src="https://cdn.jsdelivr.net/gh/jitbit/HtmlSanitizer@master/HtmlSanitizer.js"></script>
        <script src="https://cdn.tiny.cloud/1/{{ $tinymce_key ?? '' }}/tinymce/5/tinymce.min.js" referrerpolicy="origin">
        </script>
    @endauth
    <script src="{{ asset('js/all.js') }}"></script>
    {{-- <script src="{{ asset('dashboard/vendor/bootstrap-daterangepicker/daterangepicker.js') }}"></script> --}}
    <script src="{{ asset('js/app.js') }}"></script>

    <script src="https://cdn.rawgit.com/hilios/jQuery.countdown/2.0.4/dist/jquery.countdown.min.js"></script>
    @if (isset($jsControllers))
        @foreach ($jsControllers as $jsController)
            @if (is_array($jsController))
                <script type="{{ $jsController['type'] }}" src='{!! asset($jsController['file']) . '?v=' . mt_rand(100000, 999999) !!}'></script>
            @else
                <script src='{!! asset($jsController) . '?v=' . mt_rand(100000, 999999) !!}'></script>
            @endif
        @endforeach
    @endif
    <script>
        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
            @if (Session::has('success'))
                $(document).ready(function() {
                    Core.showToast('success', '{{ Session::get('success') }}');
                });
            @endif
        });
    </script>
    @yield('payment_js')
    @yield('multimodal_js')
{{--    <link rel="stylesheet" href="{{ asset('css/custome.css') }}">--}}
</body>

</html>
