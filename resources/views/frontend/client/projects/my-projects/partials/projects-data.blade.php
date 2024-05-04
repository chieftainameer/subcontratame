<ul class="post-job-bx browse-job-grid post-resume row">
    @if (!empty($projects) && $projects->count())
        @foreach ($projects as $project)
            <li class="col-lg-6 col-md-6">
                <div class="post-bx">
                    @if (!$project->departures()->count())
                        <div class="badge badge-danger p-2 font-10">
                            Sin partida(s)
                        </div>
                    @else
                        <div class="badge badge-success p-2 font-10">
                            {{ $project->departures()->count() }} partida(s)
                        </div>
                    @endif
                    <div class="d-flex m-b20 ">
                        <div class="">
                            <h5 class="m-b0 mt-3 font-14">
                                {{ $project->code }} -
                                {{ $project->title }}</h5>
                            <p class="m-b5 font-13">
                                {{ $project->short_description }}
                            </p>
                            <ul>
                                <li><i class="fa fa-map-marker"></i>&nbsp;Lugar:&nbsp;{{ $project->delivery_place }}
                                </li>
                                <br>
                                <li><i class="fa fa-clock-o"></i>&nbsp;Vence:&nbsp;
                                    {{ \Carbon\Carbon::parse($project->final_date)->format('d/m/Y') }}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="job-time m-t15 m-b10">
                        @foreach ($project->categories as $category)
                            <a href="javascript:void(0);"><span>{{ $category->name }}</span></a>
                        @endforeach
                    </div>
                    @php
                        $applications = 0;
                        foreach ($project->departures()->get() as $departure) {
                            $applications += $departure->variants()->count();
                        }
                    @endphp
                    @if ($applications > 0)
                        <div class="job-time mb-0">
                            <a href="{{ route('client.projects.my-projects.applications') . '?project=' . $project->id }} "
                                class="font-italic font-11 text-blue"><i class="fa fa-filter"></i>{{ $applications }}
                                aplicaciones</a>
                        </div>
                    @else
                        <div class="job-time mb-0">
                            <a href="javascript:void(0)" class="font-italic font-11 text-blue"><i
                                    class="fa fa-filter"></i>{{ $applications }}
                                aplicaciones</a>
                        </div>
                    @endif
                    <div class="job-links">
                        <a href="#" class="float-lg-right float-right text-danger" onclick="deleteProject({{ $project->id }})"
                           title="Eliminar proyecto">
                            <i class="fa fa-trash text-danger"></i>
                        </a>
                        <a href="#" class="float-lg-right float-right"
                            onclick="addDeparture({{ $project->id }})" title="Gestionar partidas">
                            <i class="fa fa-file-text-o"></i>
                        </a>
                        <a href="#" class="float-lg-right float-right" onclick="editProject({{ $project->id }})"
                            title="Editar proyecto">
                            <i class="fa fa-edit"></i>
                        </a>
                    </div>


                </div>
            </li>
        @endforeach
    @else
        <li class="col-lg-12 col-md-12">
            <h1 class="text-center">No hay datos</h1>
        </li>
    @endif
</ul>
<div class="row text-lg-center  mt-lg-5 mt-5">
    <div class="col-lg-12 col-md-12 mt-5 text-center">
        {!! $projects->onEachSide(2)->links() !!}
    </div>
</div>
