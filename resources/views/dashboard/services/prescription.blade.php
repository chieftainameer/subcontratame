@extends('layouts.app')
@section('content')
<form id="frmEdit">
    <input type="hidden" id="id" value="{{ $service->id }}">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Prescripción / Receta Médica</h6>
            <button type="submit" class="btn btn-success" type="button">
                Guardar / Editar
            </button>
        </div>
        <div class="card-body">
            <div class="card">
                <form action="" id="frmEdit">
                    <div class="card-header">
                        <div class="row w-100">
                            <div class="col-sm-6">
                                Aviso de privacidad
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="">CIE10</label>
                                    <input type="text"
                                      class="form-control" name="cie10" id="cie10" value="{{ $prescription!=null?$prescription->cie10:'' }}">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="">Tipo de Prescripción</label>
                                    <select name="type" class="form-control">
                                          <option value="1" {{ $prescription!=null?($prescription->type == 1?'selected':''):'' }}>Tratamiento</option>
                                          <option value="2" {{ $prescription!=null?($prescription->type == 2?'selected':''):'' }}>Receta médica</option>
                                      </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <textarea id="descripcion" name="prescription" cols="30" rows="10" class="form-control">{{ $prescription!=null?$prescription->prescription :'' }}</textarea>
                    </div>
                </form>
            </div>
        </div>
    </div>
</form>
@endsection