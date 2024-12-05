@extends('layouts.app')

@section('page-title')
    Create Project
@endsection

@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
           <div class="container">
                <div class="row">
                    <livewire:comment-form :project="$project" :department="$department"  :users="$users"  />
                    <livewire:comments :project="$project" :department="$department" />
                </div>
            </div>
        </div>
@endsection
