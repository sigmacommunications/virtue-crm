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
                <h1>Report By Company Sales</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">Report By Company Sales</li>
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
                    <h3 class="card-title">Report By Company Sales</h3>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-5">
                            <label for="companies-report-dropdown">Company:</label>
                            <select id="companies-report-dropdown" class="form-control">
                                <option value="">All</option>
                                @foreach ($companies as $comp)
                                    <option value="{{$comp->id}}">{{$comp->title}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label for="companies-report-date">Date:</label>
                                <input type="date" id="companies-report-date" class="form-control" name="date"/>
                        </div>
                        <div class="col-md-2 mt-3">
                            <button type="button" id="company_report_filter" class="btn btn-lg btn-primary">Filter</button>
                        </div>
                    </div>


                    <table id="companies_report_table" class="table dataTable" role="grid" aria-describedby="order-listing_info">
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


