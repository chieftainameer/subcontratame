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
                            <a href="{{ route('home') }}" class="mb-4"><img src="{{ asset('images/new-logo.png') }}"
                                    alt="Logo" /></a>
                        </div>
                        <div class="clearfix"></div>
                        <div class="tab-content nav p-b30 tab">
                            <form id="frmLogin" class="tab-pane active col-12 p-a0 ">
                                <p class="font-weight-600">Si tiene una cuenta con nosotros, inicie sesión.</p>
                                <div class="form-group">
                                    <label>Correo Electrónico *</label>
                                    <div class="input-group">
                                        <input name="email" id="email" required class="form-control"
                                            placeholder="Tu correo electrónico" type="email">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Contraseña *</label>
                                    <div class="input-group">
                                        <input name="password" id="password" required minlength="8" class="form-control "
                                            placeholder="Escribir contraseña" type="password">
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button class="site-button btn-block mb-4">Entrar</button>
                                </div>
                                <div class="clearfix"></div>
                                <div class="text-left mt-10">
                                    <p class="font-weight-600">Si no tienes una cuenta con nosotros, registrate <a
                                            href="{{ route('client.register') }}" style="color: blue">aquí</a>.</p>
                                </div>
                                <div class="text-right">
                                    <a data-toggle="tab" href="#frmForgotPassword"
                                        class="site-button-link forget-pass m-t15 float-right"><i
                                            class="fa fa-unlock-alt"></i> ¿Has olvidado tu contraseña?</a><br>
                                </div>
                            </form>
                            <form id="frmForgotPassword" class="tab-pane fade col-12 p-a0">
                                <p>Le enviaremos un correo electrónico para restablecer su contraseña. </p>
                                <div class="form-group">
                                    <label>Correo Electrónico *</label>
                                    <div class="input-group">
                                        <input name="email" required class="form-control"
                                            placeholder="Tu correo electrónico" type="email">
                                    </div>
                                </div>
                                <div class="text-left">
                                    <a class="site-button outline gray" data-toggle="tab" href="#frmLogin">Atrás</a>
                                    <button class="site-button pull-right">Enviar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- Full Blog Page Contant -->
    </div>
    <!-- Content END-->
@endsection
