@extends('layouts.app')
@section('content')
    <div class="card">
        <form action="" id="frmEdit">
            <input type="hidden" id="id" name="id" value="{{ isset($setting) ? $setting->id : '' }}">
            <input type="hidden" id="direction" name="direction" value="about">
            <div class="card-header">
                <h2 class="m-0 font-weight-bold text-primary">Acerca de</h2>
                <button type="submit" class="btn btn-success" style="float:right; margin-top:-6px;">Guardar</button>
            </div>
            <div class="card-body">
                <textarea id="about" name="about" cols="30" rows="10" class="form-control">{{ isset($setting) ? $setting->about : '' }}</textarea>
            </div>
        </form>
    </div>
@endsection
