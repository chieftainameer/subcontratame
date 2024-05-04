@extends('layouts.frontend.app')
@section('content')
    @php
        //dd($intent);
    @endphp
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
                                    <h5 class="font-weight-700 pull-left text-uppercase">PAGAR</h5>
                                </div>
                                @php
                                    //dd($product);
                                @endphp
                                {{-- @include('frontend.client.projects.my-projects.partials.projects-data') --}}
                                <form
                                    action="{{ route('client.projects.my-projects.process-payment', [$product, $price]) }}"
                                    method="POST" id="subscribe-form">
                                    <input type="hidden" id="project_id" name="project_id" value="{{ $project_id }}">
                                    <div class="form-group">
                                        <div class="row text-center">
                                            <div class="col-md-12">
                                                {{-- <div class="subscription-option">
                                                    <label for="plan-silver">
                                                        <span class="plan-price">${{ $price }}</span>
                                                    </label>
                                                </div> --}}
                                                <label for="" class="font-weight-700">{{ session()->get('total') }}
                                                    EUR
                                                </label><br>
                                                <span>{{ $product }} </span>
                                            </div>
                                        </div>
                                    </div>
                                    <label for="card-holder-name">Nombre del titular de la tarjeta</label>
                                    {{-- <label for="card-holder-name">Card Holder Name</label> --}}
                                    <input id="card-holder-name" type="text" class="form-control"
                                        value="{{ $user->first_name . ' ' . $user->last_name }}" disabled> <br>
                                    @csrf
                                    <div class="form-row">
                                        <label for="card-element">Tarjeta de crédito o débito</label>
                                        {{-- <label for="card-element">Credit or debit card</label> --}}
                                        <div id="card-element" class="form-control"> </div><br>
                                        <!-- Used to display form errors. -->
                                        <div id="card-errors" role="alert" class="text-red mt-3"></div>
                                    </div><br>
                                    <div class="stripe-errors"></div>
                                    @if (count($errors) > 0)
                                        <div class="alert alert-danger">
                                            @foreach ($errors->all() as $error)
                                                {{ $error }}<br>
                                            @endforeach
                                        </div>
                                    @endif
                                    <div class="form-group text-right">
                                        <button type="button" id="card-button" data-secret="{{ $intent->client_secret }}"
                                            class="btn btn-lg btn-primary btn-sm">REALIZAR PAGO</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Browse Jobs END -->
        </div>
    </div>
    <!-- Content END-->
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        var stripe = Stripe('{{ env('STRIPE_KEY') }}');
        console.log(stripe);
        var elements = stripe.elements();
        console.log(elements);
        var style = {
            base: {
                color: '#32325d',
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '16px',
                '::placeholder': {
                    color: '#aab7c4'
                }
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        };
        var card = elements.create('card', {
            hidePostalCode: true,
            style: style
        });
        card.mount('#card-element');
        console.log(document.getElementById('card-element'));
        card.addEventListener('change', function(event) {
            var displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });
        const cardHolderName = document.getElementById('card-holder-name');
        const cardButton = document.getElementById('card-button');
        const clientSecret = cardButton.dataset.secret;
        cardButton.addEventListener('click', async (e) => {
            console.log("attempting");
            const {
                setupIntent,
                error
            } = await stripe.confirmCardSetup(
                clientSecret, {
                    payment_method: {
                        card: card,
                        billing_details: {
                            name: cardHolderName.value
                        }
                    }
                }
            );

            //console.log(error, setupIntent);

            if (error) {
                console.log('paso por aqui');
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = error.message;
            } else {

                paymentMethodHandler(setupIntent.payment_method);
            }
        });

        function paymentMethodHandler(payment_method) {
            var form = document.getElementById('subscribe-form');
            var hiddenInput = document.createElement('input');
            var hiddenInputTwo = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'payment_method');
            hiddenInput.setAttribute('value', payment_method);
            hiddenInputTwo.setAttribute('type', 'hidden');
            hiddenInputTwo.setAttribute('name', 'project_id');
            hiddenInputTwo.setAttribute('value', $('#subscribe-form input[id=project_id]').val());
            form.appendChild(hiddenInput);
            form.appendChild(hiddenInputTwo);
            form.submit();
        }
    </script>
@endsection
