<ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
    <li class="nav-item">
        <a class="d-flex align-items-center" href="{{ route('dashboard') }}">
            <i data-feather="home"></i>
            <span class="menu-title text-truncate" data-i18n="Disabled Menu">Dashboard</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="d-flex align-items-center" href="{{ route('dashboard.users') }}">
            <i data-feather="users"></i>
            <span class="menu-title text-truncate" data-i18n="Disabled Menu">Usuarios</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="d-flex align-items-center" href="{{ route('dashboard.departures') }} ">
            <i data-feather="briefcase"></i>
            <span class="menu-title text-truncate" data-i18n="Disabled Menu">Partidas</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="d-flex align-items-center" href="{{ route('dashboard.comments') }} ">
            <i class="fa fa-bug"></i>
            <span class="menu-title text-truncate" data-i18n="Disabled Menu">Comentarios Rep.</span>
        </a>
    </li>
    {{-- <li class="nav-item">
        <a class="d-flex align-items-center" href="javascript:void(0)">
            <i data-feather="radio"></i>
            <span class="menu-title text-truncate" data-i18n="Disabled Menu">Notificaciones</span>
        </a>
    </li> --}}

    <li class="nav-item"><a class="d-flex align-items-center" href="#"><i data-feather="settings"></i><span
                class="menu-title text-truncate" data-i18n="Settings">Configuración</span></a>
        <ul class="menu-content">
            <li>
                <a class="d-flex align-items-center" href="{{ route('dashboard.settings.terms_conditions') }}">
                    <i data-feather="circle"></i>
                    <span class="menu-title text-truncate" data-i18n="Disabled Menu">Terminos y Condiciones</span>
                </a>
            </li>
            <li>
                <a class="d-flex align-items-center" href="{{ route('dashboard.dimensions') }}">
                    <i data-feather="circle"></i>
                    <span class="menu-title text-truncate" data-i18n="Disabled Menu">Dimensións</span>
                </a>
            </li>
            <li>
                <a class="d-flex align-items-center" href="{{ route('dashboard.settings.privacy_policies') }}">
                    <i data-feather="circle"></i>
                    <span class="menu-title text-truncate" data-i18n="Disabled Menu">Aviso de Privacidad</span>
                </a>
            </li>
            <li>
                <a class="d-flex align-items-center" href="{{ route('dashboard.settings.about') }}">
                    <i data-feather="circle"></i>
                    <span class="menu-title text-truncate" data-i18n="Disabled Menu">Acerca de</span>
                </a>
            </li>
            <li>
                <a class="d-flex align-items-center" href="{{ route('dashboard.settings.contact') }}">
                    <i data-feather="circle"></i>
                    <span class="menu-title text-truncate" data-i18n="Disabled Menu">Contacto</span>
                </a>
            </li>
            <li>
                <a class="d-flex align-items-center" href="{{ route('dashboard.settings.prices') }}">
                    <i data-feather="circle"></i>
                    <span class="menu-title text-truncate" data-i18n="Disabled Menu">Precios</span>
                </a>
            </li>
            <li>
                <a class="d-flex align-items-center" href="{{ route('dashboard.settings.categories') }}">
                    <i data-feather="circle"></i>
                    <span class="menu-title text-truncate" data-i18n="Disabled Menu">Categorías</span>
                </a>
            </li>
            <li>
                <a class="d-flex align-items-center" href="{{ route('dashboard.settings.payment_methods') }}">
                    <i data-feather="circle"></i>
                    <span class="menu-title text-truncate" data-i18n="Disabled Menu">Métodos de Pago</span>
                </a>
            </li>
            <li>
                <a class="d-flex align-items-center" href="{{ route('dashboard.settings.payment_gateway') }}">
                    <i data-feather="circle"></i>
                    <span class="menu-title text-truncate" data-i18n="Disabled Menu">Pasarela de Pago</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="d-flex align-items-center" href="{{ route('dashboard.settings.paises') }}">
                    <i data-feather="circle"></i>
                    <span class="menu-title text-truncate" data-i18n="Disabled Menu">Paises</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="d-flex align-items-center" href="{{ route('dashboard.settings.autonomous_community') }}">
                    <i data-feather="circle"></i>
                    <span class="menu-title text-truncate" data-i18n="Disabled Menu">Comunidades Autonómas</span>
                </a>
            </li>
            <li>
                <a class="d-flex align-items-center" href="{{ route('dashboard.settings.province') }}">
                    <i data-feather="circle"></i>
                    <span class="menu-title text-truncate" data-i18n="Disabled Menu">Provincias</span>
                </a>
            </li>
            <li>
                <a class="d-flex align-items-center" href="{{ route('dashboard.settings.plantilla') }}">
                    <i data-feather="circle"></i>
                    <span class="menu-title text-truncate" data-i18n="">Plantilla</span>
                </a>
            </li>

        </ul>
    </li>
</ul>
