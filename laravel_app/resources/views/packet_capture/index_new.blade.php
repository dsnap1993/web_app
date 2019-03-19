@extends('layouts.top_bar')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="card">
            <div class="card-header">packet data</div>
            <div class="card-body">
                <div class="title">{{ __('packet capture') }}</div>

                <button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#create-modal">
                    {{ __('Save') }} 
                </button>
                <a class="btn btn-primary" href="{{ route('dashboard') }}">
                    {{ __('Close without saving') }}
                </a>
                <div class="form-group row mb-0">
                    <div class="col-md-8 offset-md-4"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal of Editing -->
<div class="modal fade" id="create-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header" id="createModalHeader">
                <h5 class="modal-title" id="createModalLabel">Create a New Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cancel">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="create-form" method="POST" action="{{ route('capture_data_create') }}">
            <div class="modal-body">
                {{ csrf_field() }}
                <div class="form-group">
                    <label>Data Name</label>
                    <input type="text" name="data_name" required>
                </div>
                <div class="form-group">
                    <label>Data Summary</label>
                    <input type="text" name="data_summary" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <input type="submit" class="btn btn-primary" value="Save">
            </div>
            </form>
            </div>
        </div>
    </div>
@endsection
