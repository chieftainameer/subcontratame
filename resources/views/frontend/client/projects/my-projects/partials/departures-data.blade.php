@if (!empty($project))
    @php
        if (request()->get('search')) {
            $departures = $project
                ->departures()
                ->where(function ($q) {
                    $q->where('description', 'like', '%' . request()->get('search') . '%')->orWhere('code', 'like', '%' . request()->get('search') . '%');
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10)
                ->withQueryString();
        }
    @endphp
    @endif
<div style="width: 100%; overflow: auto;">
    <table id="tableDeparturesData">
        <thead>
            <tr>
                <th></th>
                <th width="1">Código</th>
                <th>Fecha estimada de entrega</th>
                <th>Descripción</th>
                <th>Estatus</th>
                <th width="1">Completado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @if (!empty($departures) && $departures->count())
                @foreach ($departures as $key => $departure)
                    <tr
                        style="{{ !$departure->complete ? 'background-color: white; color: black' : 'background-color: #E8F7E6; color: #878585' }}">
                        <td>
                            <input type="checkbox" name="partidas[]" value="{{ $departure->id }}" />
                        </td>
                        <td class=""><b>{{ $departure->code }}</b></td>
                        <td class="date">{{ \Carbon\Carbon::parse($departure->execution_date)->format('d/m/Y') }}</td>
                        <td class="criterias">{{ $departure->description }}</td>
                        <td>{{ $departure->status === '1' ? 'Pendiente' : 'Pagado' }}</td>
                        <td>
                            <center>
                                @if (!$departure->complete && $departure->status !== '1')
                                    <span class="custom-control custom-checkbox">
                                        <input type="checkbox" class="form-check-input checkComplete"
                                            data-id="{{ $departure->id }}">
                                    </span>
                                @else
                                    <span>
                                        -
                                    </span>
                                @endif
                            </center>
                        </td>
                        <td class="">
                            <center>
                                @if (!$departure->complete)
                                    <div class="d-flex">
                                        <div class="dropdown" style="width: 50%">
                                            <i class="fa fa-bars text-primary" aria-hidden="true" data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false" style="cursor: pointer"></i>
                                            <div class="dropdown-menu p-0" aria-labelledby="dropdownMenu2"
                                                style="width: 100%">
                                                <a class="dropdown-item text-info"
                                                    onclick="showDetail({{ $departure->id }})"
                                                    style="cursor: pointer"><i class="fa fa-eye"></i>&nbsp;Ver</a>
                                                <a class="dropdown-item text-warning"
                                                    onclick="editDeparture({{ $departure->id }})"
                                                    style="cursor: pointer"><i class="fa fa-edit"></i>&nbsp;Editar</a>
                                                @if ($departure->status === '1')
                                                    <a class="dropdown-item text-danger"
                                                        onclick="deleteDeparture({{ $departure->id }})"
                                                        style="cursor: pointer"><i
                                                            class="fa fa-trash"></i>&nbsp;Eliminar</a>
                                                @endif
                                            </div>
                                        </div>
                                        <div style="width: 50%">
                                            @if ($departure->visible)
                                                <i class="fa fa-eye text-primary" aria-hidden="true"></i>
                                            @else
                                                <i class="fa fa-eye-slash text-primary" aria-hidden="true"></i>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <span class="badge badge-success p-1">Completada</span>
                                @endif
                            </center>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td class="job-name text-center" colspan="4">No hay datos</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
<div class="row mt-lg-5 mt-5">
    <div class="col-lg-12 col-md-12 mt-2 text-center">
        {!! $departures->onEachSide(2)->links() !!}
    </div>
</div>
