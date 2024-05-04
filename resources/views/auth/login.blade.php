@extends('auth.app')
@section('content')
    <div class="d-flex col-lg-4 align-items-center auth-bg px-2 p-lg-5">
        <div class="col-12 col-sm-8 col-md-6 col-lg-12 px-xl-2 mx-auto">
            <h2 class="card-title font-weight-bold mb-1">Bienvenido</h2>
            <p class="card-text mb-2">Por favor ingrese sus datos de acceso</p>
            <form class="auth-login-form mt-2" id="frmLogin">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="email">{{ __('Email') }}</label>
                    <input class="form-control" id="email" type="text" name="email" placeholder="john@example.com"
                        aria-describedby="email" autofocus="" tabindex="1" />
                </div>
                <div class="form-group">
                    <div class="input-group input-group-merge form-password-toggle">
                        <input class="form-control form-control-merge" id="password" type="password" name="password"
                            placeholder="············" aria-describedby="password" tabindex="2" />
                        <div class="input-group-append"><span class="input-group-text cursor-pointer"><i
                                    data-feather="eye"></i></span></div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block" tabindex="4">Ingresar</button>
            </form>
        </div>
    </div>
@endsection
