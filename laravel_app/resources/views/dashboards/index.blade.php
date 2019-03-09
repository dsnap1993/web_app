@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">dashboard</div>

                <div class="card-body">
                Dear {{ Session::get('name') }}, welcome to our web app!<br>
                @foreach ($array as $data)
                    {{ $data }}<br>
                @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection