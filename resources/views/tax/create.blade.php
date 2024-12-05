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
                        <h1>Create Tax <small>All * field requireds</small></h1>
                    </section>
                    <section class="content">
                        <form name="formAdd" id="formAdd" method="POST" action="{{ route('tax.store') }}"
                            enctype="multipart/form-data">
                            @csrf

                            <div class=" box-primary">
                                <div class="row mb-3">
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-grouap {{ $errors->has('name') ? 'has-error' : null }}">
                                            <label for="title">Title <span class="text text-red">*</span></label>
                                            <input type="text" name="name" class="form-control" id="name"
                                                placeholder="Tax Name">
                                            <span class="text-danger">{{-- {{ $errors->first('full_name') }} --}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-grouap {{ $errors->has('title') ? 'has-error' : null }}">
                                            <label for="title">Percentage <span class="text text-red">*</span></label>
                                            <input type="number" name="percentage" class="form-control" id="Percentage"
                                                placeholder="Percentage">
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
        @endsection
