@extends('layouts.frontend.app')
@section('content')
    @if ($project)
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
                                        <h5 class="font-weight-700">Proyecto:
                                            {{ $project->code . ' ' . $project->title }} </h5><br>
                                        <small class="text-muted">Califica a los postulantes de las partidas de tu
                                            proyecto</small>
                                    </div>

                                    <div class="accordion table-job-bx" id="accordionExample">
                                        <a href="{{ route('client.projects.my-projects.applications.export') . '?project=' . $project->id }}"
                                            class="float-right btn btn-success btn-sm mb-3"><i
                                                class="fa fa-file-excel-o"></i>&nbsp;Exportar a excel</a>
                                        <div class="clearfix"></div>

                                        <div class="card">
                                            <form method="post" action="{{ route('applications.filter') }}">
                                            @csrf
                                                <input type="hidden" name="project_id" value="{{ $project->id }}" />
                                            @include('frontend.client.projects.my-projects.applications.filters')
                                            @foreach ($project->departures as $departure)
                                                    <div class="card-header" id="{{ 'heading' . $departure->id }}">
                                                        <div class="row d-flex align-items-center">
                                                            <div class="col-1">
                                                                <div class="form-group form-check">
                                                                    <input type="checkbox" name="departures[]" value="{{ $departure->id }}" class="form-check-input" id="partida-{{ $departure->id }}" checked>
                                                                </div>
                                                            </div>
                                                            <div class="col-11">
                                                                <h2 class="mb-0 d-flex">
                                                                    <button class="btn btn-block text-left float-left"
                                                                            type="button" data-toggle="collapse"
                                                                            data-target="{{ '#collapse' . $departure->id }}"
                                                                            aria-expanded="false"
                                                                            aria-controls="{{ 'collapse' . $departure->id }}">
                                                                        <b>{{ $departure->code }} -
                                                                            {{ $departure->description }}</b><br>
                                                                    </button>
                                                                </h2>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="{{ 'collapse' . $departure->id }}" class="collapse"
                                                        aria-labelledby="{{ 'heading' . $departure->id }}"
                                                        data-parent="#accordionExample">
                                                        <div class="card-body" style="width: 100%; overflow: auto">
                                                            @if ($departure->variants->count())
                                                                <table class="table-bordered">
                                                                    <thead>
                                                                        <th>Usuario</th>
                                                                        <th>Tipo</th>
                                                                        <th>Incluye</th>
                                                                        <th>Descripción</th>
                                                                        <th>Cantidad</th>
                                                                        <th>Dimension</th>
                                                                        <th>Precio unitario</th>
                                                                        <th>Incluye IVA</th>
                                                                        <th>Precio Total</th>
                                                                        <th>Métodos de pago</th>
                                                                        <th>Variables Suplementarias</th>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($departure->variants as $variant)
                                                                            @php
                                                                                $user = $variant->user()->first();
                                                                                $type = $variant->type === '1' ? 'Original' : 'Alternativo';
                                                                                $description = $variant->description !== null ? $variant->description : '';
                                                                                $include = ($variant->includes === '1' ? 'Suministro' : $variant->includes === '2') ? 'Instalación' : 'Suministro + Instalación';
                                                                                $iva = $variant->iva === 1 ? 'Sí' : 'No';
                                                                                $average_rating = 0;
                                                                                if (
                                                                                    $user
                                                                                        ->ratings()
                                                                                        ->get()
                                                                                        ->count() > 0
                                                                                ) {
                                                                                    $average_rating =
                                                                                        $user
                                                                                            ->ratings()
                                                                                            ->get()
                                                                                            ->sum('rating') /
                                                                                        $user
                                                                                            ->ratings()
                                                                                            ->get()
                                                                                            ->count();
                                                                                }
                                                                                
                                                                            @endphp
                                                                            <tr>
                                                                                <td>
                                                                                    {{ $user->first_name . ' ' . $user->last_name }}
                                                                                    <br>
                                                                                    <div class="ratings"
                                                                                        data-rating="{{ $average_rating }}"
                                                                                        style="font-size: 10px; text-align:center;">
                                                                                    </div>
                                                                                    <center>
                                                                                        <a href="javascript:void(0)"
                                                                                            class="btn btn-outline-success btn-sm"
                                                                                            style="font-size: 9px;"
                                                                                            onclick="qualify({{ $variant->id }}, {{ $user->id }})">Calificar</a>
                                                                                    </center>
                                                                                </td>
                                                                                <td>{{ $type }}</td>
                                                                                <td>{{ $include }} </td>
                                                                                <td>{{ $description }} </td>
                                                                                <td class="text-right">
                                                                                    {{ number_format($variant->quantity, 2, ',', '.') }}
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    {{ $departure->dimensions }}</td>
                                                                                <td class="text-right">
                                                                                    {{ number_format($variant->price_unit, 2, ',', '.') }}
                                                                                </td>
                                                                                <td class="text-center">{{ $iva }}
                                                                                </td>
                                                                                <td class="text-right">
                                                                                    {{ number_format($variant->price_total, 2, ',', '.') }}
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <ul>
                                                                                        @foreach ($variant->payment_methods()->get() as $payment_method)
                                                                                            <li>{{ $payment_method->name }}
                                                                                            </li>
                                                                                        @endforeach
                                                                                    </ul>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <a href="javascript:void(0)"
                                                                                        onclick="showVariables({{ $variant->id }})">Ver</a>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            @else
                                                                <p class="text-center">Sin datos</p>
                                                            @endif


                                                        </div>
                                                        {{-- iba aqui lo de arriba --}}
                                                    </div>
                                            @endforeach
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Browse Jobs END -->
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade browse-job modal-bx-info editor" id="mdlShowVariables" tabindex="-1" role="dialog"
            aria-labelledby="ProfilenameModalLongTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ProfilenameModalLongTitle">Variables Suplementarias</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row" id="view_variables"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal End -->

        <form id="frmQualify">
            <!-- Modal -->
            <div class="modal fade browse-job modal-bx-info editor" id="mdlQualify" tabindex="-1" role="dialog"
                aria-labelledby="ProfilenameModalLongTitle" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ProfilenameModalLongTitle">Calificar</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="project_id" name="project_id" value="{{ $project->id }}">
                            <input type="hidden" id="variant_id" name="variant_id">
                            <input type="hidden" id="user_id" name="user_id">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 text-center">
                                    <h5 class="text-center">¡Ponle una calificación al usuario!</h5>
                                    <div class="star" style="font-size: 20px"></div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary btn-sm" disabled>Enviar</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal End -->
        </form>
    @else
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
                                        <h5 class="font-weight-700">Proyecto:
                                            Sin datos </h5><br>
                                        <small class="text-muted">Sin datos</small>
                                    </div>

                                    <div class="accordion table-job-bx" id="accordionExample">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-12 col-12 text-center">
                                                        <h5>Sin datos</h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Browse Jobs END -->
            </div>
        </div>
    @endif

@endsection
