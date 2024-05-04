@extends('layouts.app')
@section('content')
<div class="container" >
    <div class="row">
        <div class="col-md-12">
            <h1>Subir Plantilla</h1>
        </div>
    </div>
    @if($plantilla->plantilla)
        <div class="row" >
            <div class="col-md-6">
                <div class="col-4">
                    <a download="Plantilla" href="{{ asset("storage/uploads/{$plantilla->plantilla}") }}" style="font-size: 12px;text-decoration:underline">Descargar plantilla</a>
                </div>
            </div>
        </div>
    @endif
        <form action="{{ route('dashboard.settings.store.plantilla') }}" method="POST" enctype="multipart/form-data" >
            @csrf
            <div class="row" >
                <div class="col-md-9">
                    <div class="form-group" >
                        <input class="form-control" type="file" name="plantilla" id="plantilla" required >
                    </div>
                </div>
                <div class="col-md-3">
                    <input class="btn btn-primary form-control" type="submit" value="Subir" name="subir">
                </div>
            </div>
        </form>
</div>
@endsection