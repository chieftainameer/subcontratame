<div class="py-3">
    <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseFilter" aria-expanded="false" aria-controls="collapseFilter">
        Filtros
    </button>
</div>
<div class="collapse pb-4" id="collapseFilter">
    <div class="card card-body">
        <div class="row">
            <div class="col-12">
                <label>Tipo de Servicio:</label>
                <div class="d-flex justify-content-start align-items-center">
                <div class="form-group form-check mr-3">
                    <input type="checkbox" name="services[]" value="2" class="form-check-input" id="instalacion" {{ in_array('2', old('services', [])) ? 'checked' : '' }}>
                    <label class="form-check-label" for="instalacion">Instalación</label>
                </div>
                <div class="form-group form-check mr-3">
                    <input type="checkbox" name="services[]" value="1" class="form-check-input" id="suministro" {{ in_array('1', old('services', [])) ? 'checked' : '' }}>
                    <label class="form-check-label" for="suministro">Suministro</label>
                </div>
                    <div class="form-group form-check mr-3">
                        <input type="checkbox" name="services[]" value="3" class="form-check-input" id="instalacion_suministro" {{ in_array('3', old('services', [])) ? 'checked' : '' }}>
                        <label class="form-check-label" for="instalacion_suministro">Suministro e instalación</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <label>Tipo Empresa:</label>
                <div class="d-flex justify-content-start align-items-center">
                    <div class="form-group form-check mr-3">
                        <input type="checkbox" name="companyType[]" value="3" class="form-check-input" id="sl" {{ in_array('3', old('companyType', [])) ? 'checked' : '' }}>
                        <label class="form-check-label" for="sl">SL</label>
                    </div>
                    <div class="form-group form-check mr-3">
                        <input type="checkbox" name="companyType[]" value="1" class="form-check-input" id="autonomo" {{ in_array('1', old('companyType', [])) ? 'checked' : '' }}>
                        <label class="form-check-label" for="autonomo">Autónomos</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <label>Tipo:</label>
                <div class="d-flex justify-content-start align-items-center">
                    <div class="form-group form-check mr-3">
                        <input type="checkbox" name="tipo[]" value="1" class="form-check-input" id="sl" {{ in_array('1', old('tipo', [])) ? 'checked' : '' }}>
                        <label class="form-check-label" for="sl">Original</label>
                    </div>
                    <div class="form-group form-check mr-3">
                        <input type="checkbox" name="tipo[]" value="2" class="form-check-input" id="autonomo" {{ in_array('2', old('tipo', [])) ? 'checked' : '' }}>
                        <label class="form-check-label" for="autonomo">Alternativo</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <label>Métodos de pago:</label>
                <div class="d-flex justify-content-start align-items-center flex-wrap">
                    @foreach($paymentMethods as $paymentMethod)
                        <div class="form-group form-check mr-3">
                            <input type="checkbox" name="paymentMethods[]" value="{{ $paymentMethod->id }}" class="form-check-input" id="paymentMethod{{$paymentMethod->id}}" {{ in_array($paymentMethod->id, old('paymentMethods', [])) ? 'checked' : '' }}>
                            <label class="form-check-label" for="paymentMethod{{$paymentMethod->id}}">{{ $paymentMethod->name }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-success">Filtrar</button>

    </div>
</div>