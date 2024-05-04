<div class="row">
    <div class="col-lg-12 col-md-12">
        <label class="font-weight-700">Tipo *</label>
        <div class="form-group">
            <select class="form-control" name="type" id="type" style="padding: 5px;" required>
                <option value="">Seleccione una opción</option>
                <option value="1">Original</option>
                <option value="2">Alternativo</option>
            </select>
        </div>
    </div>
    <div class="col-lg-12 col-md-12">
        <label class="font-weight-700">Incluye *</label>
        <div class="form-group">
            <select class="form-control" name="includes" id="includes" style="padding: 5px;" required>
                <option value="">Seleccione una opción</option>
                <option value="1">Suministro</option>
                <option value="2">Instalación</option>
                <option value="3">Instalación + Suministro</option>
            </select>
        </div>
    </div>
    <div class="col-lg-12 col-md-12">
        <label for="">Cantidad: <b><span id="quantity"></span></b></label>
    </div>
    <div class="col-lg-12 col-md-12">
        <label for="">Dimensión: <b><span id="dimension"></span></b></label>
    </div>
    <div class="col-lg-12 col-md-12">
        <div class="form-group">
            <label class="font-weight-700">Cantidad que puede ejecutar *</label>
            <input type="number" class="form-control" name="quantity" id="quantity" min="0" required />
        </div>
    </div>
    <div class="col-lg-6 col-md-6">
        <div class="form-group">
            <label class="font-weight-700">Precio unitario *</label>
            <input type="number" class="form-control" name="price_unit" id="price_unit" min="0" required />
        </div>
    </div>
    <div class="col-lg-6 col-md-6">
        <div class="form-group">
            <label class="font-weight-700">Precio total *</label>
            <input type="number" class="form-control text-right" name="price_total" id="price_total" readonly />
        </div>
    </div>
    <div class="col-lg-6 col-md-6">
        <div class="form-group form-check">
            <span class="custom-control custom-checkbox custom-control-inline">
                <input type="checkbox" class="form-check-input" id="iva" name="iva">
                <label class="form-check-label" for="iva">Incluye iva</label>
            </span>
        </div>
    </div>
</div>
