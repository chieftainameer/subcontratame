@extends('layouts.frontend.app')
@section('content')
    @if ($project)
        <!-- Content -->
        <div class="page-content bg-white">
            <!-- contact area -->
            <div class="content-block">
                <!-- Job Detail -->
                <div class="section-full content-inner-1">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="sticky-top">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-6">
                                            <div class="m-b30">
                                                <img src="{{ asset('storage') . '/' . $project->image }}" alt="">
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-6">
                                            <div class="widget bg-white p-lr20 p-t20  widget_getintuch radius-sm">
                                                <h4 class="text-black font-weight-700 p-t10 m-b15">Detalles</h4>
                                                <ul>
                                                    <li><i class="ti-shield"></i><strong
                                                            class="font-weight-700 text-black">Proyecto</strong><span
                                                            class="text-black-light"> Cod: {{ $project->code }} </span></li>
                                                    <li><i class="ti-location-pin"></i><strong
                                                            class="font-weight-700 text-black">Ubicación</strong>
                                                        {{ $project->delivery_place }} </li>
                                                    <li><i class="fa fa-clock-o"></i><strong
                                                            class="font-weight-700 text-black">Fecha de entrega</strong>
                                                        {{ \Carbon\Carbon::parse($project->final_date)->format('d/m/Y') }}
                                                    </li>
                                                    <li><i class="fa fa-money"></i><strong
                                                            class="font-weight-700 text-black">Formas de pago</strong>
                                                        @foreach ($project->payment_methods()->get() as $payment_method)
                                                            <span>- {{ $payment_method->name }}</span><br>
                                                        @endforeach
                                                    </li>
                                                    <li>
                                                        <i class="fa fa-id-card" aria-hidden="true"></i><strong
                                                            class="font-weight text-black">Contacto</strong>
                                                        <span> - Nombre:
                                                            {{ $project->user()->first()->first_name . ' ' . $project->user()->first()->last_name }}
                                                        </span><br>
                                                        <span> - Empresa: {{ $project->user()->first()->company_name }}
                                                        </span><br>
                                                        <span> - Teléfono: {{ $project->user()->first()->hide_cellphone ? '***' : $project->user()->first()->cellphone }}
                                                        </span><br>
                                                        <span> - Email: {{ $project->user()->first()->hide_email ? '***' : $project->user()->first()->email }}
                                                        </span><br>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="job-info-box">
                                    <h3 class="m-t0 m-b10 font-weight-700 title-head">{{ $project->code }} -
                                        {{ $project->title }} </h3>
                                    <p class="p-t20">{{ $project->detailed_description }} </p>
                                    <h4 class="font-weight-600">Partidas</h4>
                                    <div class="mb-2 text-right">
                                        <span class="font-11 text-muted">Debe presionar el botón <i
                                                class="fa fa-search btn btn-sm btn-primary" style="cursor: none"></i>
                                            para
                                            realizar la busqueda</span>
                                    </div>
                                    <form id="frmSearch">
                                        <input type="hidden" id="project_id" name="project_id"
                                            value="{{ $project->id }}">
                                        <div class="input-group mb-3">
                                            <input type="text" name="search" id="search" class="form-control"
                                                placeholder="Buscar por código o descripción">
                                            <div class="input-group-append">
                                                <button class="btn site-button" type="button" id="btnSearch"
                                                    title="Buscar"><i class="fa fa-search"></i></button>
                                                <button class="btn btn-outline-info" type="button" id="btnRefresh"
                                                    title="Refrescar"><i class="fa fa-refresh"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                    @if (isset(auth()->user()->id) && $project->user()->first()->id != auth()->user()->id && auth()->user()->status === 2)
                                        <br><span class="text-red font-18">Para poder aplicar a las partidas, debe completar
                                            sus
                                            datos
                                            en <a href="{{ route('client.profile') }}" style="text-decoration: none"
                                                class="badge badge-info">MI
                                                PERFIL</a></span>
                                    @endif
                                    <div class="dez-divider divider-2px bg-gray-dark mb-4 mt-0"></div>
                                    <div class="accordion" id="accordionExample">
                                        {{-- <div class="card">
                                        @foreach ($project->departures()->get() as $departure)
                                            @if ($departure->status === '2' && $departure->visible === 1)
                                                <div class="card-header" id="{{ 'heading' . $departure->id }}">
                                                    <h2 class="mb-0 d-flex">
                                                        <button class="btn btn-block text-left float-left" type="button"
                                                            data-toggle="collapse"
                                                            data-target="{{ '#collapse' . $departure->id }}"
                                                            aria-expanded="false"
                                                            aria-controls="{{ 'collapse' . $departure->id }}"
                                                            style="white-space: nowrap; text-overflow: ellipsis; overflow: hidden;">
                                                            <b>{{ $departure->code }} -
                                                                {{ $departure->description }}</b>
                                                            <br>
                                                            <div class="text-mute font-10">Fecha de entrega:
                                                                {{ \Carbon\Carbon::parse($departure->execution_date)->format('d/m/Y') }}
                                                            </div>
                                                        </button>
                                                        @if (isset(auth()->user()->id))
                                                            @if ($project->user()->first()->id != auth()->user()->id)
                                                                <div class="text-center">
                                                                    <a href="javascript:void(0)"
                                                                        class="btn btn-link-primay text-center align-middle height-100"
                                                                        onclick="apply({{ $departure->id }})"
                                                                        style="font-size: 11px;">Aplicar</a><br>
                                                                    <a href="javascript:void(0)"
                                                                        data-id="{{ $departure->id }}"
                                                                        class="btn btn-link-primay text-center align-middle height-100"
                                                                        onclick="showDetails({{ $departure->id }})"
                                                                        style="font-size: 11px;">Detalles</a>
                                                                </div>
                                                            @endif
                                                        @else
                                                            <a href="{{ route('client.login') }}"
                                                                class="btn btn-link-primary float-right text-center align-middle height-100">Aplicar</a>
                                                            <a href="javascript:void(0)"
                                                                class="btn btn-link-primay float-right text-center align-middle height-100"
                                                                onclick="showDetails({{ $departure->id }})">Ver
                                                                detalles</a>
                                                        @endif

                                                    </h2>
                                                </div>

                                                <div id="{{ 'collapse' . $departure->id }}" class="collapse"
                                                    aria-labelledby="{{ 'heading' . $departure->id }}"
                                                    data-parent="#accordionExample">
                                                    <div class="card-body">
                                                        @auth
                                                            <div class="row">
                                                                <div class="col-lg-12 col-md-12">
                                                                    <a href="#" data-toggle="collapse"
                                                                        data-target="{{ '#comment-' . $departure->id }}"
                                                                        aria-expanded="false"
                                                                        aria-controls="{{ 'comment-' . $departure->id }}"
                                                                        class="btn btn-success float-left mb-2 btn-sm">Comentar</a>
                                                                    <div class="clearfix"></div>
                                                                    <div class="collapse"
                                                                        id="{{ 'comment-' . $departure->id }}">
                                                                        <form id="frmComment" class="mt-5" method="POST"
                                                                            action="{{ route('client.projects.departures.comments.store') }}">
                                                                            @csrf
                                                                            <input type="hidden" id="departure_id"
                                                                                name="departure_id"
                                                                                value="{{ $departure->id }}">
                                                                            <input type="hidden" name="project_id"
                                                                                id="project_id" value="{{ $project->id }}">
                                                                            <div class="row form-group mb-5">
                                                                                <div class=" col-lg-11 col-md-11">
                                                                                    <label for="">Escribe tu
                                                                                        comentario</label>
                                                                                    <input class="form-control" type="text"
                                                                                        id="description" name="description"
                                                                                        required>

                                                                                </div>
                                                                                <div class="col-lg-1 col-md-1 p-2">
                                                                                    <br>
                                                                                    <button type="submit"
                                                                                        class="btn btn-default"
                                                                                        title="Enviar comentario"><i
                                                                                            class="fa fa-send"></i></button>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                        <span class="p-2 mb-2"><i><b>Comentarios:</b></i></span>
                                                                        @foreach ($departure->comments()->orderBy('created_at', 'desc')->get() as $comment)
                                                                            @if ($comment->visible === 1)
                                                                                @if (auth()->user()->id === $project->user_id)
                                                                                    @if ($comment->user_id === auth()->user()->id)
                                                                                        <div class="card mb-2 p-2 font-11 p-0">
                                                                                            <span class="text-muted font-10"><i>{{ \Carbon\Carbon::parse($comment->created_at)->format('d/m/Y') }}
                                                                                                    -
                                                                                                    {{ \Carbon\Carbon::parse($comment->created_at)->format('H:i:s a') }}
                                                                                                </i></span>
                                                                                            <i><b>Yo:&nbsp;</b></i>{{ $comment->description }}
                                                                                        </div>
                                                                                    @else
                                                                                        <div class="card mb-2 p-2 font-11">
                                                                                            <span
                                                                                                class="text-muted font-10"><i>{{ \Carbon\Carbon::parse($comment->created_at)->format('d/m/Y') }}
                                                                                                    -
                                                                                                    {{ \Carbon\Carbon::parse($comment->created_at)->format('H:i:s a') }}
                                                                                                </i></span>
                                                                                            <i><b>{{ $comment->user()->first()->company_name }}:&nbsp;</b></i>{{ $comment->description }}
                                                                                        </div>
                                                                                    @endif
                                                                                @else
                                                                                    @if ($comment->user_id === auth()->user()->id)
                                                                                        <div class="card mb-2 p-2 font-11">
                                                                                            <span
                                                                                                class="text-muted font-10"><i>{{ \Carbon\Carbon::parse($comment->created_at)->format('d/m/Y') }}
                                                                                                    -
                                                                                                    {{ \Carbon\Carbon::parse($comment->created_at)->format('H:i:s a') }}
                                                                                                </i></span>
                                                                                            <i><b>Yo:&nbsp;</b></i>{{ $comment->description }}
                                                                                        </div>
                                                                                    @else
                                                                                        <div class="card mb-2 p-2 font-11">
                                                                                            <span
                                                                                                class="text-muted font-10"><i>{{ \Carbon\Carbon::parse($comment->created_at)->format('d/m/Y') }}
                                                                                                    -
                                                                                                    {{ \Carbon\Carbon::parse($comment->created_at)->format('H:i:s a') }}
                                                                                                </i></span>
                                                                                            <i><b>Anonimo:&nbsp;</b></i>{{ $comment->description }}
                                                                                        </div>
                                                                                    @endif
                                                                                @endif
                                                                            @endif
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endauth
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div> --}}
                                    <form method="GET" action="{{ route('paginate.departues') }}" id="formPartidas">
                                        <input type="hidden" name="project" value="{{ $project->id }}" />
                                        <select name="perPage" id="per-page" class="form-control my-3" style="height: 7vh !important">
                                            <option {{old('perPage') == 10 ? 'selected' : '' }} value="10">10</option>
                                            <option {{old('perPage') == 20 ? 'selected' : '' }} value="20">20</option>
                                            <option {{old('perPage') == 30 ? 'selected' : '' }} value="30">30</option>
                                        </select>
                                    </form>
                                        @include('frontend.client.projects.partials.departures-data')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Content END-->
        <form id="frmApplyNew" enctype="multipart/form-data">
            <!-- Modal -->
            <div class="modal fade browse-job modal-bx-info editor" id="mdlApplyNew" tabindex="-1" role="dialog"
                aria-labelledby="ProfilenameModalLongTitle" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ProfilenameModalLongTitle">Aplicar a partida - <span
                                    id="view_title"></span></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="departure_id" id="departure_id">
                            <input type="hidden" name="project_id" id="project_id">
                            <input type="hidden" id="percentage_iva" name="percentage_iva"
                                value="{{ $percentage_iva ? $percentage_iva : 0 }}">
                            @include('frontend.client.projects.departure.apply.form')
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

        <!-- Modal -->
        <div class="modal fade browse-job modal-bx-info editor" id="mdlShowDetail" tabindex="-1" role="dialog"
            aria-labelledby="ProfilenameModalLongTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ProfilenameModalLongTitle">Detalles de la partida<span
                                id="view_title"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row" id="view_departure_details"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal End -->

        <form id="frmVariantNew">
            <!-- Modal -->
            <div class="modal fade modal-bx-info" id="mdlVariantNew" tabindex="-1" role="dialog"
                aria-labelledby="ProfilenameModalLongTitle" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ProfilenameModalLongTitle">Agregar variante</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="departure_id" id="departure_id">
                            @include('frontend.client.projects.departure.apply.variants.form')
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

        <!-- Content END-->
        <form id="frmCommentEdit">
            <!-- Modal -->
            <div class="modal fade browse-job modal-bx-info editor" id="mdlCommentEdit" tabindex="-1" role="dialog"
                aria-labelledby="ProfilenameModalLongTitle" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ProfilenameModalLongTitle">Editar comentario</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="comment_id" id="comment_id">
                            <input type="hidden" name="project_id" id="project_id" value="{{ $project->id }}">
                            @include('frontend.client.projects.departure.comments.form')
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

        <!-- Content END-->
        <form id="frmCommentReport">
            <!-- Modal -->
            <div class="modal fade browse-job modal-bx-info editor" id="mdlCommentReport" tabindex="-1" role="dialog"
                aria-labelledby="ProfilenameModalLongTitle" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ProfilenameModalLongTitle">Reportar comentario</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="comment_id" id="comment_id">
                            <input type="hidden" name="project_id" id="project_id" value="{{ $project->id }}">
                            @include('frontend.client.projects.departure.report.form')
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-danger"><i
                                    class="fa fa-bug"></i>&nbsp;Reportar</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal End -->
        </form>
    @else
        <div class="page-content bg-white">
            <!-- contact area -->
            <div class="content-block">
                <!-- Job Detail -->
                <div class="section-full content-inner-1">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="sticky-top">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-6">
                                            <div class="m-b30">
                                                <img src="{{ asset('images/image_default.jpeg') }}" alt=""
                                                    style="width: 100%">
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-6">
                                            <div class="widget bg-white p-lr20 p-t20  widget_getintuch radius-sm">
                                                <h4 class="text-black font-weight-700 p-t10 m-b15">Detalles</h4>
                                                <ul>
                                                    <li><i class="ti-shield"></i><strong
                                                            class="font-weight-700 text-black">Proyecto</strong><span
                                                            class="text-black-light"> Cod: Sin datos </span>
                                                    </li>
                                                    <li><i class="ti-location-pin"></i><strong
                                                            class="font-weight-700 text-black">Ubicación</strong>
                                                        Sin datos </li>
                                                    <li><i class="fa fa-clock-o"></i><strong
                                                            class="font-weight-700 text-black">Fecha de entrega</strong>
                                                        Sin datos
                                                    </li>
                                                    <li><i class="fa fa-money"></i><strong
                                                            class="font-weight-700 text-black">Formas de pago</strong>
                                                        Sin datos
                                                    </li>
                                                    <li>
                                                        <i class="fa fa-id-card" aria-hidden="true"></i><strong
                                                            class="font-weight text-black">Contacto</strong>
                                                        <span> - Nombre:
                                                            Sin datos
                                                        </span><br>
                                                        <span> - Empresa: Sin datos
                                                        </span><br>
                                                        <span> - Teléfono: Sin datos
                                                        </span><br>
                                                        <span> - Email: Sin datos
                                                        </span><br>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="job-info-box">
                                    <h3 class="m-t0 m-b10 font-weight-700 title-head">Sin datos </h3>
                                    <p class="p-t20">Sin datos </p>
                                    <h5 class="font-weight-600">Partidas</h5>
                                    {{-- <form id="frmSearch">
                                        <input type="hidden" id="project_id" name="project_id"
                                            value="{{ $project->id }}">
                                        <div class="input-group mb-3">
                                            <input type="text" name="search" id="search" class="form-control"
                                                placeholder="Buscar por código o descripción">
                                            <div class="input-group-append">
                                                <button class="btn site-button" type="button" id="btnSearch"
                                                    title="Buscar"><i class="fa fa-search"></i></button>
                                                <button class="btn btn-outline-info" type="button" id="btnRefresh"
                                                    title="Refrescar"><i class="fa fa-refresh"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                    @if (isset(auth()->user()->id) && $project->user()->first()->id != auth()->user()->id && auth()->user()->status === 2)
                                        <br><span class="text-red font-18">Para poder aplicar a las partidas, debe
                                            completar
                                            sus
                                            datos
                                            en <a href="{{ route('client.profile') }}" style="text-decoration: none"
                                                class="badge badge-info">MI
                                                PERFIL</a></span>
                                    @endif --}}
                                    <div class="dez-divider divider-2px bg-gray-dark mb-4 mt-0"></div>
                                    <div class="accordion" id="accordionExample">
                                        <div class="row">
                                            <div class="col-md-12 col-12 text-center">
                                                <h5>Sin datos</h5>
                                            </div>
                                        </div>
                                        {{-- @include('frontend.client.projects.partials.departures-data') --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection
