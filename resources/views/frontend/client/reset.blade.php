@extends('layouts.frontend.app-user')
@section('content')
    @if ($user)
        <!-- Content -->
        <div class="browse-job login-style3">
            <!-- Coming Soon -->
            <div class="bg-img-fix"
                style="background-image:url('{{ asset('frontend/images/main-slider/bg6.jpg') }}'); height: 100vh;">
                <div class="row">
                    <div
                        class="col-xl-4 col-lg-5 col-md-6 col-sm-12 bg-white z-index2 relative p-a0 content-scroll skew-section left-bottom">
                        <div class="login-form style-2">
                            <div class=" text-center p-tb30">
                                <a href="{{ route('home') }}" class="mb-6"><img src="{{ asset('images/logo.png') }}"
                                        alt="Logo" /></a>
                            </div>
                            <div class="clearfix"></div>
                            <div class="tab-content nav p-b30 tab">
                                <div id="login" class="tab-pane active ">
                                    <form id="frmResetPassword" class="tab-pane active">
                                        <input type="hidden" id="id" name="id" value="{{ $user->id }}">
                                        <h4 class="font-weight-700 m-b5 text-center">REESTABLECER CONTRASEÑA</h4>
                                        <div class="form-group mt-3">
                                            <label>Contraseña *</label>
                                            <div class="input-group">
                                                <input name="password" id="password_confirmation" id="password" required
                                                    class="form-control" placeholder="Escribe la nueva contraseña"
                                                    type="password">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Confirmar contraseña *</label>
                                            <div class="input-group">
                                                <input name="password_confirmation" id="password_confirmation" required
                                                    class="form-control" placeholder="Vuelve a ingresar la nueva contraseña"
                                                    type="password">
                                            </div>
                                        </div>
                                        <div class="text-left">
                                            <button class="site-button button-lg outline outline-2 btn-block"
                                                type="submit">Cambiar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Full Blog Page Contant -->
        </div>
        <!-- Content END-->
    @else
        <!-- Content -->
        <div class="browse-job login-style3">
            <!-- Coming Soon -->
            <div class="bg-img-fix"
                style="background-image:url('{{ asset('frontend/images/main-slider/bg6.jpg') }}'); height: 100vh;">
                <div class="row">
                    <div
                        class="col-xl-4 col-lg-5 col-md-6 col-sm-12 bg-white z-index2 relative p-a0 content-scroll skew-section left-bottom">
                        <div class="login-form style-2">
                            <div class="logo-header text-center p-tb30">
                                <a href="{{ route('home') }}">
                                    <h1 class="text-blue">INDEED</h1>
                                </a>
                            </div>
                            <div class="clearfix"></div>
                            <div class="tab-content nav p-b30 tab">
                                <div id="login" class="tab-pane active ">
                                    <div class="col-md-12 m-b30">
                                        {{-- <div class="card max-w500 radius-sm m-auto"> --}}
                                        <div class="tab-content">
                                            <h4 class="font-weight-700 m-b5 text-center">TOKEN INVÁLIDO</h4>
                                            <p class="font-weight-600 mt-3">El token para reestablecer su contraseña es
                                                inválido, por
                                                favor
                                                vuelva a solicitar un nuevo token <a href="{{ route('client.login') }}"
                                                    style="color: blue">aquí</a>.</p>
                                        </div>
                                        {{-- </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Full Blog Page Contant -->
        </div>
        <!-- Content END-->
    @endif
    {{-- </div> --}}
@endsection
