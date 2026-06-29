@extends('admin.common.layout')
@section('title', 'Dashboard')
@section('module_title', 'Dashboard')

@section('content')
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="{{ Helper::adminCardClass() }}">
                    <div class="card-header">
                        <h5 class="m-0">Update Profile</h5>
                    </div>
                    <div class="card-body"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection