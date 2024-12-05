@extends('layouts.app')

@section('page-title')
Manage Department
@endsection

@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
        <div class="content-wrapper">
            <section class="content-header">
                <h1>Manage Department</h1>
            </section>
            <section class="content">
                <div class="box-body">
                    @if($paymentmethods)
                    <table id="dataTable" class="table table-bordered">
                        <thead style="background-color: #F8F8F8;">
                            <tr>
                                <th width="30%">Name</th>
                                <th width="10%">Type</th>
                                <th width="10%">Status</th>
                                <th width="20%">Manage</th>
                            </tr>
                        </thead>
                        @foreach($paymentmethods as $pm)
                            <tr>
                                <td>{{ $pm->title }}</td>
                                <td>{{ $pm->mechant_company}}</td>
                                <td>
                                    <form method="get" action="{{route('setup-pm.show',$pm->id)}}">
                                        @if($pm->status == 'Active')
                                        <input type="text" name="status" hidden value="Deactive">
                                        <button class="btn btn-info btn-sm"><i class="fa fa-thumbs-up"></i></button>
                                        @else
                                        <input type="text" name="status" hidden value="Active">
                                        <button class="btn btn-danger btn-sm"><i class="fa fa-thumbs-down"></i></button>
                                        @endif
                                    </form>
                                </td>
                                <td>
                                    <form method="post" action="{{route('setup-pm.destroy',$pm->id)  }}">
                                        {{ csrf_field() }}
                                        {{ method_field('delete') }}
                                        <a href="{{route('setup-pm.edit', $pm->id) }}" class="btn btn-primary btn-flat btn-sm"> <i class="fa fa-edit"></i></a>
                                        <button type="submit" onclick="return confirm('Are you sure want to delete this?')" class="btn btn-danger btn-flat btn-sm"> <i class="fa fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                    @else
                    <div class="alert alert-danger">
                        No record found!
                    </div>
                    @endif
                </div>
                <div class="box-footer clearfix">
                    <div class="row">
                        <div class="col-sm-6 text-right"></div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
