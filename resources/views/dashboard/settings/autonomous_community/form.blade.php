@csrf
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <label for="name">Nombre</label>
            <input type="text" class="form-control" id="name" name="name">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="form-group" >
            <label for="country">Pais</label>
            <select class="form-control" id="country_id" name="country_id" required></select>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="status">Estatus</label>
            <select class="form-control" id="status" name="status">
                <option value="1">Activo</option>
                <option value="0">Inactivo</option>
            </select>
        </div>
    </div>
</div>
