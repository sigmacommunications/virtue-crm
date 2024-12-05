@extends('layouts.app')


@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1>Project Management</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">Project Management</li>
                </ol>
              </div>
            </div>
          </div><!-- /.container-fluid -->
        </section>
        <section class="content">
          <div class="container-fluid">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">Project Assign</h3>
                  </div>
                  <!-- /.card-header -->
                  <form action="{{ route("project-assign-update") }}" method="POST">
                    @csrf
                  <input type="hidden" value="{{$project->id}}" name="project_id" />
                  <div class="card-body">
                         <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group {{ $errors->has('Users') ? 'has-error' : null }}">
                                <label for="users">Users<span class="text text-red">*</span></label>
                                <select name="users[]" id="users" class="js-example-basic-multiple w-100 form-control" multiple>
                                <option  value="">--Select Resources--</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                        {{ $project->Projectusers->contains('user_id', $user->id) ? 'selected' : '' }}
                                        >{{ $user->name }} -- {{ $user->email }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="box-footer pull-left">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="reset" onclick="window.location='{{ URL::previous() }}'"
                                class="btn btn-danger">Cancel</button>
                        </div>
                        {{-- <input type="text" multiple /> --}}
                  </div>

                </form>

                </div>
              </div>
            </div>
          </div>
        </section>
    </div>
  </div>
</div>
@endsection
