@csrf
<div class="row">
    <div class="col-lg-4 col-md-4 text-center">
        <!-- Choose image ro profile -->
        <img class="profile" src="{{ asset('images/image_default.jpeg') }}" name="imgProfile" id="imgProfile" width="400px"
            height="400px"><br>
        <div class="form-group mt-3" style="white-space: nowrap;text-overflow: ellipsis; overflow: hidden;">
            {{-- <label for="image" class="btn text-muted text-center">
                <i class="fa fa-camera"></i>&nbsp;
                Subir imagen<br>
            </label> --}}
            {{-- <input style="visibility:hidden" type="file" name="img_logo" id="img_logo"> --}}

            {{-- <label for="image">Choose image</label> --}}
            <input style="" type="file" name="image" id="image" class=""
                onchange="imagePreview(this)" required>
        </div>
    </div>
    <div class="col-lg-8 col-md-8">
        <div class="col-lg-12 col-md-12 d-none" id="view_code">
            <h6 class="text-blue"><i>Código:</i>&nbsp;<span id="field_code"></span></h6>
        </div>
        <div class="col-lg-12 col-md-12">
            <div class="form-group">
                <label class="font-weight-700">Título del proyecto *</label>
                <input type="text" class="form-control" name="title" id="title" required />
            </div>
        </div>
        <div class="col-lg-12 col-md-12">
            <label class="font-weight-700">Selecciona una o más categorías *</label><br>
            <div class="form-group form-check">
                {{-- @foreach ($categories as $category)
                    <span class="custom-control custom-checkbox custom-control-inline">
                        <input type="checkbox" class="form-check-input" id="category_{{ $category->id }}"
                            name="category" value="{{ $category->id }}">
                        <label class="form-check-label" for="category_{{ $category->id }}">{{ $category->name }}</label>
                    </span>
                @endforeach --}}
                <div id="consolaerror" class="consola"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12">
        <div class="form-group">
            <label class="font-weight-700">Descripción breve del proyecto *</label>
            <input type="text" class="form-control" name="short_description" id="short_description" required />
        </div>
    </div>
    <div class="col-lg-12 col-md-12">
        <div class="form-group">
            <label class="font-weight-700">Descripción detallada del proyecto *</label>
            <textarea class="form-control" name="detailed_description" id="detailed_description" cols="30" rows="10"
                required></textarea>
        </div>
    </div>
    <div class="col-lg-12 col-md-12">
        <label class="font-weight-700">Formas de pago *</label><br>
        <div class="form-group form-check">
            {{-- @foreach ($payment_methods as $payment_method)
                <span class="custom-control custom-checkbox custom-control-inline">
                    <input type="checkbox" class="form-check-input" id="payment_method_{{ $payment_method->id }}"
                        name="payment_method" value="{{ $payment_method->id }}">
                    <label class="form-check-label"
                        for="payment_method_{{ $payment_method->id }}">{{ $payment_method->name }}</label>
                </span>
            @endforeach --}}
        </div>
    </div>
    <div class="col-lg-6 col-md-6">
        <div class="form-group">
            <label class="font-weight-700">Comunidad Autonoma *</label>
            <select name="autonomous_community" id="autonomous_community" required class="form-control"
                style="padding: 5px;">
                <option value="">Seleccione una opción</option>
                {{-- @foreach ($communities as $community)
                    <option value="{{ $community->id }}">
                        {{ $community->name }}</option>
                @endforeach --}}
            </select>
        </div>
    </div>
    <div class="col-lg-6 col-md-6">
        <div class="form-group">
            <label class="font-weight-700">Provincia *</label>
            <select name="province_id" id="province_id" required class="form-control" style="padding: 5px;">
                <option value="">Seleccione una opción</option>
            </select>
        </div>
    </div>
    <div class="col-lg-12 col-md-12">
        <div class="form-group">
            <label class="font-weight-700">Lugar de entrega del proyecto *</label>
            <input type="text" class="form-control" name="delivery_place" id="delivery_place" required />
        </div>
    </div>
    <div class="col-lg-6 col-md-6">
        <div class="form-group">
            <label class="font-weight-700">Fecha de inicio del proyecto</label>
            <input type="text" name="start_date" id="start_date" class="form-control text-center"
                style="padding: 5px;" readonly />
        </div>
    </div>
    <div class="col-lg-6 col-md-6">
        <div class="form-group">
            <label class="font-weight-700">Fecha de culminación *</label>
            <input type="text" name="final_date" id="final_date" required class="form-control text-center"
                style="padding: 5px;" required />
        </div>
    </div>
</div>
