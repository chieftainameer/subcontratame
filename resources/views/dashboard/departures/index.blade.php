@extends('layouts.app')
@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h2 class="m-0 font-weight-bold text-primary">Lista de partidas</h2>
            <button id="borrarSeleccionados" class="btn btn-info float-right" style="margin-top: -25px;" >Borrar Seleccionados</button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="80"></th>
                            <th width="1">{{ __('Nº') }}</th>
                            <th width="1">Proyecto</th>
                            <th width="1">Código</th>
                            <th>Descripción</th>
                            <th width="1">Estatus</th>
                            <th width="80">Acciones</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Logout Modal-->
    <!-- Editar -->
    <form id="frmEdit" enctype="multipart/form-data">
        <div class="modal fade" id="mdlEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Editar</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id">
                        @include('dashboard.departures.form')
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i></button>
                    </div>
                </div>
            </div>
        </div>
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
                        {{-- <input type="hidden" id="project_id" name="project_id" value="{{ $project->id }}"> --}}
                        <input type="hidden" id="modality" name="modality">
                        @include('dashboard.departures.variables.form')
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
                        {{-- <input type="hidden" id="project_id" name="project_id" value="{{ $project->id }}"> --}}
                        <input type="hidden" name="index" id="index">
                        @include('dashboard.departures.variables.form')
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
                    {{-- <input type="hidden" id="project_id" name="project_id" value="{{ $project->id }}"> --}}
                    @include('dashboard.departures.detail')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
@endsection
<script>
    let deleteRoute = @json(route('departures.deleteDepartures'));
    let csrfToken = @json(csrf_token() );
</script>