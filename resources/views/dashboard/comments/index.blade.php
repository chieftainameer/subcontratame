@extends('layouts.app')
@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h2 class="m-0 font-weight-bold text-primary">Lista de comentarios reportados</h2>
        </div>
        <div class="card-body">
            <div style="overflow: auto">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="1">{{ __('Nº') }}</th>
                            <th width="1">Proyecto</th>
                            <th width="1">Partida</th>
                            <th>Comentario</th>
                            <th width="1">Dueño</th>
                            <th width="1">Emitido por</th>
                            <th width="80">Acciones</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Logout Modal-->
@endsection
