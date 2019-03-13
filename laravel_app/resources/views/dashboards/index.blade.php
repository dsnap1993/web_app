@extends('layouts.top_bar')
@section('content')
<div class="container">
    <div class="row justify-content-center" id="list">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ Session::get('name') }}'s dashboard</div>

                <div class="card-body">
                    <table class="data-table">
                        @if (count($array) >= 1)
                            <tr class="data-table-title">
                                <th></th>
                                <th>Data Name</th>
                                <th>Data Summary</th>
                                <th>File Name</th>
                                <th>Created At</th>
                            </tr>
                            @foreach ($array as $data)
                                <tr>
                                    <th>
                                        <input type="checkbox" id="checkbox-dashboard" value="{{ $data['data_id'] }}">
                                    </th>
                                    <th>
                                        <a href="{{ route('packet_capture_index', $data['data_id']) }}">
                                            {{ $data['data_name'] }}
                                        </a>
                                    </th>
                                    <th>{{ $data['data_summary'] }}</th>
                                    <th>
                                        <a href="{{ route('packet_capture_index', $data['data_id']) }}">
                                            {{ $data['file_name'] }}
                                        </a>
                                    </th>
                                    <th>{{ $data['created_at'] }}</th>
                                </tr>
                            @endforeach
                        @else
                            There are no capture data. 
                        @endif
                    </table>
                </div>
                <div class="form-group row mb-0">
                    <div class="col-md-8 offset-md-4">
                        <a class="btn btn-primary" href="{{ route('packet_capture_new') }}">
                            Capture Packet
                        </a>
                        <button type="button" class="btn btn-primary" id="dashboard-modify">
                            Modify 
                        </button>
                        <button type="button" class="btn btn-primary" id="dashboard-delete">
                            Delete
                        </button>
                    </div>
                    @if (!empty($array))
                        <form id="form-dashboard-modify" method="get" action="{{ action('DashboardController@showUpdate') }}">
                            {{ csrf_field() }}
                           <input type="hidden" name="data_id" value="">
                        </form>
                        <form id="form-dashboard-delete" method="post" action="{{ action('DashboardController@showDelete') }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="data_id" value="">
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection