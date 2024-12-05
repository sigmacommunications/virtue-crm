@extends('layouts.app')


@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="content-wrapper">
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1>Invoice Management</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">Invoice Management</li>
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
                    <h3 class="card-title">Invoice List</h3>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body">
                    <div class="pull-right">
                      @can('invoice-create')
                        <a class="btn btn-primary" style="margin-bottom:5px" href="{{ route('invoices.create') }}"> + Add Invoice</a>
                      @endcan
                    </div>
                    <!-- <table id="example1" class="table table-bordered table-striped"> -->
                    <table id="order-listing" class="table dataTable no-footer" role="grid" aria-describedby="order-listing_info">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Invoice</th>
                          <th>Client Name</th>
                          <th>Amount</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                         @foreach ($invoices as $k => $inv)
                            <tr>
                                <td>{{++$k}}</td>
                                <td>{{$inv->invoice_id }}</td>
                                <td>{{$inv->client->company_name }}</td>
                                <td>{{$inv->total_amount }}</td>
                                @if ($inv->status == "inprocess")
                                    <td>
                                        <a href="{{ route('invoices.edit', $inv->id) }}" class="btn btn-primary btn-sm">Edit</a>

                                        <form action="{{ route('invoices.destroy', $inv->id) }}" method="post" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                        <a href="{{ route('invoices.show', $inv->id) }}" class="btn btn-secondary text-dark btn-sm">View</a>

                                    </td>
                                 @elseif($inv->status == "paid")
                                 <td>
                                    <h3><span
                                        class="badge bg-success "
                                        >PAID</span
                                     ></h3>
                                 </td>
                                 @else
                                @endif
                            </tr>
                         @endforeach
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


