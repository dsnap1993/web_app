@extends('layouts.top_bar')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('packet capture') }}</div>

                <div class="card-body">
                </div>
                <div class="form-group row mb-0">
                    <div class="col-md-8 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Save') }} 
                        </button>

                        <a class="btn btn-primary" href="{{ route('dashboard') }}">
                            {{ __('Close without saving') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection