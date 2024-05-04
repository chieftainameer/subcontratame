@extends('layouts.frontend.app')
@section('content')
    <!-- Content -->
    <div class="page-content bg-white">
        <!-- contact area -->
        <div class="content-block">
            <!-- Browse Jobs -->
            <div class="section-full bg-white browse-job p-t50 p-b20">
                <form id="frmEdit">
                    <div class="container">
                        <div class="row">
                            <div class="col-xl-3 col-lg-4 m-b30">
                                <div class="sticky-top">
                                    <div class="candidate-info">
                                        <div class="candidate-detail text-center">
                                            <div class="">
                                                @php
                                                    //dd(asset('storage') . '/' . $user->image);
                                                @endphp
                                                <a href="javascript:void(0);">
                                                    <img alt=""
                                                        src="{{ ($user->image === null ? asset('dashboard/app-assets/images/avatars/default-user.png') : $user->image === 'https://picsum.photos/200/300') ? 'https://picsum.photos/200/300' : asset('storage') . '/' . $user->image }}"
                                                        id="imgProfile" title="Imagen">
                                                </a>
                                                <div class="upload-link" title="Seleccionar imagen"
                                                    style="white-space: nowrap; text-overflow: ellipsis; overflow: hidden;">
                                                    <input type="file" class="" id="image" name="image"
                                                        onchange="imagePreview(this)">
                                                    {{-- <i class="fa fa-camera"></i>&nbsp;Seleccionar --}}
                                                </div>
                                            </div>
                                            <div class="candidate-title">
                                                <div class="">
                                                    <h4 class="m-b5"><a
                                                            href="javascript:void(0);">{{ $user->first_name . ' ' . $user->last_name }}
                                                        </a></h4>
                                                    <p class="m-b0"><a href="javascript:void(0);">{{ $user->email }}</a>
                                                    </p>
                                                </div>
                                            </div>
                                            @php
                                                $average_rating_user = 0;
                                                if (
                                                    auth()
                                                        ->user()
                                                        ->ratings()
                                                        ->get()
                                                        ->count() > 0
                                                ) {
                                                    $average_rating_user =
                                                        auth()
                                                            ->user()
                                                            ->ratings()
                                                            ->get()
                                                            ->sum('rating') /
                                                        auth()
                                                            ->user()
                                                            ->ratings()
                                                            ->get()
                                                            ->count();
                                                }
                                            @endphp
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 mt-3">
                                                    <span class="ratings_user"
                                                        data-rating-user="{{ $average_rating_user }}"></span>
                                                    ({{ floor($average_rating_user) }}) |
                                                    {{ auth()->user()->ratings()->get()->count() }}
                                                </div>
                                            </div>
                                        </div>
                                        @include('layouts.frontend.app-menu-profile')
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-9 col-lg-8 m-b30">
                                <div class="job-bx job-profile">
                                    <input type="hidden" id="id" name="id" value="{{ $user->id }} ">
                                    <div class="job-bx-title clearfix">
                                        <div class="clearfix"></div>
                                        <h4 class="font-weight-700 m-b5 text-center">ACTUALIZAR DATOS</h4>
                                    </div>
                                    <div class="row">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12">
                                                <h6>Datos personales</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="row m-b30">
                                            <div class="col-lg-6 col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-700">Nombre *</label>
                                                    <input name="first_name" id="first_name" required class="form-control"
                                                        type="text" value="{{ $user->first_name }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-700">Apellido *</label>
                                                    <input name="last_name" id="last_name" required class="form-control"
                                                        type="text" value="{{ $user->last_name }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="form-group">
                                                    <label for="cellphone" class="font-weight-700">Telefono</label>
                                                    <div class="row">
                                                        <div class="col-10">
                                                            <input class="form-control text-center" style="padding: 5px;" type="text" name="cellphone" id="cellphone" value="{{ $user->cellphone }}" required />
                                                        </div>
                                                        <div class="col-2">
                                                            <input class="form-control" type="checkbox" name="hide_cellphone" id="cellphone-hidden-box" {{ $user->hide_cellphone == 1 ? 'checked' : '' }}/>
                                                            <label for="cellphone-hidden-box">Ocultar</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="form-group">
                                                    <label for="email" class="font-weight-700">Correo Electrónico</label>
                                                    <div class="row">
                                                        <div class="col-10">
                                                            <input class="form-control text-center" style="padding: 5px;" type="email" name="email" id="email" value="{{ $user->email }}" readonly />
                                                        </div>
                                                        <div class="col-2">
                                                            <input class="form-control" type="checkbox" name="hide_email" id="email-hidden-box" {{ $user->hide_email ? 'checked' : '' }} />
                                                            <label for="email-hidden-box">Ocultar</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-700">Nueva contraseña</label>
                                                    <input name="password" id="password" class="form-control "
                                                        type="password" />
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-700">Confirmar contraseña</label>
                                                    <input name="password_confirmation" id="password_confirmation"
                                                        class="form-control " type="password" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12">
                                                <h6>Datos de la empresa</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="row m-b30">
                                            <div class="col-lg-6 col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-700">Nombre *</label>
                                                    <input name="company_name" id="company_name" required
                                                        class="form-control" type="text"
                                                        value="{{ $user->company_name }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-700">NIF/CIF *</label>
                                                    <input name="nif" id="nif" required class="form-control"
                                                        type="text" value="{{ $user->nif }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-700">Tipo de forma jurídica
                                                        *</label>
                                                    <select name="legal_form_id" id="legal_form_id" required
                                                        class="form-control" style="padding: 5px;">
                                                        <option value="">Seleccione una opción</option>
                                                        @foreach ($legal_forms as $legal_form)
                                                            @if ($legal_form->id === $user->legal_form_id)
                                                                <option value="{{ $legal_form->id }}" selected>
                                                                    {{ $legal_form->name }}
                                                                </option>
                                                            @else
                                                                <option value="{{ $legal_form->id }}">
                                                                    {{ $legal_form->name }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-700">Mi cargo (puesto en la
                                                        empresa) *</label>
                                                    <input name="position" id="position" class="form-control "
                                                        type="text" value="{{ $user->position }}" required />
                                                </div>
                                            </div>
                                            {{-- @php
                                                dd($user->province()->first());
                                            @endphp --}}
                                            <div class="col-lg-6 col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-700">Comunidad Autonoma *</label>
                                                    <select name="autonomous_community" id="autonomous_community" required
                                                        class="form-control" style="padding: 5px;">
                                                        <option value="">Seleccione una opción</option>
                                                        @foreach ($autonomous_communities as $autonomous_community)
                                                            @if ($autonomous_community->id ===
                                                                $user->province()->first()->community()->first()->id)
                                                                <option value="{{ $autonomous_community->id }}"
                                                                    selected="true">
                                                                    {{ $autonomous_community->name }}</option>
                                                            @else
                                                                <option value="{{ $autonomous_community->id }}">
                                                                    {{ $autonomous_community->name }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            @php
                                                $community = $user
                                                    ->province()
                                                    ->first()
                                                    ->community()
                                                    ->first()->id;
                                                $provinces = \App\Models\Province::where('status', 1)
                                                    ->where('autonomous_community_id', $community)
                                                    ->get();
                                                //dd($provinces);
                                            @endphp
                                            <div class="col-lg-6 col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-700">Provincia *</label>
                                                    <select name="province_id" id="province_id" required
                                                        class="form-control" style="padding: 5px;">
                                                        <option value="">Seleccione una opción</option>
                                                        @foreach ($provinces as $province)
                                                            @if ($province->id === $user->province_id)
                                                                <option value="{{ $province->id }}" selected="true">
                                                                    {{ $province->name }}</option>
                                                            @else
                                                                <option value="{{ $province->id }}">
                                                                    {{ $province->name }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-lg-12 col-md-12">
                                                <div class="form-group">
                                                    <label class="font-weight-700">Domicilio Fiscal *</label>
                                                    <textarea name="tax_residence" id="tax_residence" required class="form-control" cols="30" rows="10">{{ $user->tax_residence }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12">
                                                <div class="form-group">
                                                    <label class="font-weight-700">Descripción de la empresa
                                                        *</label>
                                                    <textarea name="company_description" id="company_description" required class="form-control" cols="30"
                                                        rows="10">{{ $user->company_description }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12">
                                                <h6>Datos de interes</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="row m-b30">
                                            @php
                                                foreach ($user->categories()->get() as $key => $category) {
                                                    $categories_array[] = $category->id;
                                                }
                                                foreach ($user->payment_methods()->get() as $key => $payment) {
                                                    $payments_array[] = $payment->id;
                                                }
                                            @endphp
                                            <div class="col-lg-6 col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-700">Categoría de interes*</label><br>
                                                    @foreach ($categories as $category)
                                                        @if (in_array($category->id, $categories_array))
                                                            <span class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input"
                                                                    id="category_{{ $category->id }}" name="category"
                                                                    value="{{ $category->id }}" checked="true" required>
                                                                <label class="custom-control-label"
                                                                    for="category_{{ $category->id }}">{{ $category->name }}</label>
                                                            </span>
                                                        @else
                                                            <span class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input"
                                                                    id="category_{{ $category->id }}" name="category"
                                                                    value="{{ $category->id }}" required>
                                                                <label class="custom-control-label"
                                                                    for="category_{{ $category->id }}">{{ $category->name }}</label>
                                                            </span>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-700">Formas de pago *</label><br>
                                                    @foreach ($payment_methods as $payment_method)
                                                        @if (in_array($payment_method->id, $payments_array))
                                                            <span class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input"
                                                                    id="payment_method_{{ $payment_method->id }}"
                                                                    name="payment_method"
                                                                    value="{{ $payment_method->id }}" checked="true"
                                                                    required>
                                                                <label class="custom-control-label"
                                                                    for="payment_method_{{ $payment_method->id }}">{{ $payment_method->name }}</label>
                                                            </span>
                                                        @else
                                                            <span class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input"
                                                                    id="payment_method_{{ $payment_method->id }}"
                                                                    name="payment_method"
                                                                    value="{{ $payment_method->id }}" required>
                                                                <label class="custom-control-label"
                                                                    for="payment_method_{{ $payment_method->id }}">{{ $payment_method->name }}</label>
                                                            </span>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12">
                                                <div class="d-flex">
                                                    <h5 class="">Tags/Palabras claves</h5>
                                                </div>
                                                <span class="text-muted mb-1">Al momento de ingresar las
                                                    palabras debe separarlas por , (coma)</span>
                                                <div class="tagInput">
                                                    <input class="form-control" type="text" id="key_words"
                                                        name="key_words" data-role="tagsinput"
                                                        value="{{ $user->key_words }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="row  float-right">
                                            <div class="col-lg-12 col-md-12">
                                                <button class="site-button m-b30" type="submit"
                                                    style="float: right">Actualizar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- Browse Jobs END -->
        </div>
    </div>
    <!-- Content END-->
@endsection
