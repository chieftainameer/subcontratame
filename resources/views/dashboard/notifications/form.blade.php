@csrf
<div class="row">
    <div class="col-sm-6">
        <!-- Choose image ro profile -->
        <img class="img-rounded" src="{{ asset('dashboard/app-assets/images/avatars/default-image.jpg') }}" id="imgProfile"
            style="width: 50%;margin-left: 25%; border-radius:50%;">
        <div class="form-group">
            <label for="image">Elegir imagen</label>
            <input type="file" name="image" id="image" class="form-control" onchange="imagePreview(this)">
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="name">Titulo</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="form-group">
            <label for="name">Subtitulo</label>
            <input type="text" class="form-control" id="subtitle" name="subtitle">
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="">Fecha Inicio</label>
                    <input type="date" class="form-control" name="date_start" id="date_start"
                        aria-describedby="helpId" placeholder="" required>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="">Fecha Fina</label>
                    <input type="date" class="form-control" name="date_end" id="date_end" aria-describedby="helpId"
                        placeholder="" required>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <label for="description">Descripción</label>
            <textarea class="form-control" id="description" name="description" required></textarea>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <label for="">Ciudad</label>
            <select class="form-control" name="city_id" id="city_id">
                @foreach ($cities as $city)
                    <option value="{{ $city->id }}"> {{ $city->province . ' - ' . $city->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label for="">Tipo</label>
            <select class="form-control" name="type" id="type" required>
                <option value="1">Todos</option>
                <option value="2">Titulares</option>
                <option value="3">Medicos / Lideres de ambulancia</option>
            </select>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label for="status">Estatus</label>
            <select class="form-control" id="status" name="status" required>
                <option value="1">Activo</option>
                <option value="0">Inactivo</option>
            </select>
        </div>
    </div>
</div>
