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
                <h1>Debit Credit Transaction  </h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">Debit Credit</li>
                b</ol>
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
                    <form id="debitCreditForm" action="{{ route("transaction.store")}}" method="post">
                        @csrf
                        <div id="entries">
                               <div class="row entry mb-2 p-2 border">
                                <div class="col-md-2 mb-3">
                                    <label for="type" class="form-label">Account</label>
                                    <select class="form-select form-control" name="account[]" required>
                                        @foreach ($accounts as $acnt)
                                           <option value="{{$acnt->id}}">{{$acnt->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                    <div class="col-md-2 mb-3">
                                        <label for="type" class="form-label">Transaction Type</label>
                                        <select class="form-select form-control" name="type[]" required>
                                            <option value="debit">Debit</option>
                                            <option value="credit">Credit</option>
                                        </select>
                                    </div>
                                    <div class="col-md-5 mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control" name="description[]" rows="3" required></textarea>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label for="amount" class="form-label">Amount</label>
                                        <input type="number" class="form-control" name="amount[]" required>
                                    </div>

                                        <button type="button" class="btn btn-danger btn-sm h-25 mt-4 remove-entry">Remove</button>
                                </div>
                           </div>
                          <button type="button" class="btn btn-primary" id="addEntry">Add Entry</button>
                          <button type="submit" class="btn btn-primary">Submit</button>
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
    $(document).ready(function() {
      $('#addEntry').click(function() {
        $('#entries').append(`
        <div class="row entry mb-2 p-2 border">
                                <div class="col-md-2 mb-3">
                                    <label for="type" class="form-label">Account</label>
                                    <select class="form-select form-control" name="account[]" required>
                                        @foreach ($accounts as $acnt)
                                           <option value="{{$acnt->id}}">{{$acnt->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                    <div class="col-md-2 mb-3">
                                        <label for="type" class="form-label">Transaction Type</label>
                                        <select class="form-select form-control" name="type[]" required>
                                            <option value="debit">Debit</option>
                                            <option value="credit">Credit</option>
                                        </select>
                                    </div>
                                    <div class="col-md-5 mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control" name="description[]" rows="3" required></textarea>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label for="amount" class="form-label">Amount</label>
                                        <input type="number" class="form-control" name="amount[]">
                                    </div>

                                    <button type="button" class="btn btn-danger btn-sm h-25 mt-4 remove-entry">Remove</button>
                           </div>
        `);
      });

      $(document).on('click', '.remove-entry', function() {
        $(this).parent().remove();
      });
    });
  </script>
@endsection
