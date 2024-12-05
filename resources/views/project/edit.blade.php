@extends('layouts.app')

@section('page-title')
    Edit Project
@endsection

@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="content-wrapper">
                    <section class="content-header">
                        <h1>Edit Project</h1>
                    </section>
                    <section class="content">
                        <form name="formAdd" id="formAdd" method="POST" action="{{ route('project.update', $project->id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <input type="hidden" value="{{$project->id}}" name="project_id" />
                            <div class="box box-primary">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group {{ $errors->has('name') ? 'has-error' : null }}">
                                            <label for="Project">Project Name <span class="text text-red">*</span></label>
                                            <input type="text" name="name" value="{{ $project->name }}"
                                                class="form-control" id="project" placeholder="Project">
                                        </div>
                                    </div>


                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-group {{ $errors->has('start_date') ? 'has-error' : null }}">
                                            <label for="Project">Start Date <span class="text text-red">*</span></label>
                                            <input type="date" name="start_date" value="{{ $project->start_date }}"
                                                class="form-control" id="project" placeholder="">
                                        </div>
                                    </div>

                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-group {{ $errors->has('deadline') ? 'has-error' : null }}">
                                            <label for="Project">Deadline <span class="text text-red">*</span></label>
                                            <input type="date" name="deadline" value="{{ $project->deadline }}"
                                                class="form-control" id="project" placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-group {{ $errors->has('client_id') ? 'has-error' : null }}">
                                            <label for="Project">Client <span class="text text-red">*</span></label>
                                            <select name="client_id" style="width:100%" required class="form-control">
                                                <option>::select client::</option>
                                                @foreach ($clients as $row)
                                                    <option value="{{ $row->id }}"
                                                        {{ $row->id == $project->client_id ? 'selected' : null }}>
                                                        {{ $row->company_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-group {{ $errors->has('status') ? 'has-error' : null }}">
                                            <label for="Project">Status <span class="text text-red">*</span></label>
                                            <select name="status" style="width:100%" id="status" required
                                                class="form-control">
                                                <option>--select status--</option>
                                                @foreach ($projectstatus as $ps)
                                                    <option value="{{ $ps->id }}"
                                                        {{ $project->status == $ps->id ? 'selected' : '' }}>
                                                        {{ $ps->name }}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                    {{-- @dd($project->ProjectDepartments) --}}
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group {{ $errors->has('departments') ? 'has-error' : null }}">
                                            <label for="departments">Departments<span class="text text-red">*</span></label>
                                            <select name="departments[]" id="departments"
                                                class="js-example-basic-multiple w-100 form-control" multiple>
                                                <option value="">--Select Departments--</option>
                                                @foreach ($departments as $department)
                                                    <option value="{{ $department->id }}"
                                                        {{ $project->ProjectDepartments->contains('department_id', $department->id) ? 'selected' : '' }}>
                                                        {{ $department->departments }}
                                                    </option>
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
                                                    <option value="{{ $user->id }}"
                                                    {{ $project->Projectusers->contains('user_id', $user->id) ? 'selected' : '' }}
                                                    >{{ $user->name }} -- {{ $user->email }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-grouap {{ $errors->has('description') ? 'has-error' : null }}">
                                            <label style="margin-top: 10px;" for="Project">Description <span
                                                    class="text text-red">*</span></label>
                                            <textarea name="description" class="form-control summernoteExample" id="summernoteExample" rows="6">{{ $project->description }}</textarea>
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
