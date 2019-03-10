@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">dashboard</div>

                <div class="card-body">
                    Dear {{ Session::get('name') }}, welcome to our web app!<br>
                    <table class="data-table">
                        <tr class="data-table-title">
                            <th>Data Name</th>
                            <th>Data Summary</th>
                            <th>Created At</th>
                        </tr>
                        @foreach ($array as $data)
                            <tr>
                                <th>{{ $data['data_name'] }}</th>
                                <th>{{ $data['data_summary'] }}</th>
                                <th>{{ $data['created_at'] }}</th>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection