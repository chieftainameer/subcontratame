<header class="site-header mo-left header fullwidth">
    <!-- Main Header -->
    <div class="sticky-header main-bar-wraper navbar-expand-lg">
        <div class="main-bar clearfix">
            <div class="container clearfix">
                <!-- Website Logo -->
                <div class="logo-header mostion">
                    <a href="{{ route('home') }}" class="logo"><img src="{{ asset('images/new-logo.png') }}"
                            alt=""></a>
                    {{-- <a href="index.html" class="logo-white"><img src="{{ asset('frontend/images/logo-white.png') }}"
                            alt=""></a> --}}
                </div>
                <!-- Nav Toggle Button -->
                <button class="navbar-toggler collapsed navicon justify-content-end" type="button"
                    data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                <!-- Extra Nav -->
                <div class="extra-nav">
                    <div class="extra-cell">
                        {{-- <a href="javascript:void(0);" class="layout-btn">
                            <input type="checkbox">
                            <span class="mode-label"></span>
                        </a> --}}
                        @if (auth()->user())
                            <a href="{{ route('client.notifications') }}" type="button"
                                class="btn btn-primary btn-sm mr-3"><i class="fa fa-bell-o"></i>&nbsp;<span
                                    class="badge badge-light">{{ count(auth()->user()->unreadNotifications) }}</span>
                            </a>
                            <a href="{{ route('client.logout') }}" title="Cerrar sesión" rel="bookmark"
                                class="btn btn-danger btn-sm"><i class="fa fa-sign-out" aria-hidden="true"></i> Cerrar
                                sesión
                            </a>
                        @else
                            <a href="{{ route('client.register') }} " class="btn btn-primary btn-sm"
                                title="Crea una cuenta"><i class="fa fa-user"></i>&nbsp;Registrarse</a>
                            <a href="{{ route('client.login') }}" title="Inicia sesión con tu cuenta creada"
                                rel="bookmark" class="btn btn-primary btn-sm"><i class="fa fa-lock"></i> Iniciar
                                sesión</a>
                        @endif

                    </div>
                </div>
                <!-- Main Nav -->
                <div class="header-nav navbar-collapse collapse justify-content-start" id="navbarNavDropdown">
                    <div class="logo-header">
                        <a href="index.html" class="logo"><img src="{{ asset('frontend/images/new-logo.png') }}"
                                alt=""></a>
                        <a href="index.html" class="logo-white"><img src="{{ asset('frontend/images/new-logo-white.png') }}"
                                alt=""></a>
                    </div>
                    <ul class="nav navbar-nav">
                        <li class="active">
                            <a href="{{ route('home') }} ">Inicio</a>
                            {{-- <ul class="sub-menu">
                                <li><a href="index.html" class="dez-page">Home 1</a></li>
                                <li><a href="index-2.html" class="dez-page">Home 2</a></li>
                            </ul> --}}
                        </li>
                        @auth
                            <li>
                                <a href="javascrit:void(0)">Proyectos</a>
                                <ul class="sub-menu">
                                    <li><a href="{{ route('client.projects.my-projects') }}">Mis proyectos</a></li>
                                    <li><a href="{{ route('client.projects.favorites') }}">Guardados</a></li>
                                    <li><a href="{{ route('client.projects.my-offers') }}">Mis ofertas</a></li>
                                    {{-- <li><a href="{{ route('client.logout') }}">Cerrar sesión</a></li> --}}
                                </ul>
                            </li>
                            <li>
                                <a href="{{ route('client.profile') }}">Mi perfil</a>
                            </li>
                        @endauth

                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Main Header END -->
</header>
