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
                <h1>Edit Account</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">Coa</li>
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
                    <form method="post" class="" action="{{route('coa.update', $coa->id)}}">
                      @csrf
                      @method('PUT')
                      <div class="row">
                         <div class="col-xs-6 col-sm-6 col-md-6">
                          <div class="form-group">
                            <strong>Title</strong>
                            <input class="form-control"  name="title" value="{{ $coa->title }}" required>
                          </div>
                        </div>
                        <div class="col-xs-3 col-sm-3 col-md-3">
                            <div class="form-group">
                              <strong>Nature</strong>
                              <select name="nature" class="form-control">
                                <option value="100" {{ $coa->nature == '100' ? 'selected' : '' }}>Capital</option>
                                <option value="200" {{ $coa->nature == '200' ? 'selected' : '' }}>Assets</option>
                                <option value="300" {{ $coa->nature == '300' ? 'selected' : '' }}>Liabilities</option>
                                <option value="400" {{ $coa->nature == '400' ? 'selected' : '' }}>Expense</option>
                                <option value="500" {{ $coa->nature == '500' ? 'selected' : '' }}>Income</option>
                              </select>
                            </div>
                        </div>

                        <div class="col-xs-2 col-sm-2 col-md-2 ml-1 p-4">
                            <div class="form-check">
                                <input class="form-check-input" name="is_header" type="checkbox" value="true" id="" {{ ($coa->parent_id == 0) ? 'checked' : '' }}/>
                                <label class="form-check-label" for=""> is Header</label>
                            </div>
                        </div>
                      </div>
                      <div class="row" id="conditional_fields">
                        <div class="col-xs-6 col-sm-4 col-md-6">
                            <div class="form-group">
                                <strong>Parent Account</strong>
                                <select name="parent_id" class="form-control">
                                    @foreach($accounts as $acnt )
                                    <option value="{{$acnt->id}}" {{ $coa->parent_id == $acnt->id ? 'selected' : '' }}>{{$acnt->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-4 col-md-6">
                            <div class="form-group">
                                <strong>Opening balance</strong>
                                <input class="form-control" type="number"  name="opening_balance" value="{{ $coa->opening_balance }}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                              <strong>Description</strong>
                              <textarea class="form-control h-100" rows="8" name="description" >{{ $coa->description }}</textarea>
                            </div>
                          </div>
                        <!-- <div class="col-xs-4 col-sm-4 col-md-4">
                          <div class="form-group">
                            <strong>Status</strong>
                            <input class="form-control" type="status" name="status" required>
                          </div>
                        </div> -->
                      </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="/attendance" class='btn btn-danger'>Cancel</a>
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
<script>

    $("input[name='is_header']").on("change",function(){
        if($(this).prop("checked") == true){
            $("#conditional_fields").hide();
        }
        else
        {
            $("#conditional_fields").show();
        }
    })
    $("input[name='is_header']").trigger("change");
</script>
@endsection
