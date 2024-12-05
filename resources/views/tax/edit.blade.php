@extends('layouts.app')
@section('page-title')
    Edit Payment Method
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
                        <h1>Edit Payment Method<small>All * field requireds</small></h1>
                    </section>
                    <section class="content">
                        {{-- @dd($paymentMethod) --}}
                        <form name="formEdit" id="formEdit" method="POST"
                            action="{{ route('tax.update', ['tax' => $tax->id]) }}"
                            enctype="multipart/form-data">

                            @csrf
                            @method('PUT')
                            <div class=" box-primary">
                                <div class="row mb-3">
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-grouap {{ $errors->has('name') ? 'has-error' : null }}">
                                            <label for="title">Title <span class="text text-red">*</span></label>
                                            <input type="text" name="name" class="form-control" id="name"
                                                placeholder="Tax Name" value="{{$tax->name}}">
                                            <span class="text-danger">{{-- {{ $errors->first('full_name') }} --}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-grouap {{ $errors->has('title') ? 'has-error' : null }}">
                                            <label for="title">Percentage <span class="text text-red">*</span></label>
                                            <input type="number" name="percentage" class="form-control" id="Percentage"
                                                placeholder="Percentage" value="{{$tax->percentage}}">
                                            <span class="text-danger">{{-- {{ $errors->first('full_name') }} --}}</span>
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
        </div>
    @endsection

    @section('scripts')
        <script>
            $(document).ready(function() {
                var paymentMethod = $('#paymentMethod').val();
                togglePaymentFields(paymentMethod);

                $('#paymentMethod').on('change', function() {
                    paymentMethod = $(this).val();
                    togglePaymentFields(paymentMethod);
                });

                function togglePaymentFields(method) {
                    $('.payment-fields').hide();
                    $('.payment-fields input').prop('required', false);

                    if (method === 'paypal') {
                        $('#paypalFields').show();
                        $('#paypalFields input').prop('required', true);
                    } else if (method === 'stripe') {
                        $('#stripeFields').show();
                        $('#stripeFields input').prop('required', true);
                    }
                }
            });
        </script>
    @endsection
