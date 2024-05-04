<ul>
    <li><a href="{{ route('client.profile') }}">
            <i class="fa fa-user-o" aria-hidden="true"></i>
            <span>Mi Perfil</span></a></li>
    <li><a href="{{ route('client.projects.favorites') }}">
            <i class="fa fa-file-text-o" aria-hidden="true"></i>
            <span>Proyectos Guardados</span></a></li>
    <li><a href="{{ route('client.projects.my-offers') }}">
            <i class="fa fa-heart-o" aria-hidden="true"></i>
            <span>Mis Ofertas</span></a></li>
    <li><a href="{{ route('client.projects.my-projects') }}">
            <i class="fa fa-briefcase" aria-hidden="true"></i>
            <span>Mis Proyectos</span></a></li>
    <li><a href="{{ route('client.notifications') }} ">
            <i class="fa fa-bell-o" aria-hidden="true"></i>
            <span>Mis Notificaciones
                @auth
                    <span class="badge badge-primary">{{ count(auth()->user()->unreadNotifications) }}</span>
                @endauth
        </a>
    </li>
    {{-- <li><a href="#">
            <i class="fa fa-id-card-o" aria-hidden="true"></i>
            <span>CV Manager</span></a></li>
    <li><a href="#">
            <i class="fa fa-key" aria-hidden="true"></i>
            <span>Change Password</span></a></li> --}}
    <li><a href="{{ route('client.logout') }} ">
            <i class="fa fa-sign-out" aria-hidden="true"></i>
            <span>Cerrar sesión</span></a></li>
</ul>
