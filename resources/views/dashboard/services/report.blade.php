@extends('layouts.app')
@section('content')
    <form id="frmEdit">
        <input type="hidden" id="id" value="{{ $service->id }}">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Reporte</h6>
                <button type="submit" class="btn btn-success" type="button">
                    Guardar / Editar
                </button>
            </div>
            <div class="card-body">
                <div class="card">
                    <form action="" id="frmEdit">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="">CIE10</label>
                                    <input type="text" class="form-control" name="cie10" id="cie10"
                                        value="{{ $report->cie10 != null ? $report->cie10 : '' }}">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="">Presión sanguinea</label>
                                    <input type="text" class="form-control" name="blood_pressure" id="blood_pressure"
                                        value="{{ $report->blood_pressure != null ? $report->blood_pressure : '' }}">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="">Ritmo cardiaco</label>
                                    <input type="text" class="form-control" name="heart_rate" id="heart_rate"
                                        value="{{ $report->heart_rate != null ? $report->heart_rate : '' }}">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="">Frecuencia respiratoria</label>
                                    <input type="text" class="form-control" name="breathings_frequency"
                                        id="breathings_frequency"
                                        value="{{ $report->breathings_frequency != null ? $report->breathings_frequency : '' }}">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="">Temperatura</label>
                                    <input type="text" class="form-control" name="temperature" id="temperature"
                                        value="{{ $report->temperature != null ? $report->temperature : '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="card-body">Tratamiento
                            <textarea id="treatment" name="treatment" cols="30" rows="10" class="form-control">{{ $report->treatment != null ? $report->treatment : '' }}</textarea>
                        </div>
                        <div class="card-body">Descriptión
                            <textarea id="description" name="description" cols="30" rows="10" class="form-control">{{ $report != null ? $report->description : '' }}</textarea>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </form>
@endsection
