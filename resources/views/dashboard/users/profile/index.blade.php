@extends('layouts.app')
@section('content')
    <form id="frmEdit">
        @csrf
        <input type="hidden" id="id" name="id" value="{{ auth()->user()->id }}">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h2 class="m-0 font-weight-bold text-primary">Actualizar datos de perfil</h2>
            </div>
            <div class="card-body">

                <div class="row">
                    <div class="col-sm-12 col-md-4 col-4">
                        <!-- Choose image ro profile -->
                        <img class="img-rounded profile"
                            src="{{ auth()->user()->image !== null ? asset('storage') . '/' . auth()->user()->image : (auth()->user()->image === 'https://picsum.photos/200/300' ? 'https://picsum.photos/200/300' : asset('images/anonymous.png')) }}"
                            id="imgProfile">
                        <div class="form-group">
                            <label for="image">Choose image</label>
                            <input type="file" name="image" id="image" class="form-control"
                                onchange="imagePreview(this)">
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-8 col-8">
                        <div class="form-group">
                            <label for="first_name">Nombres</label>
                            <input class="form-control" type="text" name="first_name" id="first_name"
                                value="{{ auth()->user()->first_name }} " required />
                        </div>
                        <div class="form-group">
                            <label for="last_name">Apellidos</label>
                            <input class="form-control" type="text" name="last_name" id="last_name"
                                value="{{ auth()->user()->last_name }}" required />
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input class="form-control text-center" type="email" name="email" id="email"
                                value="{{ auth()->user()->email }}" readonly />
                        </div>
                        <div class="form-group">
                            <label for="cellphone">Teléfono Celular</label>
                            <input class="form-control" type="text" name="cellphone" id="cellphone"
                                value="{{ auth()->user()->cellphone }}" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-4 offset-md-4">
                        <div class="form-group">
                            <label for="password">Contraseña</label>
                            <input class="form-control" type="password" name="password" id="password" />
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-4 col-4">
                        <div class="form-group">
                            <label for="password_confirmation">Confirmar Contraseña</label>
                            <input class="form-control" type="password" name="password_confirmation"
                                id="password_confirmation" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row  float-right">
                    <div class="col-sm-12 col-12">
                        <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i>&nbsp;Actualizar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
