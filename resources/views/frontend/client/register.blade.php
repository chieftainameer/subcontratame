@extends('layouts.frontend.app-user')
@section('content')
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
                            <a href="{{ route('home') }}" class="mb-6"><img src="{{ asset('images/new-logo.png') }}"
                                    alt="Logo" /></a>
                        </div>
                        <div class="clearfix"></div>
                        <div class="tab-content nav p-b30 tab">
                            <div id="login" class="tab-pane active ">
                                <form class=" dez-form p-b30" id='frmRegister'>
                                    {{-- <h3 class="form-title m-t0">Información Básica</h3> --}}
                                    <h5 class="font-weight-700 m-b5 text-center">INFORMACIÓN PERSONAL</h5>
                                    <p class="font-weight-600 mb-3">Si tiene una cuenta con nosotros, inicie sesión <a
                                            href="{{ route('client.login') }}" style="color: blue">aquí</a>.</p>
                                    <div class="dez-separator-outer m-b5">
                                        <div class="dez-separator bg-primary style-liner"></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="font-weight-700">Nombres *</label>
                                        <input name="first_name" id="first_name" required class="form-control"
                                            type="text">
                                    </div>
                                    <div class="form-group">
                                        <label class="font-weight-700">Apellidos *</label>
                                        <input name="last_name" id="last_name" required class="form-control" type="text">
                                    </div>
                                    <div class="form-group">
                                        <label class="font-weight-700">Correo Electrónico *</label>
                                        <input name="email" id="email" required class="form-control" type="email">
                                    </div>
                                    <div class="form-group">
                                        <label class="font-weight-700">Contraseña *</label>
                                        <input name="password" id="password" required class="form-control "
                                            type="password">
                                    </div>
                                    <div class="form-group">
                                        <label class="font-weight-700">Confirmar Contraseña *</label>
                                        <input name="password_confirmation" id="password_confirmation" class="form-control "
                                            type="password">
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="i_agree"
                                                id="i_agree" required>
                                            <label for="i_agree" class="custom-control-label">Acepto los <a
                                                    href="javascript:void(0)" id="btnTerm"
                                                    style="text-decoration: none;">Términos y
                                                    Condiciones</a>
                                                y la <a href="javascript:void(0)" id="btnPrivacy"
                                                    style="text-decoration: none;">Política de
                                                    Privacidad</a></label>
                                        </div>
                                    </div>
                                    <div class="text-left">
                                        <button class="site-button button-lg outline outline-2 btn-block"
                                            type="submit">Crear&nbsp;una&nbspcuenta</button>
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

    <div class="modal fade browse-job modal-bx-info editor" id="mdlShowPrivacy" tabindex="-1" role="dialog"
        aria-labelledby="ProfilenameModalLongTitle2" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ProfilenameModalLongTitle2">Políticas de Privacidad</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @include('frontend.client.partials.data-privacy')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade browse-job modal-bx-info editor" id="mdlShowTerm" tabindex="-1" role="dialog"
        aria-labelledby="ProfilenameModalLongTitle2" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ProfilenameModalLongTitle2">Términos y Condiciones</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @include('frontend.client.partials.data-term')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal End -->
@endsection
