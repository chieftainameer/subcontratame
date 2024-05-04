<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="" />
    <meta name="author" content="" />
    <meta name="robots" content="" />
    {{-- <meta name="description" content="Job Board - Job Portal HTML Template + RTL and Dark layout" />
    <meta property="og:title" content="Job Board - Job Portal HTML Template + RTL and Dark layout" />
    <meta property="og:description" content="Job Board - Job Portal HTML Template + RTL and Dark layout" /> --}}
    {{-- <meta property="og:image" content="https://job-board.dexignzone.com/xhtml/social-image.png" />
    <meta name="format-detection" content="telephone=no"> --}}

    <!-- FAVICONS ICON -->
    <link rel="icon" href="{{ asset('images/logo_ico.ico') }}" type="image/x-icon" />
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/new-logo.png') }}" />

    <!-- PAGE TITLE HERE -->
    <title>{{ config('app.name') }} </title>

    <!-- MOBILE SPECIFIC -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!--[if lt IE 9]>
 <script src="js/html5shiv.min.js"></script>
 <script src="js/respond.min.js"></script>
 <![endif]-->

    <!-- STYLESHEETS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/plugins.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/templete.css') }}">
    <link class="skin" rel="stylesheet" type="text/css" href="{{ asset('frontend/css/skin/skin-1.css') }}">
    <link class="skin" rel="stylesheet" type="text/css" href="{{ asset('frontend/css/dark-layout.css') }}">
    {{-- <link href="{{ url('') . mix('css/all.css') }}" rel="stylesheet"> --}}
    <link rel="stylesheet" href="{{ asset('dashboard/vendor/toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/app-assets/css/plugins/richtext/richtext.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/vendor/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet"
        href="{{ asset('dashboard/app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('dashboard/app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('dashboard/app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('dashboard/app-assets/vendors/css/tables/datatable/rowGroup.bootstrap4.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('dashboard/app-assets/css/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/plugins/starrr/starrr.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('app/style.css') }}"> --}}

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Lato:wght@100;300;400;700;900&family=Montserrat:wght@100;200;300;400;500;600;700;800;900&family=Roboto:wght@100;300;400;500;700;900&family=Rubik:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    @if (isset($cssStyles))
        @foreach ($cssStyles as $css_style)
            <link href="{{ asset($css_style) }}" rel="stylesheet">
        @endforeach
    @endif
</head>

<body id="bg">
    <div id="loading-area"></div>
    <input type="hidden" id="urlRoute" name="urlRoute" value="{{ config('app.url') }}">
    <input type="hidden" id="domainHost" value="{{ config('app.url') }}">
    <div class="page-wraper">

        <!-- header -->
        @include('layouts.frontend.app-header')
        <!-- header END -->
        @if (session()->has('success'))
            <script>
                toastr.success("{{ session('success') }}")
            </script>
        @elseif (session()->has('info'))
            <script>
                toastr.info("{{ session('info') }}")
            </script>
        @elseif (session()->has('error'))
            <script>
                toastr.error("{{ session('error') }}")
            </script>
        @endif
        <!-- Content -->
        @yield('content')
        <!-- Content END-->
        <!-- Modal Box -->
        <div class="modal fade lead-form-modal" id="car-details" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="modal-body row m-a0 clearfix">
                        <div class="col-lg-6 col-md-6 d-flex p-a0"
                            style="background-image: url(frontend/images/background/bg3.jpg);  background-position:center; background-size:cover;">

                        </div>
                        <div class="col-lg-6 col-md-6 p-a0">
                            <div class="lead-form browse-job text-left">
                                <form>
                                    <h3 class="m-t0">Personal Details</h3>
                                    <div class="form-group">
                                        <input value="" class="form-control" placeholder="Name" />
                                    </div>
                                    <div class="form-group">
                                        <input value="" class="form-control" placeholder="Mobile Number" />
                                    </div>
                                    <div class="clearfix">
                                        <button type="button" class="btn-primary site-button btn-block">Submit
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Box End -->
        <!-- Footer -->
        @include('layouts.frontend.app-footer')
        <!-- Footer END -->
        <!-- scroll top button -->
        <button class="scroltop fa fa-arrow-up"></button>
    </div>
    <!-- JAVASCRIPT FILES ========================================= -->
    <script src="{{ asset('frontend/js/jquery.min.js') }}"></script><!-- JQUERY.MIN JS -->
    <script src="{{ asset('frontend/plugins/bootstrap/js/popper.min.js') }}"></script><!-- BOOTSTRAP.MIN JS -->
    <script src="{{ asset('frontend/plugins/bootstrap/js/bootstrap.min.js') }}"></script><!-- BOOTSTRAP.MIN JS -->
    <script src="{{ asset('frontend/plugins/bootstrap-select/bootstrap-select.min.js') }}"></script><!-- FORM JS -->
    <script src="{{ asset('frontend/plugins/bootstrap-touchspin/jquery.bootstrap-touchspin.js') }}"></script><!-- FORM JS -->
    <script src="{{ asset('frontend/plugins/magnific-popup/magnific-popup.js') }}"></script><!-- MAGNIFIC POPUP JS -->
    <script src="{{ asset('frontend/plugins/counter/waypoints-min.js') }}"></script><!-- WAYPOINTS JS -->
    <script src="{{ asset('frontend/plugins/counter/counterup.min.js') }}"></script><!-- COUNTERUP JS -->
    <script src="{{ asset('frontend/plugins/imagesloaded/imagesloaded.js') }}"></script><!-- IMAGESLOADED -->
    <script src="{{ asset('frontend/plugins/masonry/masonry-3.1.4.js') }}"></script><!-- MASONRY -->
    <script src="{{ asset('frontend/plugins/masonry/masonry.filter.js') }}"></script><!-- MASONRY -->
    <script src="{{ asset('frontend/plugins/owl-carousel/owl.carousel.js') }}"></script><!-- OWL SLIDER -->
    <script src="{{ asset('frontend/plugins/scroll/scrollbar.min.js') }}"></script>
    <script src="{{ asset('frontend/js/custom.js') }}"></script><!-- CUSTOM FUCTIONS  -->
    <script src="{{ asset('frontend/js/dz.carousel.js') }}"></script><!-- SORTCODE FUCTIONS  -->
    <script src="{{ asset('dashboard/vendor/axios/axios.min.js') }}"></script>
    <script src="{{ asset('dashboard/vendor/axios/progress.bar.min.js') }}"></script>
    <script src="{{ asset('dashboard/vendor/jquery.validate/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('dashboard/vendor/jquery.validate/messages_es.min.js') }}"></script>
    <script src="{{ asset('dashboard/vendor/moment/moment.min.js') }}"></script>
    <script src="{{ asset('dashboard/vendor/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('dashboard/vendor/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('dashboard/vendor/jquery.blockUI/jquery.blockUI.min.js') }}"></script>
    <script src="{{ asset('dashboard/vendor/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('dashboard/app-assets/js/scripts/richtext/richtext.min.js') }}"></script>
    <script src="{{ asset('dashboard/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('dashboard/app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('dashboard/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('dashboard/app-assets/vendors/js/tables/datatable/responsive.bootstrap4.js') }}"></script>
    <script src="{{ asset('dashboard/vendor/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('dashboard/vendor/ckeditor/adapters/jquery.js') }}"></script>

    <script src="{{ asset('dashboard/app-assets/js/scripts/bootstrap-tagsinput/bootstrap-tagsinput.min.js') }}"></script>
    <script src="{{ asset('frontend/plugins/starrr/starrr.js') }}"></script>
    <script src="{{ asset('dashboard/vendor/bootbox/bootbox.min.js') }}"></script>

    <script src="{{ asset('app/Config.js') }}"></script>
    <script src="{{ asset('app/Core.js') }}"></script>
    <script src="{{ asset('app/MultiModal.js') }}"></script>
    <script src="{{ asset('app/removePage.js') }}"></script>
    <script src="{{ asset('app/ratings.js') }}"></script>
    @if (isset($jsControllers))
        @foreach ($jsControllers as $jsController)
            {{-- @if (is_array($jsController))
                <script type="{{ $jsController['type'] }}" src='{!! asset($jsController['file']) . '?v=' . mt_rand(100000, 999999) !!}'></script>
            @else --}}
            <script src='{!! asset($jsController) . '?v=' . mt_rand(100000, 999999) !!}'></script>
            {{-- @endif --}}
        @endforeach
    @endif
</body>

</html>
