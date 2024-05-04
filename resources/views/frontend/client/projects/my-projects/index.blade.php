@extends('layouts.frontend.app')
@section('content')
    <!-- Content -->
    <div class="page-content bg-white">
        <!-- contact area -->
        <div class="content-block">
            <!-- Browse Jobs -->
            <div class="section-full bg-white p-t50 p-b20">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-3 col-lg-4 m-b30">
                            @include('frontend.client.projects.my-projects.partials.candidate-profile')
                        </div>
                        <div class="col-xl-9 col-lg-8 m-b30">
                            <div class="job-bx clearfix">
                                <div class="job-bx-title clearfix">
                                    <h5 class="font-weight-700 pull-left text-uppercase">MIS PROYECTOS</h5>
                                    <div class="float-right">
                                        @if (auth()->user()->status === 2)
                                            <span class="text-red font-18 p-2">Para agregar proyectos, debe completar sus
                                                datos
                                                en <a href="{{ route('client.profile') }}" style="text-decoration: none"
                                                    class="badge badge-info">MI
                                                    PERFIL</a></span>
                                        @else
                                            <a class="m-l15 font-16 text-white btn btn-primary btn-sm p-2"
                                                data-toggle="modal" data-target="#mdlNew" href="#">Agregar
                                                proyecto</a>
                                        @endif
                                    </div>
                                </div>
                                @if (session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('success') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    @php
                                        session()->forget('numDepartures');
                                        session()->forget('numVariables');
                                        session()->forget('price_departure');
                                        session()->forget('price_variable');
                                        session()->forget('total');
                                    @endphp
                                @endif
                                @if (session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        {{ session('error') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    @php
                                        session()->forget('numDepartures');
                                        session()->forget('numVariables');
                                        session()->forget('price_departure');
                                        session()->forget('price_variable');
                                        session()->forget('total');
                                    @endphp
                                @endif
                                @include('frontend.client.projects.my-projects.partials.projects-data')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Browse Jobs END -->
        </div>
    </div>
    <!-- Content END-->

    <form id="frmNew">
        <!-- Modal -->
        <div class="modal fade browse-job modal-bx-info editor" id="mdlNew" tabindex="-1" role="dialog"
            aria-labelledby="ProfilenameModalLongTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ProfilenameModalLongTitle">Nuevo proyecto</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @include('frontend.client.projects.my-projects.form')
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal End -->
    </form>

    <form id="frmEdit" enctype="multipart/form-data">
        <!-- Modal -->
        <div class="modal fade browse-job modal-bx-info editor" id="mdlEdit" tabindex="-1" role="dialog"
            aria-labelledby="ProfilenameModalLongTitle2" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ProfilenameModalLongTitle2">Editar proyecto</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="id" name="id">
                        @include('frontend.client.projects.my-projects.form')
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal End -->
    </form>
@endsection
