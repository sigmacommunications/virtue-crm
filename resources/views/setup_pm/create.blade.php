@extends('layouts.app')
@section('page-title')
    Create Departments
@endsection

@section('content')
    <style>
        .payment-fields {
            display: none;
            margin-top: 15px;
        }
    </style>
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="content-wrapper">
                    <section class="content-header">
                        <h1>Create Departments<small>All * field requireds</small></h1>
                    </section>
                    <section class="content">
                        <form name="formAdd" id="formAdd" method="POST" action="{{ route('setup-pm.store') }}"
                            enctype="multipart/form-data">
                            @csrf

                            <div class=" box-primary">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-grouap {{ $errors->has('title') ? 'has-error' : null }}">
                                            <label for="title">Title <span class="text text-red">*</span></label>
                                            <input type="text" name="title" class="form-control" id="title"
                                                placeholder="Title">
                                            <span class="text-danger">{{-- {{ $errors->first('full_name') }} --}}</span>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group my-2">
                                            <label for="paymentMethod">Select Payment Method:</label>
                                            <select id="paymentMethod" name="paymentMethod" class="form-control" required>
                                                <option value="">--Select--</option>
                                                <option value="paypal">PayPal</option>
                                                <option value="stripe">Stripe</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div id="paypalFields" class="payment-fields row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group {{ $errors->has('paypalClientId') ? 'has-error' : null }}">
                                            <label for="paypalClientId">Client ID <span
                                                    class="text text-red">*</span></label>
                                            <input type="text" name="paypalClientId" class="form-control"
                                                id="paypalClientId" placeholder="PayPal Client ID">
                                            <span class="text-danger">{{ $errors->first('paypalClientId') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group {{ $errors->has('paypalSecret') ? 'has-error' : null }}">
                                            <label for="paypalSecret">Secret <span class="text text-red">*</span></label>
                                            <input type="password" name="paypalSecret" class="form-control"
                                                id="paypalSecret" placeholder="PayPal Secret">
                                            <span class="text-danger">{{ $errors->first('paypalSecret') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div id="stripeFields" class="payment-fields row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group {{ $errors->has('stripePublicKey') ? 'has-error' : null }}">
                                            <label for="stripePublicKey">Public Key <span
                                                    class="text text-red">*</span></label>
                                            <input type="text" name="stripePublicKey" class="form-control"
                                                id="stripePublicKey" placeholder="Stripe Public Key">
                                            <span class="text-danger">{{ $errors->first('stripePublicKey') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group {{ $errors->has('stripeSecretKey') ? 'has-error' : null }}">
                                            <label for="stripeSecretKey">Secret Key <span
                                                    class="text text-red">*</span></label>
                                            <input type="password" name="stripeSecretKey" class="form-control"
                                                id="stripeSecretKey" placeholder="Stripe Secret Key">
                                            <span class="text-danger">{{ $errors->first('stripeSecretKey') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>Company:</strong>
                                        <select name="company_id[]" multiple class="js-example-basic-multiple w-100"
                                            required>
                                            @foreach ($companies as $comp)
                                                <option value="{{ $comp->id }}">{{ $comp->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <button type="reset" onclick="window.location='{{ URL::previous() }}'"
                                    class="btn btn-danger">Cancel</button>
                            </div>
                </div>
                </form>
                </section>
            </div>
        </div>
    @endsection
    @section('scripts')
        <script>
            $(document).ready(function() {
                $('#paymentMethod').on('change', function() {
                    var paymentMethod = $(this).val();
                    // console.log(paymentMethod);

                    $('.payment-fields').hide();
                    $('.payment-fields input').prop('required', false);

                    if (paymentMethod == 'paypal') {
                        $('#paypalFields').show();
                        $('#paypalFields input').prop('required', true);
                    } else if (paymentMethod == 'stripe') {
                        $('#stripeFields').show();
                        $('#stripeFields input').prop('required', true);
                    }
                });
            });
        </script>
    @endsection
