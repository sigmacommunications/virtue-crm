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
                <h1>Manage Taxes</h1>
            </section>
            <section class="content">
                <div class="box-body">
                   <div class="pull-right">
                        @can('tax-create')
                            <a class="btn btn-primary" style="margin-bottom:5px" href="{{ route('tax.create') }}"> + Add Tax</a>
                        @endcan
                    </div>
                    @if($taxes)
                    <table id="dataTable" class="table table-bordered">
                        <thead style="background-color: #F8F8F8;">
                            <tr>
                                <th width="30%">S.No#</th>
                                <th width="30%">Name</th>
                                <th width="10%">Percentage</th>
                                <th width="20%">Manage</th>
                            </tr>
                        </thead>
                        @foreach($taxes as $tax)
                            <tr>
                                <td>{{ $tax->id }}</td>
                                <td>{{ $tax->name }}</td>
                                <td>{{ $tax->percentage}} %</td>
                                <td>
                                    <form method="post" action="{{route('tax.destroy',$tax->id)  }}">
                                        {{ csrf_field() }}
                                        {{ method_field('delete') }}
                                        <a href="{{route('tax.edit', $tax->id) }}" class="btn btn-primary btn-flat btn-sm"> <i class="fa fa-edit"></i></a>
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
