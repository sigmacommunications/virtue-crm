@extends('layouts.app')

@section('page-title')
Create Project
@endsection

@section('content')
 <div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="content-wrapper">
         <section class="content-header">
          <h1>Create Project</h1>
        </section>
        <section class="content">
          <form name="formAdd" id="formAdd" method="POST" action="{{route('project.store')}}" enctype="multipart/form-data">
            @csrf
            <div class="box box-primary">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group {{ $errors->has('name') ? 'has-error' : null }}">
                            <label for="Project">Project Name <span class="text text-red">*</span></label>
                            <input type="text" name="name" class="form-control" id="project" placeholder="Project">
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="form-group {{ $errors->has('start_date') ? 'has-error' : null }}">
                            <label for="Project">Start Date <span class="text text-red">*</span></label>
                            <input type="date" name="start_date" class="form-control" id="project" placeholder="">
                        </div>
                    </div>

                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="form-group {{ $errors->has('deadline') ? 'has-error' : null }}">
                            <label for="Project">Deadline <span class="text text-red">*</span></label>
                            <input type="date" name="deadline" class="form-control" id="project" placeholder="">
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="form-group  {{ $errors->has('client_id') ? 'has-error' : null }}">
                            <label for="Project">Client <span class="text text-red">*</span></label>
                            <select name="client_id" style="width:100%" id="assign_client" required class="form-control js-example-basic-single">
                                <option value="">--select client--</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6">
                        <div class="form-group {{ $errors->has('status') ? 'has-error' : null }}">
                            <label for="Project">Status <span class="text text-red">*</span></label>
                            <select name="status" style="width:100%" id="status" required class="form-control">
                                <option>--select status--</option>
                                @foreach ($projectstatus as $ps)
                                <option value="{{ $ps->id }}">{{ $ps->name }}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group {{ $errors->has('departments') ? 'has-error' : null }}">
                            <label for="departments">Departments<span class="text text-red">*</span></label>
                            <select name="departments[]" id="departments" class="js-example-basic-multiple w-100 form-control"  multiple>
                            <option  value="">--Select Departments--</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->departments }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group {{ $errors->has('Users') ? 'has-error' : null }}">
                            <label for="users">Users<span class="text text-red">*</span></label>
                            <select name="users[]" id="users" class="js-example-basic-multiple w-100 form-control" multiple>
                            <option  value="">--Select Resources--</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} -- {{ $user->email }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-grouap {{ $errors->has('description') ? 'has-error' : null }}">
                            <label style="margin-top: 10px;" for="Project">Description <span class="text text-red">*</span></label>
                            <textarea  name="description" class="form-control summernoteExample" id="summernoteExample"  rows="6"></textarea>
                        </div>
                    </div>
                </div>
              </div>
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
