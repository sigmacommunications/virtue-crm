@extends('layouts.app')
@section('page-title')
Manage Leaves
@endsection
@section('content')
<div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Manage Leaves Type</h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- /.row -->
        <div class="box">

            <!-- /.box-header -->
            <!-- <div class="box-body table-responsive"> -->
                @if($leavetypes)
                <table id="order-listing" class="table dataTable no-footer" role="grid" aria-describedby="order-listing_info">
                    <thead>
                        <tr>
                            <th width="16%">id</th>
                            <th width="16%">Title</th>
                            <th width="16%">Action</th>
                        </tr>
                    </thead>
                    @foreach($leavetypes as $k => $leave)
                    <tr>
                        <td>{{ $k+1}}</td>
                        <td>{{ $leave->name }}</td>
                        {{-- <td><a href="leaves-type/{{ $leave->id }}/detail" class="btn btn-success btn-a btn-flat btn-sm"> <i class="fa fa-eye"></i></a></td> --}}
                        <td>
                            <form method="post" action="{{route('leaves-type.destroy',$leave->id)}}">
                                {{ csrf_field() }}
                                {{ method_field('delete') }}
                                <button type="submit" onclick="return confirm('Are you sure want to delete this?')" class="btn btn-danger btn-b btn-flat btn-sm"> <i class="fa fa-trash"></i></button>
                            </form>
                        </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer clearfix">
                        <div class="row">
                            {{-- <div class="col-sm-6">
                                <span style="display:block;font-size:15px;line-height:34px;margin:20px 0;">
                                    Showing {{($attendances->currentpage()-1)*$attendances->perpage()+1}} to {{$attendances->currentpage()*$attendances->perpage()}} of {{$attendances->total()}} entries
                                </span>
                            </div> --}}
                            <div class="col-sm-6 text-right">
                                {{-- {{ $attendances->links() }} --}}
                        </div>
                    </div>
                </div>
                @else
                <div class="alert alert-danger">
                    No record found!
                </div>
                @endif
                <!-- /.box-body -->
            </div>
        </section>
    </div>
    </div>
@endsection
