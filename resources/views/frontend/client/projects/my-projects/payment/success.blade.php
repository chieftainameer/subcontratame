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
                                    <h5 class="font-weight-700 pull-left text-uppercase">PAGO CORRECTAMENTE</h5>
                                </div>
                                <div class="text-center">
                                    <img src="{{ asset('images/ok-icon.jpg') }} " alt="Ok Icon"><br><br>
                                    <h2>El pago se realizo correctamente</h2>
                                    <a href="{{ route('client.projects.my-projects.departures', ['project_id' => session()->get('project')]) }}"
                                        class="btn btn-primary btn-sm">Ir a detalles del proyecto</a>
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
