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
                <h1>Attendance New Create</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">Attendance</li>
                </ol>
              </div>
            </div>
          </div><!-- /.container-fluid -->
        </section>
        <section class="content">
          <div class="container-fluid">
            @if (count($errors) > 0)
              <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <form method="post" class="" action="{{route('attendance.store')}}">
                      @csrf
                      <div class="row">
                        <div class="col-xs-4 col-sm-4 col-md-4">
                          <div class="form-group">
                          <strong>UserID</strong>
                          <select name="user_id" id="user_id" class="form-control">
                          @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                          @endforeach
                  </select>
                          </div>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4">
                          <div class="form-group">
                            <strong>Time In:</strong>
                            <input class="form-control" type="time" name="time_in" required>
                          </div>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4">
                          <div class="form-group">
                            <strong>Time Out:</strong>
                            <input class="form-control" type="time" name="time_out" required>
                          </div>
                        </div>
                        <!-- <div class="col-xs-4 col-sm-4 col-md-4">
                          <div class="form-group">
                            <strong>Status</strong>
                            <input class="form-control" type="status" name="status" required>
                          </div>
                        </div> -->

                        <!-- <div class="col-xs-4 col-sm-4 col-md-4">
                          <div class="form-group">
                            <strong>Status</strong>
                            <input class="form-control" type="status" name="status" required>
                          </div>
                        </div> -->
                        
                        <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="/attendance" class='btn btn-danger'>Cancel</a>
                      </div>
                        
                      </div>
                    </form>
                  </div>
                </div> 
              </div>   
            </div>
          </div>
        </section>
</div>
  </div>
</div>
@endsection