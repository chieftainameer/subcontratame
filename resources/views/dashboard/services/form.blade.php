@csrf
<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="user_id">Paciente</label>
            <select class="form-control" id="user_id" name="user_id" required>
                <option value="">Elegir paciente</option>
                @foreach ($patients as $patient)
                    <option value="{{ $patient->id }}">
                        {{ $patient->dni . ' ' . $patient->name . ' ' . $patient->last_name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="afiliate_user_id">Afiliado</label>
            <select class="form-control" id="afiliate_user_id" name="afiliate_user_id">
                <option value="">Elegir paciente</option>
            </select>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="type">Tipo</label>
            <select class="form-control" id="type" name="type" required>
                <option value="">Elegir servicio</option>
                <option value="1">Consulta médica</option>
                <option value="2">Ambulancia</option>
            </select>
        </div>
    </div>
    <div class="col-sm-6 d-none ambulance">
        <div class="form-group">
            <label for="type_transportation">Tipo de traslado</label>
            <select class="form-control" id="type_transportation" name="type_transportation" required>
                <option value="">Tipo de traslado</option>
                <option value="1">Urgencia</option>
                <option value="2">Emergencia</option>
            </select>
        </div>
    </div>
    <div class="col-sm-6 d-none medic">
        <div class="form-group">
            <label for="medical_id">Médico</label>
            <select class="form-control" id="medical_id" name="medical_id">
                <option value="">Elegir médico</option>
                @foreach ($medicals as $medic)
                    <option value="{{ $medic->id }}">{{ $medic->dni . ' ' . $medic->name . ' ' . $medic->last_name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-sm-6 d-none ambulance">
        <div class="form-group">
            <label for="ambulance_id">Ambulancia</label>
            <select class="form-control" id="ambulance_id" name="ambulance_id">
                <option value="">Elegir ambulancia</option>
                @foreach ($ambulances as $ambulance)
                    <option value="{{ $ambulance->id }}">{{ $ambulance->name . ' ' . $ambulance->vehicle_license }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-sm-6 d-none ambulance">
        <div class="form-group">
            <label for="address">Dirección de Abordaje</label>
            <input type="text" name="address" id="address" class="form-control" placeholder="Ingrese Dirección"
                required>
            <input type="hidden" name="lat" id="lat">
            <input type="hidden" name="lng" id="lng">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <label for="description">Descripción</label>
            <textarea class="form-control" id="description" name="description"></textarea>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6"></div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="status">Estatus</label>
            <select class="form-control" id="status" name="status" required>
                <option value="">Elegir status</option>
                <option value="0">Pendiente</option>
                <option value="1" class="medical">{{ __('En camino') }}</option>
                <option value="2">{{ __('En atención') }}</option>
                <option value="3" class="medical">{{ __('En traslado') }}</option>
                <option value="4">{{ __('Realizando informe') }}</option>
                <option value="5">{{ __('Informe finalizado') }}</option>
                <option value="6">{{ __('Cancelado') }}</option>
            </select>
        </div>
    </div>
</div>
