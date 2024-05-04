@extends('layouts.app')
@section('content')
    <div class="card">
        <form action="" id="frmEdit">
            <input type="hidden" id="id" name="id" value="{{ isset($setting) ? $setting->id : '' }}">
            <input type="hidden" id="direction" name="direction" value="contact">
            <div class="card-header">
                <h2 class="m-0 font-weight-bold text-primary">Contacto</h2>
                <button type="submit" class="btn btn-success" style="float:right; margin-top:-6px;">Guardar</button>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="contact_email">Correo</label>
                            <input type="email" class="form-control" id="contact_email" name="contact_email"
                                value="{{ isset($setting) ? $setting->contact_email : '' }}" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="contact_cellphone">Celular</label>
                            <input type="text" class="form-control" id="contact_cellphone" name="contact_cellphone"
                                value="{{ isset($setting) ? $setting->contact_cellphone : '' }}" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="contact_cellphone">Linkedin</label>
                            <input type="text" class="form-control" id="contact_linkedin" name="contact_linkedin"
                                value="{{ isset($setting) ? $setting->contact_linkedin : '' }}" />
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
