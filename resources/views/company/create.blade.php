@extends('layouts.app')

@section('page-title')
    Create Companys
@endsection

@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="content-wrapper">
                    <section class="content-header">
                        <h1>Create Company</h1>
                    </section>
                    <section class="content">
                        <form name="formAdd" id="formAdd" method="POST" action="{{ route('company.store') }}"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="box box-primary">
                                <div class="row">
                                    <div class="col-xs-4 col-sm-4 col-md-4">
                                        <div class="form-grouap {{ $errors->has('title') ? 'has-error' : null }}">
                                            <label for="Companys">Title <span class="text text-danger">*</span></label>
                                            <input type="text" name="title" class="form-control" id="title"
                                                placeholder="Title">
                                            <span class="text-danger"> {{ $errors->first('title') }} </span>
                                        </div>
                                    </div>
                                    <div class="col-xs-4 col-sm-4 col-md-4">
                                        <div class="form-grouap {{ $errors->has('phone') ? 'has-error' : null }}">
                                            <label for="Companys">Phone <span class="text text-danger">*</span></label>
                                            <input type="text" name="phone" class="form-control" id="phone"
                                                placeholder="Phone">
                                            <span class="text-danger"> {{ $errors->first('phone') }} </span>
                                        </div>
                                    </div>
                                    <div class="col-xs-4 col-sm-4 col-md-4">
                                        <div class="form-grouap {{ $errors->has('is_default') ? 'has-error' : null }}">
                                            <label for="Companys">Default <span class="text text-danger">*</span></label>
                                            <select class=" form-control w-100" name="is_default">
                                                <option selected value="0">Disabled</option>
                                                <option value="1">Enabled</option>
                                            </select>
                                            <span class="text-danger"> {{ $errors->first('is_default') }} </span>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-grouap {{ $errors->has('description') ? 'has-error' : null }}">
                                            <label for="description">Description <span
                                                    class="text text-danger">*</span></label>
                                            <textarea name="description" id="description" class="form-control" cols="20" rows="20"></textarea>
                                            <span class="text-danger"> {{ $errors->first('description') }} </span>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-grouap {{ $errors->has('logo') ? 'has-error' : null }}">
                                            <label for="logo">logo <span class="text text-danger">*</span></label>
                                            <input type="file" name="logo" class="form-control" accept="image/*">
                                            <span class="text-danger"> {{ $errors->first('logo') }} </span>
                                        </div>
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
