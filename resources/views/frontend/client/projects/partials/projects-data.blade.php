<ul class="post-job-bx browse-job">
    @if (!empty($projects) && $projects->count())
        @foreach ($projects as $project)
            <li>
                <div class="post-bx">
                    <div class="d-flex m-b30">
                        <div class="job-post-company">
                            <span><img alt=""
                                    src="{{ $project->image !== null ? asset('storage') . '/' . $project->image : asset('images/image_default.jpeg') }}"></span>
                        </div>
                        <div class="job-post-info">
                            <h4><a href="{{ route('client.projects') . '?project=' . $project->id }}">{{ $project->code }}
                                    - {{ $project->title }}</a></h4>
                            <p class="m-b5 font-13">
                                {{ $project->short_description }}
                            </p>
                            <ul>
                                <li><i class="fa fa-map-marker"></i>{{ $project->delivery_place }}</li>
                                {{-- <li><i class="fa fa-bookmark-o"></i> Full Time</li> --}}
                                <li><i class="fa fa-clock-o"></i> Vence:
                                    {{ \Carbon\Carbon::parse($project->final_date)->format('d/m/Y') }}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="job-time m-t15 m-b10">
                            @foreach ($project->categories as $category)
                                <a href="javascript:void(0);"><span>{{ $category->name }}</span></a>
                            @endforeach
                        </div>
                    </div>
                    <div class="float-right">
                        <a href="{{ route('client.projects', ['project' => $project->id]) }}"
                            class="btn btn-link btn-sm mb-3"><i class="fa fa-eye"></i></a>
                    </div>
                    @auth
                        @if ($project->user_id !== auth()->user()->id)
                            <label class="like-btn">
                                <input type="checkbox">
                                <span class="checkmark" onclick="saveProject({{ $project->id }})"></span>
                            </label>
                        @endif
                    @endauth
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
        {!! $projects->onEachSide(2)->links() !!}
    </div>
</div>
