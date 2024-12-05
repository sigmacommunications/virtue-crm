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
                <h1>Clients Management</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">Client Management</li>
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
                    <h3 class="card-title">Clients List</h3>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body">
                    <div class="pull-right">
                      @can('client-create')
                        <a class="btn btn-primary" style="margin-bottom:5px" href="{{ route('client.create') }}"> + Add Client</a>
                      @endcan
                    </div>
                    <!-- <table id="example1" class="table table-bordered table-striped"> -->
                    <table id="order-listing" class="table dataTable no-footer" role="grid" aria-describedby="order-listing_info">
                      <thead>
                        <tr>
                          <th>S.No</th>
                          <th>Client Name</th>
                          <th>Client Company</th>
                          <th>Details</th>
                          @if (Auth::user()->hasPermissionTo('user-edit') || Auth::user()->hasPermissionTo('user-delete'))
                          <th>Action</th>
                          @endif
                        </tr>
                      </thead>
                      <tbody>
                        @if($clients)
                        @php
                        $id =1;
                        @endphp
                        @foreach($clients as $key => $client)
                        <tr>
                          <td>{{$id++}}</td>
                          <td>{{ $client->first_name }} - {{ $client->last_name }}</td>
                          <td>{{ $client->company_name }}</td>
                          <td><ul>
                            <li><b>Email: </b> {{$client->email}}</li>
                            <li><b>Client Category: </b> {{$client->category}}</li>
                            <li><b>Phone: </b>  {{$client->phone}}</li>
                            <li><b>Detail: </b>  {{$client->description}}</li>
                          
                          </ul></td>
                        
                          <td>
                            <div class="btn-group">
                              @can('user-edit')
                              <a class="btn btn-primary btn-a" href="{{ route('client.edit',$client->id) }}">Edit</a>
                              @endcan
                              @can('user-delete')
                              <form method="post" action="{{route('client.destroy',$client->id)}}">
                                @csrf
                                @method('delete')
                                <button type="submit" onclick="return confirm('Are You Sure Want To Delete This.??')" type="button" class="btn btn-danger btn-b"><i class="fa fa-trash"></i></button>
                              </form>
                              @endcan
                            </div>
                          </td>
                        </tr>
                        @endforeach
                        @endif
                      </tbody>
                    </table>
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
