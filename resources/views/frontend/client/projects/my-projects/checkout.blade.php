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
                                    <h5 class="font-weight-700 pull-left text-uppercase">DETALLES DEL PAGO</h5>
                                    <div class="float-right">

                                    </div>
                                </div>
                                <table id="tableDataPrices" class="table table-bordered table-job-bx">
                                    <thead>
                                        <tr>
                                            <th>Nº</th>
                                            <th>Descripción</th>
                                            <th>Cantidad</th>
                                            <th>Precio unitario</th>
                                            <th>Precio total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (session()->get('numDepartures') !== null &&
                                            session()->get('numDepartures') > 0 &&
                                            (session()->get('numVariables') === null || session()->get('numVariables') === 0))
                                            @php
                                                $description = 'Pago de ' . session()->get('numDepartures') . ' partida(s)';
                                            @endphp
                                            <tr>
                                                <td scope="row">1</td>
                                                <td>Partidas</td>
                                                <td class="text-center">{{ session()->get('numDepartures') }}</td>
                                                <td class="text-right">
                                                    {{ number_format((float) session()->get('price_departure'), 2, ',', '.') }}
                                                </td>
                                                <td class="text-right">
                                                    {{ number_format((int) session()->get('numDepartures') * (float) session()->get('price_departure'), 2, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endif
                                        @if ((session()->get('numDepartures') === null || session()->get('numDepartures') === 0) &&
                                            session()->get('numVariables') > 0 &&
                                            session()->get('numVariables') !== null)
                                            @php
                                                $description = 'Pago de ' . session()->get('numVariables') . ' variable(s)';
                                            @endphp
                                            <tr>
                                                <td scope="row">1</td>
                                                <td>Variables</td>
                                                <td class="text-center">{{ (int) session()->get('numVariables') }}</td>
                                                <td class="text-right">
                                                    {{ number_format((float) session()->get('price_variable'), 2, ',', '.') }}
                                                </td>
                                                <td class="text-right">
                                                    {{ number_format((int) session()->get('numVariables') * (float) session()->get('price_variable'), 2, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endif
                                        @if (session()->get('numDepartures') !== null &&
                                            session()->get('numDepartures') > 0 &&
                                            session()->get('numVariables') !== null &&
                                            session()->get('numVariables') > 0)
                                            @php
                                                $description = 'Pago de ' . session()->get('numDepartures') . ' partida(s) y ' . session()->get('numVariables') . ' variable(s)';
                                            @endphp
                                            <tr>
                                                <td scope="row">1</td>
                                                <td>Partidas</td>
                                                <td class="text-center">{{ session()->get('numDepartures') }}</td>
                                                <td class="text-right">
                                                    {{ number_format(session()->get('price_departure'), 2, ',', '.') }}</td>
                                                <td class="text-right">
                                                    {{ number_format(session()->get('numDepartures') * session()->get('price_departure'), 2, ',', '.') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td scope="row">2</td>
                                                <td>Variables</td>
                                                <td class="text-center">{{ session()->get('numVariables') }}</td>
                                                <td class="text-right">
                                                    {{ number_format(session()->get('price_variable'), 2, ',', '.') }}</td>
                                                <td class="text-right">
                                                    {{ number_format(session()->get('numVariables') * session()->get('price_variable'), 2, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endif
                                        @php
                                            $total = number_format(session()->get('numDepartures') * session()->get('price_departure') + session()->get('numVariables') * session()->get('price_variable'), 2, ',', '.');
                                        @endphp
                                        <tr>
                                            <td colspan="4" class="text-right"><b>Total:</b></td>
                                            <td class="text-right">
                                                @php
                                                    session()->put('total',(float)$total + ((float)$total * 0.21))
                                                @endphp
                                                {{ session()->get('total') }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="text-right">
                                    <a href="{{ route('client.projects.my-projects.payment', ['string' => $description]) . '?project=' . $project_id }}"
                                        class="btn btn-primary">Pagar</a>
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
