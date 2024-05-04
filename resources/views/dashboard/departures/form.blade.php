<div class="row">
    <div class="col-lg-4 col-md-4">
        <div class="form-group">
            <label class="font-weight-700">Código *</label>
            <input type="text" class="form-control" name="code" id="code" required />
        </div>
    </div>
    <div class="col-lg-8 col-md-8">
        <div class="form-group">
            <label class="font-weight-700">Descripción *</label><br>
            <input type="text" class="form-control" name="description" id="description" required>
        </div>
    </div>
    <div class="col-lg-4 col-md-4">
        <div class="form-group">
            <label class="font-weight-700">Fecha estimada de entrega *</label>
            <input type="date" class="form-control text-center" name="execution_date" id="execution_date"
                autocomplete="off" required />
        </div>
    </div>
    <div class="col-lg-4 col-md-4">
        <div class="form-group">
            <label class="font-weight-700">Cantidad *</label>
            <input type="number" class="form-control" name="quantity" id="quantity" min="0" required />
        </div>
    </div>
    <div class="col-lg-4 col-md-4">
        <label class="font-weight-700">Dimensiones *</label><br>
        <div class="form-group">
            <select name="dimensions" id="dimensions" required class="form-control" style="padding: 5px;">
                <option value="">Seleccione una opción</option>
                <option value="Unidad">Unidad</option>
                <option value="Kg">Kg</option>
                <option value="m3">m3</option>
                <option value="m2">m2</option>
                <option value="Litro">Litro</option>
                <option value="ML">ML</option>
            </select>
        </div>
    </div>
    <div class="col-lg-6 col-md-6">
        <div class="form-group form-check">
            <span class="custom-control custom-checkbox custom-control-inline">
                <input type="checkbox" class="form-check-input" id="visible" name="visible">
                <label class="form-check-label" for="visible">Visible</label>
            </span>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 d-none" id="view_complete">
        <div class="form-group form-check float-right">
            <span class="custom-control custom-checkbox custom-control-inline">
                <input type="checkbox" class="form-check-input" id="complete" name="complete">
                <label class="form-check-label" for="complete">Completado</label>
            </span>
        </div>
    </div>

    <div class="col-lg-12 col-md-12 p-2">
        <a href="#" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#mdlVariableNew"
            role="dialog"><i class="fa fa-plus"></i>&nbsp;Agregar
            variable</a>
    </div>
    <div style="width: 100%; height: 200px; overflow: auto;" class="p-2 mt-1">
        <table class="table table-job-bx browse-job" id="tableVariableData" style="width:100%">
            <thead>
                <tr>
                    <th>Variable</th>
                    <th>Opciones</th>
                    <th>Obligatorio</th>
                    <th>Visible</th>
                    <th>Acciones</th>
                    <th></th> {{-- 5 - type --}}
                    <th></th> {{-- 6 - options --}}
                    <th></th> {{-- 7 - text --}}
                    <th></th> {{-- 8 - id --}}
                    <th></th> {{-- 9 - file --}}
                    <th></th> {{-- 10 - Aux --}}
                    <th></th> {{-- 11 - Status --}}
                    <th></th> {{-- 12 - Download file --}}
                    <th></th> {{-- 13 - Aux download file --}}
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
    <div class="col-lg-4 offset-lg-8 col-md-4 offset-md-8">
        <label class="font-weight-700">Estatus *</label><br>
        <div class="form-group">
            <select name="status" id="status" required class="form-control" style="padding: 5px;">
                <option value="">Seleccione una opción</option>
                <option value="1">Pendiente</option>
                <option value="2">Pagado</option>
            </select>
        </div>
    </div>
</div>
