@csrf
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="form-group">
            <label class="font-weight-700">Fecha de entrega:&nbsp;</label>
            <span id="date_of_delivery"></span>
        </div>
    </div>
    <div class="col-lg-12 col-md-12">
        <div class="form-group">
            <label class="font-weight-700">Partida:&nbsp;</label>
            <span id="departure_name"></span>
        </div>
    </div>
    <div class="col-lg-12 col-md-12">
        <div class="form-group">
            <label class="font-weight-700">Vencimiento de tu oferta *</label>
            <input type="text" class="form-control text-center" name="expiration_date" id="expiration_date"
                autocomplete="off" required />
        </div>
    </div>
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
    <div class="col-lg-12 col-md-12 d-none" id="view_description">
        <label for="">Descripción</label>
        <div class="form-group">
            <input type="text" class="form-control" name="description" id="description">
        </div>
    </div>
    <div class="col-lg-12 col-md-12">
        <label class="font-weight-700 height-100">Incluye *</label>
        <div class="form-group">
            <select class="form-control" name="includes" id="includes" style="padding: 5px;" required>
                <option value="">Seleccione una opción</option>
                <option value="1">Suministro</option>
                <option value="2">Instalación</option>
                <option value="3">Suministro + Instalación</option>
            </select>
        </div>
    </div>
    <div class="col-lg-12 col-md-12">
        <label for="">Cantidad: <b><span id="quantity"></span></b></label>
    </div>
    <div class="col-lg-12 col-md-12">
        <label for="">Dimensión: <b><span id="dimension"></span></b></label>
    </div>
    <div class="col-lg-4 col-md-4">
        <div class="form-group">
            <label class="font-weight-700">Cantidad que puede ejecutar *</label>
            <input type="number" class="form-control text-right" name="quantity" id="quantity" min="0"
                required />
        </div>
    </div>
    <div class="col-lg-4 col-md-4">
        <div class="form-group">
            <label class="font-weight-700">Precio unitario *</label>
            <input type="number" class="form-control text-right" name="price_unit" id="price_unit" min="0"
                required />
        </div>
    </div>
    <div class="col-lg-4 col-md-4">
        <div class="form-group">
            <label class="font-weight-700">Total sin iva</label>
            <input type="number" class="form-control text-right" name="sub_total" id="sub_total" readonly />
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-12">
        <div class="form-group form-check">
            <span class="custom-control custom-checkbox custom-control-inline">
                <input type="checkbox" class="form-check-input" id="iva" name="iva">
                <label class="form-check-label" for="iva">IVA aplicado</label>
            </span>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-12">
        <div class="form-group">
            <label for="iva">Iva ({{ $percentage_iva ? $percentage_iva : 0 }}%)</label>
            <input type="number" class="form-control text-right" name="price_iva" id="price_iva" readonly />
        </div>
    </div>
    <div class="col-lg-4 col-md-4">
        <div class="form-group">
            <label class="font-weight-700">Total con iva</label>
            <input type="number" class="form-control text-right" name="price_total" id="price_total" readonly />
        </div>
    </div>
    <div class="col-lg-12 col-md-12 mt-4">
        <label class="font-weight-700">Seleccione el método de pago *</label><br>
        <div class="form-group form-check">
            @foreach ($payment_methods as $payment_method)
                <span class="custom-control custom-checkbox custom-control-inline">
                    <input type="checkbox" class="form-check-input" id="payment_method_{{ $payment_method->id }}"
                        name="payment_method" value="{{ $payment_method->id }}">
                    <label class="form-check-label"
                        for="payment_method_{{ $payment_method->id }}">{{ $payment_method->name }}</label>
                </span>
            @endforeach
        </div>
    </div>
    <div class="col-lg-12 col-md-12">
        <p class="mt-5"><i><b>Variable(s) suplementaria(s):</b></i></p>
        <div id="view_variables" class="mt-0 width-100"></div>
    </div>

</div>
