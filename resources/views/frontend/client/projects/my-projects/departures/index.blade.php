@extends('layouts.frontend.app')
@section('content')
    <!-- Content -->
    <div class="page-content bg-white">
        <input type="hidden" name="project_id" id="project_id" value="{{ $project->id }}">
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
                            <div class="job-bx table-job-bx clearfix">
                                <div class="job-bx-title clearfix">
                                    <div class="">
                                        <a class="m-l15 font-16 text-white btn btn-primary btn-sm float-right"
                                            data-toggle="modal" data-target="#mdlNew" href="#"><i
                                                class="fa fa-plus"></i>&nbsp;Agregar</a>
                                        <div class="float-left">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <h5 class="font-weight-700 pull-left text-uppercase">Proyecto:
                                                        {{ $project->code }}
                                                    </h5>
                                                </div>
                                                <div class="col-md-12">
                                                    <p class="font-13">Fecha de culminación:
                                                        {{ \Carbon\Carbon::parse($project->final_date)->format('d/m/Y') }}
                                                    </p>
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <form action="{{ route('import.test') }}" method="post" enctype="multipart/form-data">
                                                        @csrf
                                                        <input type="hidden" name="project_id" value="{{ $project->id }}" />
                                                        <div class="col-md-6 form-group">
                                                            <div class="row">
                                                                <div class="col-8">
                                                                    <input type="file" name="excel"  class="form-control"/>
                                                                </div>
                                                                @if($plantilla->plantilla)
                                                                    <div class="col-4">
                                                                        <a download="Plantilla" href="{{ asset("storage/uploads/{$plantilla->plantilla}") }}" style="font-size: 12px;text-decoration:underline">Descargar plantilla</a>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 mt-2">
                                                            <button class="btn btn-primary" type="submit" name="Importar" value="Importar" >Importar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="col-md-12">
                                                    <p class="text-blue">
                                                        Lista de partidas
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <form id="frmSearchPartidas">
                                    <div class="input-group mb-3">
                                        <input type="text" name="search" id="search" class="form-control"
                                               placeholder="Buscar por código o descripción">
                                        <div class="input-group-append">
                                            <button class="btn site-button" type="submit" id="btnSearch"
                                                    title="Buscar"><i class="fa fa-search"></i></button>
                                            <button class="btn btn-outline-info" type="button" id="btnRefresh"
                                                    title="Refrescar"><i class="fa fa-refresh"></i></button>
                                            <input type="hidden" id="project_id" name="project_id"
                                                   value="{{ $project->id }}">
                                        </div>
                                    </div>
                                </form>
                                <form method="POST" action="{{ route('delete.partidas',$project->id) }}" id="formPartidas">
                                    @csrf
                                    <div class="d-flex justify-content-between align-items-center mb-5">
                                        <div>
                                            <button class="btn btn-danger my-3" type="submit" value="delete_partidas">Borrar Seleccionados</button>
                                        </div>
                                        <div>
                                            <select class="form-control" id="per-page" name="per_page"  style="height: 7vh !important">
                                                <option {{ old('per_page') == 10 ? 'selected' : '' }} value="10">10</option>
                                                <option {{ old('per_page') == 20 ? 'selected' : '' }} value="20">20</option>
                                                <option {{ old('per_page') == 30 ? 'selected' : '' }} value="30">30</option>
                                            </select>
                                        </div>
                                    </div>
                                    @include('frontend.client.projects.my-projects.partials.departures-data')
                                </form>
                                <div class="col-lg-12 col-md-12">
                                    <label>Precio por
                                        partida:</label>&nbsp;{{ $price_departure }}
                                    EUR
                                </div>
                                <div class="col-lg-12 col-md-12 mb-2">
                                    <label>Precio por
                                        variable:</label>&nbsp;{{ $price_variable }}
                                    EUR
                                </div>
                                <div style="width: 100%; overflow: auto;">
                                    <table id="tableDataPrices">
                                        <thead>
                                            <tr>
                                                <th>Nº de partidas</th>
                                                <th>Nº de variables</th>
                                                <th>Precio total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                // $num_departures = session()->get('numDepartures');
                                                // $num_variables = session()->get('numVariables');
                                            @endphp
                                            <td class="text-center">{{ $numDepartures }}
                                            </td>
                                            <td class="text-center">{{ $numVariables }}
                                            </td>
                                            <td class="text-center">
                                                {{ $total }}
                                            </td>
                                        </tbody>
                                    </table>
                                </div>
                                @if (($numDepartures !== null && $numDepartures > 0) || ($numVariables !== null && $numVariables > 0))
                                    @php
                                        session(['numDepartures' => $numDepartures, 'price_departure' => $price_departure]);
                                        session(['numVariables' => $numVariables, 'price_variable' => $price_variable]);
                                        session(['total' => $total]);
                                    @endphp
                                    @if ($total > 0)
                                        <div class="col-lg-12 col-md-12">
                                            <a href="{{ route('client.projects.my-projects.checkout') . '?project=' . $project->id }}"
                                                class="btn btn-primary">Procesar pago</a>
                                        </div>
                                    @else
                                        <div class="col-lg-12 col-md-12">
                                            <a href="{{ route('client.projects.my-projects.publish') . '?project=' . $project->id }}"
                                                class="btn btn-primary">Publicar</a>
                                        </div>
                                    @endif
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Browse Jobs END -->
        </div>
    </div>
    <!-- Content END-->

    <form id="frmNew" enctype="multipart/form-data">
        <!-- Modal -->
        <div class="modal fade browse-job modal-bx-info" id="mdlNew" tabindex="-1" role="dialog"
            aria-labelledby="ProfilenameModalLongTitle" aria-hidden="true" style="z-index: 9000">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ProfilenameModalLongTitle">Nueva partida</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="project_id" name="project_id" value="{{ $project->id }}">

                        @include('frontend.client.projects.my-projects.departures.form')
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
            aria-labelledby="ProfilenameModalLongTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ProfilenameModalLongTitle">Editar partida</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="id" name="id">
                        <input type="hidden" id="modality" name="modality">
                        @include('frontend.client.projects.my-projects.departures.form')
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

    <form id="frmVariableNew">
        <input type="hidden" name="type_send" id="type_send">
        <!-- Modal -->
        <div class="modal fade modal-bx-info" id="mdlVariableNew" tabindex="-1" role="dialog"
            aria-labelledby="ProfilenameModalLongTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ProfilenameModalLongTitle">Nueva variable</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="project_id" name="project_id" value="{{ $project->id }}">
                        <input type="hidden" id="modality" name="modality">
                        @include('frontend.client.projects.my-projects.departures.variables.form')
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal End -->
    </form>

    <form id="frmVariableEdit">
        <!-- Modal -->
        <div class="modal fade modal-bx-info" id="mdlVariableEdit" tabindex="-1" role="dialog"
            aria-labelledby="ProfilenameModalLongTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ProfilenameModalLongTitle">Editar variable</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="project_id" name="project_id" value="{{ $project->id }}">
                        <input type="hidden" name="index" id="index">
                        <input type="hidden" name="variable_id", id="variable_id">
                        @include('frontend.client.projects.my-projects.departures.variables.form')
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal End -->
    </form>

    <div class="modal fade browse-job modal-bx-info" id="mdlViewDetail" tabindex="-1" role="dialog"
        aria-labelledby="ProfilenameModalLongTitle" aria-hidden="true" style="z-index: 9000">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ProfilenameModalLongTitle">Detalles de la partida #<span
                            id="code"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="project_id" name="project_id" value="{{ $project->id }}">
                    @include('frontend.client.projects.my-projects.departures.detail')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
@endsection
