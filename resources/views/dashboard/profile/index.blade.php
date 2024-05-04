@extends('layouts.app')
@section('content')
<form id="frmUpdate">
    @csrf
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Perfil de usuario</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-4">
                    <!-- Choose image ro profile -->
                    <img class="img-rounded profile" src="{{ $data->image!='https://picsum.photos/200/300'?asset('storage'.'/'.$data->image):'https://picsum.photos/200/300' }}" id="imgProfile">
                    <div class="form-group">
                        <label for="image">Choose image</label>
                        <input type="file" name="image" id="image" class="form-control" onchange="imagePreview(this)">
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="">Nombre</label>
                                <input type="text" class="form-control" name="name" id="name" value="{{ $data->name??'' }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="">Correo</label>
                                <input type="text" class="form-control" name="email" id="email" value="{{ $data->email??'' }}" required>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="">Teléfono</label>
                                <input type="phone" class="form-control" name="phone" id="phone" value="{{ $data->phone??'' }}">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="">Dirección</label>
                                <input type="text" class="form-control" name="address" id="address" value="{{ $data->address??'' }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                              <label for="">Contraseña actual<span class="warning">*</span></label>
                              <input type="password" class="form-control" name="password" id="password" required>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="">Nueva contraseña</label>
                                <input type="password" class="form-control" name="newpassword" id="newpassword">
                              </div>
                        </div>
                        <div class="col-sm-4"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary float-right"><i class="fa fa-save"></i></button>
        </div>
    </div>
</form>
@endsection