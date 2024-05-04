@extends('layouts.frontend.app')
@section('content')
    @if ($user)
        <!-- Content -->
        <div class="page-content bg-white">
            <!-- contact area -->
            <div class="content-block">
                <!-- Browse Jobs -->
                <div class="section-full bg-white browse-job p-t50 p-b20">
                    <form id="frmCompleteRegistration">
                        <div class="container">
                            <div class="row">
                                <div class="col-xl-3 col-lg-4 m-b30">
                                    <div class="sticky-top">
                                        <div class="candidate-info">
                                            <div class="candidate-detail text-center">
                                                {{-- <div class=""> --}}
                                                <a href="javascript:void(0);">
                                                    <img id="imgProfile" alt=""
                                                        src="{{ asset('dashboard/app-assets/images/avatars/default-user.png') }}">
                                                </a>
                                                <div style="white-space: nowrap;text-overflow: ellipsis; overflow: hidden;">
                                                    <div class="form-group mt-3">
                                                        <input style="" type="file" name="image" id="image"
                                                            class="" onchange="imagePreview(this)">
                                                    </div>
                                                </div>
                                                <div class="candidate-title">
                                                    <div class="">
                                                        <h4 class="m-b5"><a
                                                                href="javascript:void(0);">{{ $user->first_name . ' ' . $user->last_name }}
                                                            </a></h4>
                                                        <p class="m-b0"><a
                                                                href="javascript:void(0);">{{ $user->email }}</a>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            @include('layouts.frontend.app-menu-profile')
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-9 col-lg-8 m-b30">
                                    <div class="job-bx job-profile">
                                        <div class="job-bx-title clearfix">
                                            <a href="{{ route('home') }}"
                                                class="site-button right-arrow button-sm float-right mb-3">Inicio</a>
                                            <div class="clearfix"></div>
                                            <h4 class="font-weight-700 m-b5 text-center">COMPLETAR INFORMACIÓN</h4>
                                            <p class="font-weight-700 text-center">Ingrese los datos faltantes para poder
                                                ofertar o
                                                postular
                                                proyectos</p>

                                        </div>
                                        <input type="hidden" id="id" name="id" value="{{ $user->id }} ">
                                        <div class="row">
                                            <div class="row m-b30">
                                                <div class="col-lg-6 col-md-6">
                                                    <div class="form-group">
                                                        <label class="font-weight-700">Nombre de la empresa
                                                            *</label>
                                                        <input name="company_name" id="company_name" required
                                                            class="form-control" type="text">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6">
                                                    <div class="form-group">
                                                        <label class="font-weight-700">NIF/CIF *</label>
                                                        <input name="nif" id="nif" required class="form-control"
                                                            type="text">
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
                                                                <option value="{{ $legal_form->id }}">
                                                                    {{ $legal_form->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6">
                                                    <div class="form-group">
                                                        <label class="font-weight-700">Mi cargo (puesto en la
                                                            empresa) *</label>
                                                        <input name="position" id="position" class="form-control "
                                                            type="text" required />
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6">
                                                    <div class="form-group">
                                                        <label class="font-weight-700">Comunidad Autonoma *</label>
                                                        <select name="autonomous_community" id="autonomous_community"
                                                            required class="form-control" style="padding: 5px;">
                                                            <option value="">Seleccione una opción</option>
                                                            @foreach ($autonomous_communities as $autonomous_community)
                                                                <option value="{{ $autonomous_community->id }}">
                                                                    {{ $autonomous_community->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6">
                                                    <div class="form-group">
                                                        <label class="font-weight-700">Provincia *</label>
                                                        <select name="province_id" id="province_id" required
                                                            class="form-control" style="padding: 5px;">
                                                            <option value="">Seleccione una opción</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-12 col-md-12">
                                                    <div class="form-group">
                                                        <label class="font-weight-700">Domicilio Fiscal *</label>
                                                        <textarea name="tax_residence" id="tax_residence" required class="form-control" cols="30" rows="10"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12">
                                                    <div class="form-group">
                                                        <label class="font-weight-700">Descripción de la empresa
                                                            *</label>
                                                        <textarea name="company_description" id="company_description" required class="form-control" cols="30"
                                                            rows="10"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6">
                                                    <div class="form-group">
                                                        <label class="font-weight-700">Categoría de
                                                            interes*</label><br>
                                                        @foreach ($categories as $category)
                                                            <span class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input"
                                                                    id="category_{{ $category->id }}" name="category"
                                                                    value="{{ $category->id }}" required>
                                                                <label class="custom-control-label"
                                                                    for="category_{{ $category->id }}">{{ $category->name }}</label>
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6">
                                                    <div class="form-group">
                                                        <label class="font-weight-700">Formas de pago *</label><br>
                                                        @foreach ($payment_methods as $payment_method)
                                                            <span class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input"
                                                                    id="payment_method_{{ $payment_method->id }}"
                                                                    name="payment_method"
                                                                    value="{{ $payment_method->id }}" required>
                                                                <label class="custom-control-label"
                                                                    for="payment_method_{{ $payment_method->id }}">{{ $payment_method->name }}</label>
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12">
                                                    <div class="d-flex">
                                                        <h5 class="m-b15">Tags/Palabras claves</h5>
                                                    </div>
                                                    <span class="text-muted mb-1">Al momento de ingresar las
                                                        palabras debe separarlas por , (coma)</span>
                                                    <div class="form-group">
                                                        <div class="tagInput">
                                                            <input class="form-control" type="text" id="key_words"
                                                                name="key_words" data-role="tagsinput">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <button class="site-button m-b30" type="submit">Guardar</button>
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
        <!-- Modal Box -->
        <div class="modal fade lead-form-modal" id="car-details" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="modal-body row m-a0 clearfix">
                        <div class="col-lg-6 col-md-6 d-flex p-a0"
                            style="background-image: url(images/background/bg3.jpg);  background-position:center; background-size:cover;">

                        </div>
                        <div class="col-lg-6 col-md-6 p-a0">
                            <div class="lead-form browse-job text-left">
                                <form>
                                    <h3 class="m-t0">Personal Details</h3>
                                    <div class="form-group">
                                        <input value="" class="form-control" placeholder="Name" />
                                    </div>
                                    <div class="form-group">
                                        <input value="" class="form-control" placeholder="Mobile Number" />
                                    </div>
                                    <div class="clearfix">
                                        <button type="button" class="btn-primary site-button btn-block">Submit </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Box End -->
    @else
        <!-- Content -->
        <div class="browse-job login-style3">
            <div class="bg-img-fix"
                style="background-image:url('{{ asset('frontend/images/main-slider/bg6.jpg') }}'); height: 100vh;">
                <div class="row">
                    <div
                        class="col-xl-4 col-lg-5 col-md-6 col-sm-12 bg-white z-index2 relative p-a0 content-scroll skew-section left-bottom">
                        <div class="login-form style-2">
                            <div class="logo-header text-center p-tb30">
                                <a href="{{ route('home') }}">
                                    <h1 class="text-blue">INDEED</h1>
                                </a>
                            </div>
                            <div class="clearfix"></div>
                            <div class="tab-content nav p-b30 tab">
                                <div id="login" class="tab-pane active ">
                                    <div class="col-md-12 m-b30">
                                        <div class="tab-content">
                                            <h4 class="font-weight-700 m-b5 text-center">TOKEN INVÁLIDO</h4>
                                            <p class="font-weight-600 mt-3">El token para completar su registro es
                                                inválido, por
                                                favor
                                                envie un correo a <a href="mailto:4megatech2024@gmail.com"
                                                    style="color: blue">4megatech2024@gmail.com</a>.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Content END-->
    @endif











    {{-- <div class="section-full content-inner browse-job bg-white shop-account"
        style="background-image: url('{{ asset('frontend/images/background/bg6.jpg') }}'); background-size: cover;">
        <!-- Product -->
        <div class="container">
            <div class="row">
                <div class="col-md-12 m-b30">
                    <div class="card max-w500 radius-sm m-auto">
                        <div class="tab-content">
                            <form id="frmCompleteRegistration" class="tab-pane active">
                                <h4 class="font-weight-700 m-b5 text-center">COMPLETAR INFORMACIÓN</h4>
                                <p class="font-weight-600">Ingrese los datos faltantes para poder ofertar o postular
                                    proyectos.</p>
                                <div class="form-group">
                                    <label class="font-weight-700">Nombre de la empresa *</label>
                                    <input name="company_name" id="company_name" required class="form-control"
                                        type="text">
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-700">NIF/CIF *</label>
                                    <input name="nif" id="nif" required class="form-control" type="text">
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-700">Tipo de forma jurídica *</label>
                                    <select name="legal_form_id" id="legal_form_id" required class="">
                                        <option value="">Seleccione una opción</option>
                                        @foreach ($legal_forms as $legal_form)
                                            <option value="{{ $legal_form->id }} ">{{ $legal_form->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-700">Comunidad Autonoma *</label>
                                    <select name="autonomous_community" id="autonomous_community" required
                                        class="">
                                        <option value="">Seleccione una opción</option>
                                        @foreach ($autonomous_communities as $autonomous_community)
                                            <option value="{{ $autonomous_community->id }} ">
                                                {{ $autonomous_community->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-700">Provincia *</label>
                                    <select name="province_id" id="province_id" required class=""></select>
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-700">Domicilio Fiscal *</label>
                                    <textarea name="tax_residence" id="tax_residence" required class="form-control" cols="30" rows="10"></textarea>
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-700">Mi cargo (puesto en la empresa) *</label>
                                    <input name="position" id="position" class="form-control " type="text">
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-700">Descripción de la empresa *</label>
                                    <textarea name="company_description" id="company_description" required class="form-control" cols="30"
                                        rows="10"></textarea>
                                </div>
                                <div class="text-left">
                                    <button class="site-button button-lg outline outline-2 btn-block"
                                        type="submit">Guardar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Product END -->
    </div> --}}
@endsection
