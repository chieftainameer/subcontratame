@extends('layouts.app')
@section('content')
    <div class="card">
        <form id="frmEdit">
            <input type="hidden" id="id" name="id" value="{{ isset($setting) ? $setting->id : '' }}">
            <input type="hidden" id="direction" name="direction" value="prices">
            <div class="card-header">
                <h2 class="m-0 font-weight-bold text-primary">Precios</h2>
                <button type="submit" class="btn btn-success" style="float:right; margin-top:-6px;">Guardar</button>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 col-sm-12">
                        <div class="form-group">
                            <label for="price_departure">Precio por partida básica</label>
                            <input type="number" min="0" step="0.50" class="form-control" id="price_departure"
                                name="price_departure"
                                value="{{ isset($setting) ? (float) $setting->price_departure : '' }}" />
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-12">
                        <div class="form-group">
                            <label for="price_variable">Precio por variable sumplementaria adicional</label>
                            <input type="number" min="0" step="0.50" class="form-control" id="price_variable"
                                name="price_variable"
                                value="{{ isset($setting) ? (float) $setting->price_variable : '' }}" />
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-12">
                        <div class="form-group">
                            <label for="price_variable">% del Iva</label>
                            <input type="number" min="0" step="0.50" class="form-control" id="percentage_iva"
                                name="percentage_iva"
                                value="{{ isset($setting) ? (float) $setting->percentage_iva : '' }}" />
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
