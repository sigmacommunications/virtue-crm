@extends('layouts.app')

@section('page-title')
Create Client
@endsection

@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="content-wrapper">
         <section class="content-header">
          <h1>Create Client</h1>
        </section>
        <section class="content">
          <form name="formAdd" id="formAdd" method="POST" action="{{route('client.store')}}" enctype="multipart/form-data">
            @csrf
            <div class="box box-primary">
                <div class="row">
                    <div class="col-xs-2 col-sm-2 col-md-2 mb-2">
                        <div class="form-grouap {{ $errors->has('company_name') ? 'has-error' : null }}">
                            <label for="Project">Company <span class="text text-red">*</span></label>
                            <select class="w-100 js-example-basic-single" name="company_id">
                                @foreach ($companies as $company)
                                    
                                <option value="{{$company->id}}">{{$company->title}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-2 col-sm-2 col-md-2 mb-2">
                        <div class="form-grouap {{ $errors->has('company_name') ? 'has-error' : null }}">
                            <label for="Project">Customer Type <span class="text text-red">*</span></label>
                            <select class="w-100 js-example-basic-single" name="category">
                                <option value="Bussiness">Bussiness</option>
                                <option value="Individual">Individual</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-2 col-sm-2 col-md-2">
                        <div class="form-group ">
                            <label for="salutation">Salutation</label>
                            <select class=" js-example-basic-single w-100" id="salutation" name="salutation">
                                <option selected>Salutation</option>
                                <option value="1">Mr.</option>
                                <option value="2">Ms.</option>
                                <option value="3">Mrs.</option>
                                <option value="4">Dr.</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-5 col-sm-5 col-md-5">
                        <div class="form-group {{ $errors->has('first_name') ? 'has-error' : null }}">
                            <label for="Project">First Name <span class="text text-red">*</span></label>
                            <input type="text" name="first_name" required class="form-control" id="title" placeholder="First Name">
                        </div>
                    </div>
                    <div class="col-xs-5 col-sm-5 col-md-5">
                        <div class="form-group {{ $errors->has('last_name') ? 'has-error' : null }}">
                            <label for="Project">Last Name <span class="text text-red">*</span></label>
                            <input type="text" name="last_name" required class="form-control" id="title" placeholder="Last Name">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group {{ $errors->has('company_name') ? 'has-error' : null }}">
                            <label for="Project">Company Name <span class="text text-red">*</span></label>
                            <input type="text" name="company_name" required class="form-control" id="project" placeholder="Company Name">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group {{ $errors->has('currency') ? 'has-error' : null }}">
                            <label for="currency">Currency</label>
                            <select class="form-control w-100" id="currency" name="currency">
                                <option selected value="USD">USD - United States Dollar</option>
                                <option value="EUR">EUR - Euro</option>
                                <option value="GBP">GBP - British Pound</option>
                                <option value="JPY">JPY - Japanese Yen</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group {{ $errors->has('email') ? 'has-error' : null }}">
                            <label for="Project">Email <span class="text text-red">*</span></label>
                            <input type="email" name="email" required class="form-control" id="title" placeholder="Email">
                        </div>
                    </div>
                <div class="col-xs-6 col-sm-6 col-md-6">
                    <div class="form-group {{ $errors->has('password') ? 'has-error' : null }}">
                        <label for="Project">Phone <span class="text text-red">*</span></label>
                        <input type="number" name="phone" required class="form-control" id="title" placeholder="">
                    </div>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6">
                    <div class="form-group {{ $errors->has('password') ? 'has-error' : null }}">
                        <label for="Project">Telephone <span class="text text-red">*</span></label>
                        <input type="number" name="telephone" required class="form-control" id="title" placeholder="">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group {{ $errors->has('description') ? 'has-error' : null }}">
                        <label for="Project">Description  <span class="text text-red">*</span></label>
                        <textarea  name="description" class="form-control summernoteExample" id="summernoteExample"  rows="6"></textarea>
                    </div>
                </div>
              </div> </div>
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
                <button type="reset" onclick="window.location='{{ URL::previous() }}'" class="btn btn-danger">Cancel</button>
              </div>
            </div>
          </form>
        </section>
        </div>
    </div>

  @endsection
