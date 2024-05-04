<div style="overflow: auto" style="width: 100%">
    <table class="table table-bordered table-job-bx" style="width: 100%">
        <thead>
            <tr>
                <th></th>
                <th>Proyecto</th>
                <th>Ubicación</th>
                <th>Fecha Vencimiento</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @if (count($favorite_projects))
                @foreach ($favorite_projects as $fp)
                    <tr>
                        <td><img class="table-image"
                                src="{{ $fp->image === null ? asset('images/default.png') : asset('storage') . '/' . $fp->image }}"
                                alt="{{ $fp->title }}"> </td>
                        <td><b>{{ $fp->code . ' - ' . $fp->title }}</b>
                        </td>
                        <td>{{ $fp->location }} </td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($fp->expiration_date)->format('d/m/Y') }} </td>
                        <td>
                            <center>
                                <a href="{{ route('client.projects') . '?project=' . $fp->project_id }} "
                                    class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></a>
                                <a href="javascript:void(0)" class="btn btn-danger btn-sm"><i class="fa fa-trash"
                                        onclick="deleteFavoriteProject('{{ $fp->id }}')"></i></a>
                            </center>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" class="text-center">No hay datos</td>
                </tr>
            @endif

        </tbody>
    </table>
</div>
<div class="row mt-lg-5 mt-5">
    <div class="col-lg-12 col-md-12 mt-2 text-center">
        {!! $favorite_projects->onEachSide(2)->links() !!}
    </div>
</div>
