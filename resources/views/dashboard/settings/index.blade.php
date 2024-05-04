@extends('layouts.app')
@section('content')
    <form id="frmEdit">
        {{-- <input type="hidden" id="id" name="id" value="1"> --}}
        @csrf
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Configuración</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-4">
                        <!-- Choose image ro profile -->
                        <img class="img-rounded"
                            src="{{ $settings != null ? ($settings->logo == 'https://picsum.photos/200/300' ? $settings->logo : asset('storage/' . $settings->logo)) : 'https://picsum.photos/200/300' }}"
                            id="imgProfile">
                        <div class="form-group">
                            <label for="image">Elegir imagen</label>
                            <input type="file" name="image" id="image" class="form-control"
                                onchange="imagePreview(this)">
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="">Nombre de sitio</label>
                                    <input type="text" class="form-control" name="site_name" id="site_name"
                                        value="{{ $settings->site_name ?? '' }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="">Coreo</label>
                                    <input type="text" class="form-control" name="email" id="email"
                                        value="{{ $settings->email ?? '' }}">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="">Teléfono</label>
                                    <input type="text" class="form-control" name="phone" id="phone"
                                        value="{{ $settings->phone ?? '' }}">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="">Dirección</label>
                                    <input type="text" class="form-control" name="address" id="address"
                                        value="{{ $settings->address ?? '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="">Mensaje de refgeridos</label>
                                    <input type="text" class="form-control" name="message_sharing" id="message_sharing"
                                        value="{{ $settings->message_sharing ?? '' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <h4>Google Maps</h4>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="">App ID</label>
                            <input type="text" class="form-control" name="googlemaps_key" id="googlemaps_key"
                                aria-describedby="helpId" placeholder="" value="{{ $settings->googlemaps_key ?? '' }}">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="">App Token</label>
                            <input type="text" class="form-control" name="googlemaps_libraries" id="googlemaps_libraries"
                                aria-describedby="helpId" placeholder=""
                                value="{{ $settings->googlemaps_libraries ?? '' }}">
                        </div>
                    </div>
                </div>
                <h4>OneSignal</h4>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="">App ID</label>
                            <input type="text" class="form-control" name="onesignal_id" id="onesignal_id"
                                aria-describedby="helpId" placeholder="" value="{{ $settings->onesignal_id ?? '' }}">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="">App Token</label>
                            <input type="text" class="form-control" name="onesignal_token" id="onesignal_token"
                                aria-describedby="helpId" placeholder="" value="{{ $settings->onesignal_token ?? '' }}">
                        </div>
                    </div>
                </div>
                <hr>
                <h4>Firebase</h4>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">CloudStorage(FireStorage)</label>
                            <input type="text" class="form-control" name="firebase_firestore" id="firebase_firestore"
                                placeholder="" value="{{ $settings->firebase_firestore ?? '' }}">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Firebase(FireStore)</label>
                            <input type="text" class="form-control" name="firebase_bucket" id="firebase_bucket"
                                placeholder="" value="{{ $settings->firebase_bucket ?? '' }}">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Credenciales(Archivo JSON)</label>
                            <input type="file" class="form-control" name="firebase_credentials"
                                id="firebase_credentials">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Estatus</label>
                            <select class="form-control" name="firebase_status" id="firebase_status">
                                <option value="0"
                                    {{ isset($settings->firebase_status) && $settings->firebase_status == 0 ? 'selected' : '' }}>
                                    Inactivo
                                </option>
                                <option value="1"
                                    {{ isset($settings->firebase_status) && $settings->firebase_status == 1 ? 'selected' : '' }}>
                                    Activo
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for=""><b>Emergencia</b> Descripción</label>
                            <input type="text" class="form-control" name="emergency_description"
                                id="emergency_description" value="{{ $settings->emergency_description ?? '' }}">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for=""><b>Urgencia</b> Descripción</label>
                            <input type="text" class="form-control" name="urgency_description"
                                id="urgency_description" value="{{ $settings->urgency_description ?? '' }}">
                        </div>
                    </div>
                </div>
                <hr>
                <h4>Pagos</h4>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="">Precio por código</label>
                            <input type="number" step="0.01" min="0" class="form-control"
                                name="price_per_code" id="price_per_code" value="{{ $settings->price_per_code ?? '' }}">
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
