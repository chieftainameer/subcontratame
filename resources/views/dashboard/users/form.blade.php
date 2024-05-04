@csrf
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <label for="role">Rol</label>
            <select class="form-control" name="role" id="role">
                <option value="">Elegir rol</option>
                <option value="1">Administrador</option>
                <option value="2">Usuario</option>
            </select>
        </div>
        <!-- Choose image ro profile -->
        <img class="img-rounded profile" src="" id="imgProfile">
        <div class="form-group">
            <label for="image">Choose image</label>
            <input type="file" name="image" id="image" class="form-control" onchange="imagePreview(this)">
        </div>
    </div>
    <div class="col-sm-8">
        <div class="form-group">
            <label for="first_name">Nombres</label>
            <input class="form-control" type="text" name="first_name" id="first_name" />
        </div>
        <div class="form-group">
            <label for="last_name">Apellidos</label>
            <input class="form-control" type="text" name="last_name" id="last_name" />
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <div class="row">
                <div class="col-10">
                    <input class="form-control" type="email" name="email" id="email" readonly required />
                </div>
                <div class="col-1">
                    <input class="form-control" type="checkbox" name="hide_email" id="email-hidden" />
                    <label for="email-hidden">Ocultar</label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="cellphone">Teléfono Celular</label>
            <div class="row">
                <div class="col-10">
                    <input class="form-control" type="text" name="cellphone" id="cellphone" />
                </div>
                <div class="col-1">
                    <input class="form-control" type="checkbox" name="hide_cellphone" id="cellphone-hidden" />
                    <label for="cellphone-hidden">Ocultar</label>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row show_field">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="nif">NIF/CIF</label>
            <input class="form-control" type="text" name="nif" id="nif" />
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="legal_form_id">Tipo de Forma Juridica</label>
            <select class="form-control" name="legal_form_id" id="legal_form_id">
                <option value="">Seleccione una opción</option>
                @foreach ($legal_forms as $legal_form)
                    <option value="{{ $legal_form->id }}">{{ $legal_form->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-sm-12 view_other">
        <div class="form-group">
            <label for="other">Otra Forma Jurídica</label>
            <input class="form-control" type="text" name="other" id="other" />
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="autonomous_community">Comunidad Autonoma</label>
            <select class="form-control" name="autonomous_community" id="autonomous_community" required>
                <option value="">Seleccione una opción</option>
                @foreach ($autonomous_communities as $autonomous_community)
                    <option value="{{ $autonomous_community->id }}">{{ $autonomous_community->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="province_id">Provincia</label>
            <select class="form-control" name="province_id" id="province_id" required>
                <option value="">Seleccione una opción</option>
            </select>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <label for="company_name">Nombre de la empresa</label>
            <input class="form-control" type="text" name="company_name" id="company_name" />
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <label for="company_description">Descripción de la empresa</label>
            <textarea class="form-control" name="company_description" id="company_description" cols="30" rows="5"></textarea>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <label for="position">Mi cargo en la empresa</label>
            <input class="form-control" type="text" name="position" id="position" />
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <label for="tax_residence">Domicilio Fiscal</label>
            <textarea class="form-control" name="tax_residence" id="tax_residence" cols="30" rows="5"></textarea>
        </div>
    </div>
    <div class="col-lg-12 col-md-12">
        <div class="d-flex">
            <label class="m-b15">Tags/Palabras claves</label>
        </div>
        <div class="tagInput">
            <input class="form-control" type="text" id="key_words" name="key_words" data-role="tagsinput">
        </div>
    </div>
    <div class="col-lg-6 col-md-6 mt-2">
        <label class="font-weight-700">Categoría de interes*</label><br>
        <div class="form-group form-check">
            @foreach ($categories as $category)
                {{-- <span class="custom-control custom-checkbox"> --}}
                <input type="checkbox" class="form-check-input" id="category_{{ $category->id }}" name="category"
                    value="{{ $category->id }}" />
                <label class="form-check-label" for="category_{{ $category->id }}">{{ $category->name }}</label><br>
                {{-- </span> --}}
            @endforeach
        </div>
    </div>
    <div class="col-lg-6 col-md-6 mt-2">
        <div class="form-group">
            <label class="font-weight-700">Formas de pago *</label><br>
            @foreach ($payment_methods as $payment_method)
                {{-- <span class="custom-control custom-checkbox"> --}}
                <input type="checkbox" class="form-check-input" id="payment_method_{{ $payment_method->id }}"
                    name="payment_method" value="{{ $payment_method->id }}" />
                <label class="form-check-label"
                    for="payment_method_{{ $payment_method->id }}">{{ $payment_method->name }}</label><br>
                {{-- </span> --}}
            @endforeach
        </div>
    </div>

</div>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <label for="password">Contraseña</label>
            <input class="form-control" type="password" name="password" id="password" />
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label for="password_confirmation">Confirmar Contraseña</label>
            <input class="form-control" type="password" name="password_confirmation" id="password_confirmation" />
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label for="status">Estatus</label>
            <select class="form-control" name="status" id="status">
                <option value="">Seleccione una opción</option>
                <option value="0">Inactivo</option>
                <option value="1">Activo</option>
                <option value="2">Pendiente</option>
                <option value="3">Bloqueado</option>
            </select>
        </div>
    </div>
</div>


{{-- @csrf
<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="role">Rol de usuario</label>
            <select class="form-control" id="role" name="role">
                <option value="">Elegir rol</option>
                <option value="1">Administrador</option>
                <option value="2">Medico/Lider</option>
                <option value="3">Titular</option>
                <option value="4">Afiliado</option>
                <option value="5">Empresa</option>
            </select>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="dni">DNI</label>
            <input type="text" class="form-control" id="dni" name="dni" placeholder="ingrese DNI" required>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <!-- Choose image ro profile -->
        <img class="img-rounded" src="{{ asset('dashboard/app-assets/images/avatars/default-user.png') }}"
            id="imgProfile">
        <div class="form-group">
            <label for="image">Elegir Imagen</label>
            <input type="file" name="image" id="image" class="form-control" onchange="imagePreview(this)">
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="name">Nombre</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Ingrese Nombre" required>
        </div>
        <div class="form-group">
            <label for="last_name">Apellidos</label>
            <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Ingrese Apellidos" required>
        </div>
        <div class="form-group d-none afiliate">
            <label for="user_id">Titular</label>
            <select class="form-control" name="user_id" id="user_id" required>
                <option value="">Elegir Titular</option>
                @foreach ($holders as $holder)
                    <option value="{{ $holder->id }}">{{ $holder->dni.' - '.$holder->name.' '.$holder->last_name }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="row d-none users enterprise">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="address">Dirección</label>
            <input type="text" name="address" id="address" class="form-control" placeholder="Ingrese Dirección" required>
            <input type="hidden" name="lat" id="lat">
            <input type="hidden" name="lng" id="lng">
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="cp">Código Postal</label>
            <input type="text" name="cp" id="cp" class="form-control" placeholder="Ingrese Código Postal" data-rule-minlength="5" data-rule-maxlength="5">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="phone">Teléfono</label>
            <input type="text" class="form-control" id="phone" name="phone" placeholder="Ingrese Teléfono" required>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="email">Correo</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Ingrese Correo" required>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Ingrese su contraseña" autocomplete="false" required>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="password_confirmation">Confirmar contraseña</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirmar contraseña" autocomplete="false" required>
        </div>
    </div>
</div>
<div class="row d-none users">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="typeinsurance_id">Tipo de seguro</label>
            <select type="text" class="form-control" id="typeinsurance_id" name="typeinsurance_id">
                <option value="">Seleccionar Tipo de seguro</option>
                @foreach ($typeinsurances as $typeinsurance)
                    <option value="{{ $typeinsurance->id }}">{{ $typeinsurance->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="blood_type">Grupo Sanguineo</label>
            <select class="form-control" id="blood_type" name="blood_type">
                <option value="">Elegir Grupo</option>
                <option value="1">O-</option>
                <option value="2">O+</option>
                <option value="3">A-</option>
                <option value="4">A+</option>
                <option value="5">B-</option>
                <option value="6">B+</option>
                <option value="7">AB-</option>
                <option value="8">AB+</option>
            </select>
        </div>
    </div>
</div>
<div class="row d-none users">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="birthdate">Fecha de nacimiento</label>
            <input type="date" class="form-control" id="birthdate" name="birthdate">
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="empresa_id">Empresa</label>
            <select class="form-control" name="empresa_id" id="empresa_id">
                <option value="">Seleccionar Empresa</option>
                @foreach ($enterprises as $enterprise)
                <option value="{{ $enterprise->id }}">{{ $enterprise->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="form-group d-none medical">
            <label for="medical_license">Licencia médica</label>
            <input type="text" class="form-control" id="medical_license" name="medical_license" placeholder="Licencia médica" required>
        </div>
        <div class="form-group">
            <label for="status">Estatus</label>
            <select class="form-control" id="status" name="status" required>
                <option value="">Elegir estatus</option>
                <option value="0">Inactivo</option>
                <option value="1">Activo</option>
                <option value="2">Pendiente</option>
                <option value="3">Bloqueado</option>
            </select>
        </div>
    </div>
    <div class="col-sm-6 d-none medical">
        <!-- Choose image Signature -->
        <img class="img-rounded" src="{{ asset('images/default.png') }}"
            id="imgSignature">
        <div class="form-group">
            <label for="signature">Cargar firma electrónica</label>
            <input type="file" name="signature" id="signature" class="form-control" onchange="signaturePreview(this)">
        </div>
    </div>
</div> --}}
