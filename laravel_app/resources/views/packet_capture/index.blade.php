@extends('layouts.top_bar')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
                <div class="title">{{ __('packet capture') }}</div>

                <a class="btn btn-primary" href="{{ route('dashboard') }}">
                            {{ __('Back to Dashboard') }}
                        </a>
                </div>
                <div class="form-group row mb-0">
                    <div class="col-md-8 offset-md-4">
                    </div>
                </div>
        </div>
    </div>
</div>
@endsection

