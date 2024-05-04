@if (!empty($project))
    @php
        if (request()->get('search')) {
            $departures = $project
                ->departures()
                ->where('status', '2')
                ->where('visible', 1)
                ->where('complete', 0)
                ->where(function ($q) {
                    $q->where('description', 'like', '%' . request()->get('search') . '%')->orWhere('code', 'like', '%' . request()->get('search') . '%');
                })
                ->orderBy('created_at', 'desc')
                ->paginate($perPage)
                ->withQueryString();
        } else {
            $departures = $project
                ->departures()
                ->where('status', '2')
                ->where('visible', 1)
                ->where('complete', 0)
                ->orderBy('created_at', 'desc')
                ->paginate($perPage)
                ->withQueryString();
        }
        //dd(count($departures));
    @endphp

    @if (count($departures))
        <div class="card">
            @foreach ($departures as $departure)
                {{-- @if ($departure->status === '2' && $departure->visible === 1 && $departure->complete === 0) --}}
                <div class="card-header" id="{{ 'heading' . $departure->id }}">
                    <h2 class="mb-0 d-flex">
                        <button class="btn btn-block text-left float-left" type="button" data-toggle="collapse"
                            data-target="{{ '#collapse' . $departure->id }}" aria-expanded="false"
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
                                @if (auth()->user()->status === 1)
                                    <a href="javascript:void(0)"
                                        class="btn btn-link-primay text-center align-middle height-100"
                                        onclick="apply({{ $departure->id }})" style="font-size: 11px;">Aplicar</a><br>
                                @endif
                                <a href="javascript:void(0)" data-id="{{ $departure->id }}"
                                    class="btn btn-link-primay text-center align-middle height-100"
                                    onclick="showDetailsWithSession({{ $departure->id }})"
                                    style="font-size: 11px;">Detalles</a>
                            @endif
                        @else
                            <a href="{{ route('client.login') }}"
                                class="btn btn-link-primary float-right text-center align-middle height-100"
                                style="font-size: 11px;">Aplicar</a>
                            @if (auth()->user())
                                <a href="javascript:void(0)"
                                    class="btn btn-link-primay float-right text-center align-middle height-100"
                                    onclick="showDetailsWithSession({{ $departure->id }})" style="font-size: 11px;">Ver
                                    detalles</a>
                            @else
                                <a href="javascript:void(0)"
                                    class="btn btn-link-primay float-right text-center align-middle height-100"
                                    onclick="showDetailsWithoutSession({{ $departure->id }})"
                                    style="font-size: 11px;">Detalles</a>
                            @endif
                        @endif
                    </h2>
                </div>
                <div id="{{ 'collapse' . $departure->id }}" class="collapse"
                    aria-labelledby="{{ 'heading' . $departure->id }}" data-parent="#accordionExample">
                    <div class="card-body">
                        {{-- @auth --}}
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <a href="#" data-toggle="collapse"
                                    data-target="{{ '#comment-' . $departure->id }}" aria-expanded="false"
                                    aria-controls="{{ 'comment-' . $departure->id }}"
                                    class="btn btn-success float-left mb-2 btn-sm">{{ auth()->check() ? 'Comentar' : 'Ver comentarios' }}</a>
                                <div class="clearfix"></div>
                                <div class="collapse" id="{{ 'comment-' . $departure->id }}">
                                    @auth
                                        <form id="frmComment" class="mt-3" method="POST"
                                            action="{{ route('client.projects.departures.comments.store') }}">
                                            @csrf
                                            <input type="hidden" id="departure_id" name="departure_id"
                                                value="{{ $departure->id }}">
                                            <input type="hidden" name="project_id" id="project_id"
                                                value="{{ $project->id }}">
                                            <div class="row form-group mb-5">
                                                <div class=" col-lg-11 col-md-11">
                                                    <label for="">Escribe tu
                                                        comentario</label>
                                                    <input class="form-control" type="text" id="description"
                                                        name="description" required>

                                                </div>
                                                <div class="col-lg-1 col-md-1 p-2">
                                                    <br>
                                                    <button type="submit" class="btn btn-default"
                                                        title="Enviar comentario"><i class="fa fa-send"></i></button>
                                                </div>
                                            </div>
                                        </form>
                                    @endauth
                                    <span class="p-2 mb-3 mt-2"><i><b>Comentarios:</b></i></span>
                                    @if ($departure->comments()->get()->count())
                                        @foreach ($departure->comments()->orderBy('created_at', 'desc')->get() as $comment)
                                            @if ($comment->visible === 1)
                                                @if (auth()->check())
                                                    @if (auth()->user()->id === $project->user_id)
                                                        @if ($comment->user_id === auth()->user()->id)
                                                            <div class="card mb-2 p-2 font-11 p-0">
                                                                <div class="row">
                                                                    <div class="col-md-12 col-12">
                                                                        <span class="text-muted font-10"><i>{{ \Carbon\Carbon::parse($comment->created_at)->format('d/m/Y') }}
                                                                                -
                                                                                {{ \Carbon\Carbon::parse($comment->created_at)->format('H:i:s a') }}
                                                                            </i></span>
                                                                    </div>
                                                                    <div class="col-md-12 col-12">
                                                                        <i><b>Yo
                                                                                dije:&nbsp;</b></i><br>{{ $comment->description }}
                                                                    </div>
                                                                    @if ($comment->reported)
                                                                        <div class="col-md-12 col-12">
                                                                            <div class="float-right">
                                                                                <span
                                                                                    class="badge badge-danger p-1">Reportado</span>
                                                                            </div>
                                                                        </div>
                                                                    @else
                                                                        <div class="col-md-12 col-12">
                                                                            <div class="float-right"><a
                                                                                    href="javascript:void(0)"
                                                                                    class="btn btn-warning btn-sm text-white"
                                                                                    title="Editar"
                                                                                    data-id="{{ $comment->id }}"
                                                                                    id="btnCommentEdit"><i
                                                                                        class="fa fa-pencil-square-o"
                                                                                        aria-hidden="true"></i></a>
                                                                                <a href="javascript:void(0)"
                                                                                    class="btn btn-danger btn-sm"
                                                                                    title="Eliminar"
                                                                                    data-id="{{ $comment->id }}"
                                                                                    id="btnCommentDelete"><i
                                                                                        class="fa fa-trash"
                                                                                        aria-hidden="true"></i></a>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="card mb-2 p-2 font-11">
                                                                <div class="row">
                                                                    <div class="col-md-12 col-12">
                                                                        <span class="text-muted font-10"><i>{{ \Carbon\Carbon::parse($comment->created_at)->format('d/m/Y') }}
                                                                                -
                                                                                {{ \Carbon\Carbon::parse($comment->created_at)->format('H:i:s a') }}
                                                                            </i></span>
                                                                    </div>
                                                                    <div class="col-md-12 col-12">
                                                                        <i><b>{{ $comment->user()->first()->company_name }}
                                                                                dice:&nbsp;</b></i><br>{{ $comment->description }}
                                                                    </div>
                                                                    @if ($comment->reported)
                                                                        <div class="col-md-12 col-12">
                                                                            <div class="float-right">
                                                                                <span
                                                                                    class="badge badge-danger p-1">Reportado</span>
                                                                            </div>
                                                                        </div>
                                                                    @else
                                                                        <div class="col-md-12 col-12">
                                                                            <div class="float-right"><a
                                                                                    href="javascript:void(0)"
                                                                                    title="Reportar"
                                                                                    data-id="{{ $comment->id }}"
                                                                                    id="btnCommentReport"
                                                                                    class="btn btn-secondary btn-sm"><i
                                                                                        class="fa fa-bug"
                                                                                        aria-hidden="true"></i></a>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @else
                                                        @if ($comment->user_id === auth()->user()->id)
                                                            <div class="card mb-2 p-2 font-11">
                                                                <div class="row">
                                                                    <div class="col-md-12 col-12">
                                                                        <span class="text-muted font-10"><i>{{ \Carbon\Carbon::parse($comment->created_at)->format('d/m/Y') }}
                                                                                -
                                                                                {{ \Carbon\Carbon::parse($comment->created_at)->format('H:i:s a') }}
                                                                            </i></span>

                                                                    </div>
                                                                    <div class="col-md-12 col-12">
                                                                        <i><b>Yo
                                                                                dije:&nbsp;</b></i><br>{{ $comment->description }}
                                                                    </div>
                                                                    @if ($comment->reported)
                                                                        <div class="col-md-12 col-12">
                                                                            <div class="float-right">
                                                                                <span
                                                                                    class="badge badge-danger p-1">Reportado</span>
                                                                            </div>
                                                                        </div>
                                                                    @else
                                                                        <div class="col-md-12 col-12">
                                                                            <div class="float-right"><a
                                                                                    href="javascript:void(0)"
                                                                                    class="btn btn-warning btn-sm text-white"
                                                                                    title="Editar"
                                                                                    data-id="{{ $comment->id }}"
                                                                                    id="btnCommentEdit"><i
                                                                                        class="fa fa-pencil-square-o"
                                                                                        aria-hidden="true"></i></a>
                                                                                <a href="javascript:void(0)"
                                                                                    class="btn btn-danger btn-sm"
                                                                                    title="Eliminar"
                                                                                    data-id="{{ $comment->id }}"
                                                                                    id="btnCommentDelete"><i
                                                                                        class="fa fa-trash"
                                                                                        aria-hidden="true"></i></a>
                                                                            </div>
                                                                        </div>
                                                                    @endif

                                                                </div>
                                                            </div>
                                                        @else
                                                            @if (!$comment->reported)
                                                                @if ($comment->user_id !== $project->user_id)
                                                                    <div class="card mb-2 p-2 font-11">
                                                                        <div class="row">
                                                                            <div class="col-md-12 col-12">
                                                                                <span class="text-muted font-10"><i>{{ \Carbon\Carbon::parse($comment->created_at)->format('d/m/Y') }}
                                                                                        -
                                                                                        {{ \Carbon\Carbon::parse($comment->created_at)->format('H:i:s a') }}
                                                                                    </i></span>
                                                                            </div>
                                                                            <div class="col-md-12 col-12">
                                                                                <i><b>Anónimo
                                                                                        dice:&nbsp;</b></i><br>{{ $comment->description }}
                                                                            </div>
                                                                            <div class="col-md-12 col-12">
                                                                                <div class="float-right"><a
                                                                                        href="javascript:void(0)"
                                                                                        title="Reportar"
                                                                                        data-id="{{ $comment->id }}"
                                                                                        id="btnCommentReport"
                                                                                        class="btn btn-secondary btn-sm"><i
                                                                                            class="fa fa-bug"
                                                                                            aria-hidden="true"></i></a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <div class="card mb-2 p-2 font-11">
                                                                        <div class="row">
                                                                            <div class="col-md-12 col-12">
                                                                                <span class="text-muted font-10"><i>{{ \Carbon\Carbon::parse($comment->created_at)->format('d/m/Y') }}
                                                                                        -
                                                                                        {{ \Carbon\Carbon::parse($comment->created_at)->format('H:i:s a') }}
                                                                                    </i></span>
                                                                            </div>
                                                                            <div class="col-md-12 col-12">
                                                                                <i><b>{{ $comment->user()->first()->company_name }}
                                                                                        dice:&nbsp;</b></i><br>{{ $comment->description }}
                                                                            </div>
                                                                            <div class="col-md-12 col-12">
                                                                                <div class="float-right"><a
                                                                                        href="javascript:void(0)"
                                                                                        title="Reportar"
                                                                                        data-id="{{ $comment->id }}"
                                                                                        id="btnCommentReport"
                                                                                        class="btn btn-secondary btn-sm"><i
                                                                                            class="fa fa-bug"
                                                                                            aria-hidden="true"></i></a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @endif
                                                @else
                                                    @if (!$comment->reported)
                                                        @if ($comment->user_id !== $project->user_id)
                                                            <div class="card mb-2 p-2 font-11">
                                                                <div class="row">
                                                                    <div class="col-md-12 col-12">
                                                                        <span class="text-muted font-10"><i>{{ \Carbon\Carbon::parse($comment->created_at)->format('d/m/Y') }}
                                                                                -
                                                                                {{ \Carbon\Carbon::parse($comment->created_at)->format('H:i:s a') }}
                                                                            </i></span>
                                                                    </div>
                                                                    <div class="col-md-12 col-12">
                                                                        <i><b>Anónimo:&nbsp;</b></i><br>{{ $comment->description }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="card mb-2 p-2 font-11">
                                                                <div class="row">
                                                                    <div class="col-md-12 col-12">
                                                                        <span class="text-muted font-10"><i>{{ \Carbon\Carbon::parse($comment->created_at)->format('d/m/Y') }}
                                                                                -
                                                                                {{ \Carbon\Carbon::parse($comment->created_at)->format('H:i:s a') }}
                                                                            </i></span>
                                                                    </div>
                                                                    <div class="col-md-12 col-12">
                                                                        <i><b>{{ $comment->user()->first()->company_name }}:&nbsp;</b></i><br>{{ $comment->description }}
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        @endif
                                                    @endif
                                                @endif
                                            @endif
                                        @endforeach
                                    @else
                                        <br><span class="p-2 mb-2">No hay comentarios</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        {{-- @endauth --}}
                    </div>
                    {{-- iba aqui lo de arriba --}}
                </div>
                {{-- @endif --}}
            @endforeach
        </div>
    @else
        <div class="row">
            <div class="col-md-12 col-12 mt-5 text-center">
                <h5>Sin resultados</h5>
            </div>
        </div>
    @endif
@else
    <div class="row">
        <div class="col-lg-12 col-md-12 mt-5 text-center">
            <h5>No hay datos</h5>
        </div>
    </div>
@endif
@if (!empty($project))
    <div class="row text-lg-center  mt-lg-5 mt-5">
        <div class="col-lg-12 col-md-12 mt-5 text-center">
            {!! $departures->onEachSide(2)->links() !!}
        </div>
    </div>
@endif
