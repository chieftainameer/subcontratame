<ul class="post-job-bx browse-job">
    @if (!empty($variants) && $variants->count())
        @foreach ($variants as $variant)
            <li>
                <div class="post-bx">
                    <div class="job-post-info m-a0">
                        <h4><a
                                href="{{ route('client.projects') .'?project=' .$variant->departure()->first()->project()->first()->id }}">{{ $variant->departure()->first()->project()->first()->code .' - ' .$variant->departure()->first()->project()->first()->title }}</a>
                        </h4>
                        <ul>
                            <li><i class="fa fa-map-marker">&nbsp;{{ $variant->departure()->first()->project()->first()->delivery_place }}
                                </i></li>
                            <li><i class="fa fa-bookmark-o"></i>Partida:&nbsp;{{ $variant->departure()->first()->code }}
                            </li>
                            @php
                                $comment = $variant
                                    ->departure()
                                    ->first()
                                    ->comments()
                                    ->where('user_id', auth()->user()->id)
                                    ->get()
                                    ->count();
                            @endphp
                            @if ($comment)
                                <li><i class="fa fa-comments-o"></i>&nbsp;Comentaste</li>
                            @endif
                            <li><i class="fa fa-clock-o"></i>&nbsp;Publicado:
                                {{ \Carbon\Carbon::parse($variant->departure()->first()->project()->first()->publication_date)->format('d/m/Y') }}
                            </li>
                        </ul>
                        <div class="job-time m-t15 m-b10">
                            @foreach ($variant->departure()->first()->project()->first()->categories()->get() as $category)
                                <a href="javascript:void(0);"><span>{{ $category->name }}</span></a>
                            @endforeach
                        </div>
                        @php
                            $defeated = false;
                            if (\Carbon\Carbon::parse($variant->expiration_date)->lessThan(\Carbon\Carbon::now())) {
                                $defeated = true;
                            }
                        @endphp
                        <div class="posted-info clearfix">
                            @if ($defeated)
                                <p class="m-tb0 text-danger float-left"><span class="text-black m-r10">Vencimiento de
                                        la
                                        oferta:</span>&nbsp;{{ \Carbon\Carbon::parse($variant->expiration_date)->format('d/m/Y') }}

                                </p>
                                <a href="javascript:void(0)" class="btn btn-warning btn-sm float-right text-white"
                                    onclick="editOffer({{ $variant->id }})"><i class="fa fa-edit"
                                        title="Editar"></i></a>
                                <a href="javascript:void(0)" class="btn btn-primary btn-sm float-right mr-1"
                                    onclick="showOffer({{ $variant->id }})"><i class="fa fa-eye"
                                        title="Ver"></i></a>
                            @else
                                <p class="m-tb0 text-primary float-left"><span class="text-black m-r10">Vencimiento de
                                        la
                                        oferta:</span>&nbsp;{{ \Carbon\Carbon::parse($variant->expiration_date)->format('d/m/Y') }}
                                </p>
                                <a href="javascript:void(0)" class="btn btn-warning btn-sm float-right text-white"><i
                                        class="fa fa-edit" onclick="editOffer({{ $variant->id }})"
                                        title="Editar"></i></a>
                                <a href="javascript:void(0)" class="btn btn-primary btn-sm float-right mr-1"><i
                                        class="fa fa-eye" onclick="showOffer({{ $variant->id }})"
                                        title="Ver"></i></a>
                            @endif

                        </div>
                    </div>
                </div>
            </li>
        @endforeach
    @else
        <li class="col-lg-12 col-md-12">
            <h5 class="text-center">No hay datos</h5>
        </li>
    @endif
</ul>
<div class="row text-lg-center  mt-lg-5 mt-5">
    <div class="col-lg-12 col-md-12 mt-5 text-center">
        {!! $variants->onEachSide(2)->links() !!}
    </div>
</div>
