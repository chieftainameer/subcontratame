@extends('layouts.app')
@section('content')
    <div class="card">
        <form id="frmEdit">
            <input type="hidden" id="id" name="id" value="{{ isset($setting) ? $setting->id : '' }}">
            <input type="hidden" id="direction" name="direction" value="terms-conditions">
            <div class="card-header">
                <h2 class="m-0 font-weight-bold text-primary">Términos y Condiciones</h2>
                <button type="submit" class="btn btn-success" style="float:right; margin-top:-6px;">Guardar</button>
            </div>
            <div class="card-body">
                <textarea id="terms_conditions" name="terms_conditions" cols="30" rows="10" class="form-control">{{ isset($setting) ? $setting->terms_conditions : '' }}</textarea>
            </div>
        </form>
    </div>
@endsection
