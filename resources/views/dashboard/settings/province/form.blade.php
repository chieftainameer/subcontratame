@csrf
<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="name">Nombre</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="name">Comunidad Autonoma</label>
            <select class="form-control" id="autonomous_community_id" name="autonomous_community_id" required></select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6"></div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="status">Estatus</label>
            <select class="form-control" id="status" name="status" required>
                <option value="1">Activo</option>
                <option value="0">Inactivo</option>
            </select>
        </div>
    </div>
</div>
