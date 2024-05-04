@extends('layouts.frontend.app')
@section('content')
    <div class="page-content">
        <!-- Section Banner -->
        <div class="dez-bnr-inr dez-bnr-inr-md main-slider"
            style="background-image:url('{{ asset('frontend/images/main-slider/slide2.jpg') }}');" style="height: 200px">
            <div class="container">
                <div class="dez-bnr-inr-entry align-m">
                    <div class="find-job-bx" style="margin-top: 350px;">
                        <h2>Busca entre más de <br /> <span class="text-primary">50,000</span> Proyectos Aplicables.
                        </h2>
                        <form method="GET" id="frmSearch">
                            <div class="row" id="simpleSearch">
                                <div class="col-lg-10 col-md-6">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="text" name="word" id="word" class="form-control"
                                                placeholder="Ingrese una palabra">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-2 col-md-6">
                                    <button type="button" id="btnSearch" class="site-button"><i
                                            class="fa fa-search"></i></button>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <button id="btnViewSearchAdvance" data-type="advance" class="btn btn-info btn-sm mb-3"
                                        type="button">Ver búsqueda
                                        avanzada</button>
                                </div>
                            </div>
                            <div class="row d-none mt-2" id="advanceSearch">
                                <div class="col-lg-8 col-md-8">
                                    <div class="row mb-5" id="container_inputs">
                                        <div class="col-lg-3 col-md-3">
                                            <select class="form-control d-none" name="types[]" style="margin-top: 13px;">
                                                <option value=""></option>
                                            </select>
                                        </div>
                                        <div class="col-lg-4 col-md-4">
                                            <select class="form-control" name="fields[]" style="margin-top: 13px;">
                                                <option value="title">Título de proyecto</option>
                                                <option value="user">Usuario</option>
                                                <option value="company">Empresa</option>
                                                <option value="des_project">Descripción de proyecto</option>
                                                <option value="des_departure">Descripción de partida</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-5 col-md-5">
                                            <input class="form-control" type="text" name="terms[]" value=""
                                                placeholder="Ingrese el término para buscar">
                                        </div>
                                        <div class="col-lg-3 col-md-3">
                                            <select class="form-control" name="types[]" style="margin-top: 13px;">
                                                <option value="AND">Y</option>
                                                <option value="OR">O</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-4 col-md-4">
                                            <select class="form-control" name="fields[]" id="field"
                                                style="margin-top: 13px;">
                                                <option value="title">Título de proyecto</option>
                                                <option value="user">Usuario</option>
                                                <option value="company">Empresa</option>
                                                <option value="des_project">Descripción de proyecto</option>
                                                <option value="des_departure">Descripción de partida</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-5 col-md-5">
                                            <input class="form-control" type="text" name="terms[]" value=""
                                                placeholder="Ingrese el término para buscar">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-lg-12 col-md-12 ">
                                            <a href="javascript:void(0)"
                                                class="btn btn-danger btn-sm float-right resetFields"><i
                                                    class="fa fa-undo"></i>&nbsp;Eliminar</a>
                                            <a href="javascript:void(0)"
                                                class="btn btn-outline-warning btn-sm addField float-right mr-2"><i
                                                    class="fa fa-plus"></i>&nbsp;Agregar
                                                una nueva línea</a>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 ml-0">
                                    <div class="row float-left">
                                        <div class="col-lg-12 col-md-12 ml-0">
                                            <a href="javascript:void(0)" class="site-button float-left"
                                                id="btnSearchAdvance"><i class="fa fa-search"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Call To Action END -->
        <!-- Our Job -->
        <div class="section-full bg-white content-inner-2">
            <div class="container">
                <div class="d-flex job-title-bx section-head">
                    <div class="mr-auto">
                        <h2 class="m-b5">Proyectos Recientes</h2>
                        <h6 class="fw4 m-b0">20+ Proyectos agregados recientemente</h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-3 col-lg-4 col-md-5 m-b30">
                        <form method="GET" id="frmSearchAdvance">
                            <aside id="accordion1" class="sticky-top sidebar-filter">
                                <h6 class="title"><i class="fa fa-sliders m-r5"></i> Filtro Avanzado <a
                                        href="javascript:void(0);" class="font-12 float-right"
                                        onclick="reset()">Resetear</a>
                                </h6>
                                <div class="panel">
                                    <div class="acod-head">
                                        <h6 class="acod-title">
                                            <a data-toggle="collapse" href="#categories" class="collapsed">
                                                Categorías
                                            </a>
                                        </h6>
                                    </div>
                                    <div id="categories" class="acod-body collapse">
                                        <div class="acod-content">
                                            @foreach ($categories as $category)
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input"
                                                        id="{{ 'category_' . $category->id }}" type="checkbox"
                                                        name="checkbox-categories" value="{{ $category->id }}">
                                                    <label class="custom-control-label"
                                                        for="{{ 'category_' . $category->id }}">{{ $category->name }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="panel">
                                    <div class="acod-head">
                                        <h6 class="acod-title">
                                            <a data-toggle="collapse" href="#payment_methods" class="collapsed">
                                                Métodos de pago
                                            </a>
                                        </h6>
                                    </div>
                                    <div id="payment_methods" class="acod-body collapse">
                                        <div class="acod-content">
                                            @foreach ($payment_methods as $payment)
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input"
                                                        id="{{ 'payment_method_' . $payment->id }}" type="checkbox"
                                                        name="checkbox-payments" value="{{ $payment->id }}">
                                                    <label class="custom-control-label"
                                                        for="{{ 'payment_method_' . $payment->id }}">{{ $payment->name }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="panel">
                                    <div class="acod-head">
                                        <h6 class="acod-title">
                                            <a data-toggle="collapse" href="#location" class="collapsed">
                                                Ubicación
                                            </a>
                                        </h6>
                                    </div>
                                    <div id="location" class="acod-body collapse">
                                        <div class="acod-content">
                                            @foreach ($paises as $pais )                  
                                                <div class="panel">
                                                    <div class="acod-head">
                                                        <h6 class="acod-title">
                                                            <a data-toggle="collapse"
                                                                href="{{ '#pais' . $pais->id }}"
                                                                class="collapsed">
                                                                {{ $pais->name_es }}
                                                            </a>
                                                        </h6>
                                                    </div>
                                                    <div id="{{ 'pais' . $pais->id }}"
                                                        class="acod-body collapse">
                                                        <div class="acod-content">
                                                            
                                                            @foreach ($pais->communities as $community)
                                                                <div class="panel">
                                                                    <div class="acod-head">
                                                                        <h6 class="acod-title">
                                                                            <a data-toggle="collapse"
                                                                                href="{{ '#community' . $community->id }}"
                                                                                class="collapsed">
                                                                                {{ $community->name }}
                                                                            </a>
                                                                        </h6>
                                                                    </div>
                                                                    <div id="{{ 'community' . $community->id }}"
                                                                        class="acod-body collapse">
                                                                        <div class="acod-content">
                                                                            @foreach ($community->provinces()->get() as $province)
                                                                                <span
                                                                                    class="custom-control custom-checkbox custom-control-inline">
                                                                                    <input type="checkbox" class="form-check-input"
                                                                                        id="province_{{ $province->id }}"
                                                                                        name="checkbox-provinces"
                                                                                        value="{{ $province->id }}">
                                                                                    <label class="form-check-label"
                                                                                        for="province_{{ $province->id }}">{{ $province->name }}</label>
                                                                                </span><br>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach

                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach

                                        </div>
                                    </div>
                                </div>

                            </aside>
                        </form>
                        <button class="site-button button-sm mt-2 float-right" type="button"
                            id="btnFilterAdvance">Buscar</button>
                    </div>
                    <div class="col-lg-9">
                        @include('frontend.client.projects.partials.projects-data')
                    </div>

                </div>
            </div>
        </div>
        <!-- Our Latest Blog -->
    </div>
@endsection
