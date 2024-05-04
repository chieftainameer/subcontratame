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
                            <div class="row">
                                <div class="col-lg-6 col-md-6">
                                    <input type="text" class="form-control" name="word" id="word"
                                        placeholder="Ingrese una palabra para la busqueda" />
                                </div>
                                <div class="col-lg-3 col-md-3">
                                    <select name="field" id="field" class="form-control" style="margin-top: 13px;">
                                        <option value="title">Título del proyecto</option>
                                        <option value="user">Usuario</option>
                                        <option value="company">Empresa</option>
                                        <option value="description_project">Descripción del proyecto</option>
                                        <option value="description_departure">Descripción de partida</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-3">
                                    <button class="btn btn-success btn-sm" style="margin-top: 15px;"><i
                                            class="fa fa-search"></i></button>
                                </div>

                            </div>

                            {{-- <div class="row">
                                <div class="col-lg-10 col-md-10">
                                    <label for="">Buscar por:</label>
                                    <select class="form-control" name="search_for" id="search_for">
                                        <option value="">Seleccione</option>
                                        <option value="title">Título del proyecto</option>
                                        <option value="user">Usuario</option>
                                        <option value="company">Empresa</option>
                                        <option value="description_project">Descripción del proyecto</option>
                                        <option value="description_departure">Descripción de partida</option>
                                        <option value="execution_date">Fecha de ejecución del proyecto</option>
                                        <option value="location">Ubicación</option>
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-2">
                                    <button class="btn btn-outline-success btn-sm btn-block" style="margin-top: 20px;"><i
                                            class="fa fa-undo"></i>
                                        Limpiar</button>
                                </div>
                            </div> --}}
                            <div class="row d-none" id="box_word_search">
                                <div class="col-lg-8 col-md-8">
                                    <input class="form-control" type="text" name="text_word_search" id="text_word_search"
                                        placeholder="Ingrese una palabra">
                                </div>
                                <div class="col-lg-2 col-md-2" style="margin-top: 13px;">
                                    <select class="form-control" name="type_word_search" id="type_word_search">
                                        <option value="OR">O</option>
                                        <option value="AND">Y</option>
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-2" style="margin-top: 13px;">
                                    <button class="btn btn-outline-success btn-sm" id="btnWordSearch">Agregar</button>
                                </div>
                            </div>
                            <div class="row d-none" id="box_date_search">
                                <div class="col-lg-8 col-md-8">
                                    <input class="form-control text-center" type="text" name="execution_date_search"
                                        id="execution_date_search" />
                                </div>
                                <div class="col-lg-2 col-md-2" style="margin-top: 13px;">
                                    <select class="form-control" name="type_date_search" id="type_date_search">
                                        <option value="OR">O</option>
                                        <option value="AND">Y</option>
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-2" style="margin-top: 13px;">
                                    <button class="btn btn-outline-success btn-sm" id="btnDateSearch">Agregar</button>
                                </div>
                            </div>
                            <div class="row d-none" id="box_payment_search">
                                <div class="col-lg-8 col-md-8">
                                    <select class="form-control" name="payment_search" id="payment_search"
                                        style="margin-top: 13px">
                                        <option value="">Seleccione</option>
                                        @foreach ($payment_methods as $payment)
                                            <option value="{{ $payment->id }}">{{ $payment->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-2" style="margin-top: 13px;">
                                    <select class="form-control" name="type_payment_search" id="type_payment_search">
                                        <option value="OR">O</option>
                                        <option value="AND">Y</option>
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-2" style="margin-top: 13px;">
                                    <button class="btn btn-outline-success btn-sm" id="btnPaymentSearch">Agregar</button>
                                </div>
                            </div>
                            <div class="row d-none" id="box_location_search">
                                <div class="col-lg-4 col-md-4">
                                    <div class="form-group">
                                        <select class="form-control" id="autonomous_community_search"
                                            name="autonomous_community_search" class="mt-2" style="height: 50px">
                                            <option value="">Selecciona comunidad</option>
                                            @foreach ($autonomous_communities as $community)
                                                <option value="{{ $community->id }}">{{ $community->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4">
                                    <div class="form-group">
                                        <select class="form-control" id="province_id_search" name="province_id_search"
                                            class="mt-2" style="height: 50px">
                                            <option value="">Selecciona provincia</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2" style="margin-top: 13px;">
                                    <select class="form-control" name="type_location_search" id="type_location_search">
                                        <option value="OR">O</option>
                                        <option value="AND">Y</option>
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-2" style="margin-top: 13px;">
                                    <button class="btn btn-outline-success btn-sm" id="btnLocationSearch">Agregar</button>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-lg-10 col-md-10">
                                    <label for="">Palabras para la busquedad</label>
                                    <div id="arrWordsContainer">
                                    </div>
                                </div>
                                {{-- <div class="col-lg-6 col-md-6">
                                    <label for="">Palabras (O)</label>
                                </div> --}}
                            </div>
                            <div class="row">
                                <div class="col-lg-2 col-md-6 offset-md-10">
                                    <button type="button" id="btnSearch" class="site-button button-sm btn-block"><i
                                            class="fa fa-search"></i>&nbsp;Buscar</button>
                                </div>
                            </div>
                            {{-- <div class="row mt-3">
                                <div class="col-lg-4 col-md-6">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="text" name="word" id="word" class="form-control"
                                                placeholder="Ingrese una palabra">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="form-group">
                                        <select class="form-control" id="autonomous_community"
                                            name="autonomous_community" class="mt-2" style="height: 50px">
                                            <option value="">Selecciona comunidad</option>
                                            @foreach ($autonomous_communities as $community)
                                                <option value="{{ $community->id }}">{{ $community->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="form-group">
                                        <select class="form-control" id="province_id" name="province_id" class="mt-2"
                                            style="height: 50px">
                                            <option value="">Selecciona provincia</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6">
                                    <button type="button" id="btnSearch" class="site-button btn-block"><i
                                            class="fa fa-search"></i>&nbsp;Buscar</button>
                                </div>
                            </div> --}}
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
                    {{-- <div class="col-lg-3">
                        <div class="sticky-top">
                            <div class="candidates-are-sys m-b30">
                                <div class="candidates-bx">
                                    <div class="testimonial-pic radius"><img src="{{ asset('images/anonymous.png') }}"
                                            alt="" width="100" height="100"></div>
                                    <div class="testimonial-text">
                                        <p>I just got a job that I applied for via careerfy! I used the site all the
                                            time during my job hunt.</p>
                                    </div>
                                    <div class="testimonial-detail"> <strong class="testimonial-name">Richard
                                            Anderson</strong> <span class="testimonial-position">Nevada, USA</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    <div class="col-xl-3 col-lg-4 col-md-5 m-b30">
                        <form method="GET" id="frmSearchAvance">
                            <aside id="accordion1" class="sticky-top sidebar-filter">
                                <h6 class="title"><i class="fa fa-sliders m-r5"></i> Filtro Avanzado <a
                                        href="javascript:void(0);" class="font-12 float-right"
                                        onclick="reset()">Resetear</a></h6>
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
                                {{-- <button class="site-button button-sm mt-2 float-right">Buscar</button> --}}
                            </aside>
                        </form>
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
