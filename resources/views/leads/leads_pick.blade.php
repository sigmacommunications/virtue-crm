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
                <h1>Leads Pick</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">Leads Pick</li>
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
                    <h3 class="card-title">Leads Pick List</h3>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body">
                    <table id="order-listing" class="table dataTable no-footer" role="grid" aria-describedby="order-listing_info">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>users</th>
                          <th>Leads</th>
                          <th>Phone</th>
                          <th>Comments</th>
                          <th>Status</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                              @php $i=0; @endphp
                              @foreach($lead_pick as $lead)
                                @php $i++; @endphp
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>{{ $lead->users->name }}</td>
                                    <td>{{ $lead->leads->name }}</td>
                                    <td>{{ $lead->leads->phone }}</td>
                                    @if(isset($lead) && $lead->status == 'pending')
                                      <td>{!! $lead->comment !!}</td>
                                      <td><span style="color:orange;font-weight:bold;font-size:17px">{{ $lead->status }}</span></td>
                                    @elseif($lead->status == 'rejected')
                                      <td>{!! $lead->comment !!}</td>
                                      <td><span style="color:red;font-weight:bold;font-size:17px">{{ $lead->status }}</span></td>
                                    @elseif($lead->status == 'accepted')
                                      <td>{!! $lead->comment !!}</td>
                                      <td><span style="color:green;font-weight:bold;font-size:17px">{{ $lead->status }}</span></td>
                                    @else
                                      <td>{!! $lead->comment !!}</td>
                                      <td><span style="color:#392C70;font-weight:bold;font-size:17px">{{ $lead->status }}</span></td>
                                    @endif
                                    <td>
                                    @if(isset($lead) && $lead->status != 'accepted')
                                      <a href="{{ route('invoices.client.add',$lead->lead_id) }}" class="btn btn-primary btn-sm btn-view">Make A Client</a>
                                      {{-- <a href="{{ route('invoices.create') }}" class="btn btn-secondary text-dark btn-sm btn-view">Invoice View</a> --}}
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
