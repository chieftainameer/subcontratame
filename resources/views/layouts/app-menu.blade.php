<!-- BEGIN: Main Menu-->
@php
    $settings = cache('settings');
@endphp
<div class="main-menu menu-fixed menu-accordion menu-shadow menu-light" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto">
                <a class="navbar-brand">
                    <img src="{{ asset('images/new-logo.png') }}" alt="Subcontratame" style="width: 100%; height: 100%;">

                    {{-- <h2 class="brand-text">{{ config('app.name') }}</h2> --}}
                </a>
            </li>
            <li class="nav-item nav-toggle">
                <a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse" id="data-toggle-collapse">
                    <i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i>
                    <i class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc"
                        data-ticon="disc"></i>
                </a>
            </li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        @auth
            @if (auth()->user()->role == '1')
                @include('layouts.app-menu-admin')
            @endif
        @endauth
    </div>
</div>
<!-- END: Main Menu-->
