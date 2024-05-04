@extends('layouts.frontend.app')

@section('content')
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
                                    <h5 class="font-weight-700 pull-left text-uppercase">MIS NOTIFICACIONES</h5>
                                </div>
                                {{-- @include('frontend.client.projects.my-projects.partials.projects-data') --}}
                                <div style="overflow: auto" style="width: 100%">
                                    <table class="table table-bordered table-job-bx" style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th>Proyecto</th>
                                                <th>Notificación</th>
                                                <th>Fecha</th>
                                                <th>Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (count(auth()->user()->notifications))
                                                @foreach (auth()->user()->notifications as $notification)
                                                    @php
                                                        //dd($notification->data['type']);
                                                        $type_message = [1 => 'Nuevo proyecto', 2 => 'Nuevo comentario', 3 => 'Nueva oferta a partida'];
                                                        switch ($notification->data['type']) {
                                                            case 1:
                                                            case 2:
                                                                // 1 - New Project
                                                                // 2 - New Comment
                                                                $route = route('client.projects') . '?project=' . $notification->data['id'];
                                                                break;
                                                            case 3:
                                                                // New Apply Project
                                                                $route = route('client.projects.my-projects.applications') . '?project=' . $notification->data['id'];
                                                            case 4:
                                                                // New Apply Project
                                                                $route = route('client.projects.my-projects.applications') . '?project=' . $notification->data['id'];
                                                            default:
                                                                break;
                                                        }
                                                    @endphp
                                                    <tr>
                                                        <td><b>{{ $notification->data['project_title'] }}</b>
                                                        </td>
                                                        <td>{{ $notification->data['message'] }} </td>
                                                        <td>{{ $notification->data['publication_date'] }} </td>
                                                        <td>
                                                            <center>
                                                                <a href="{{ $route }} "
                                                                    class="btn btn-primary btn-sm"><i
                                                                        class="fa fa-eye"></i></a>
                                                                <a href="javascript:void(0)"
                                                                    class="btn btn-danger btn-sm"><i class="fa fa-trash"
                                                                        onclick="deleteNotification('{{ $notification->id }}')"></i></a>
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
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- Browse Jobs END -->
        </div>
    </div>
    <!-- Content END-->
@endsection
