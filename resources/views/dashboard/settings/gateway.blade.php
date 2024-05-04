@extends('layouts.app')
@section('content')
    <div class="card">
        <form id="frmEdit">
            <input type="hidden" id="id" name="id" value="{{ isset($setting) ? $setting->id : '' }}">
            <input type="hidden" id="direction" name="direction" value="payment-gateway">
            <div class="card-header">
                <h2 class="m-0 font-weight-bold text-primary">Credenciales de Stripe</h2>
                <button type="submit" class="btn btn-success" style="float:right; margin-top:-6px;">Guardar</button>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="price_departure">Public Key</label>
                            <input type="text" class="form-control" id="stripe_public_key" name="stripe_public_key"
                                value="{{ isset($setting) ? $setting->stripe_public_key : '' }}" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="price_variable">Secret Key</label>
                            <input type="text" class="form-control" id="stripe_secret_key" name="stripe_secret_key"
                                value="{{ isset($setting) ? $setting->stripe_secret_key : '' }}" />
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
