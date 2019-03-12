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
                                        <input type="checkbox">
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
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modify" data-href="{{ action('DashboardController@showUpdate') }}">
                            Modify 
                        </button>

                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#delete" data-href="{{ action('DashboardController@showDelete') }}">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal of Deleting -->
    <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header" id="deleteModalHeader">
                <h5 class="modal-title" id="deleteModalLabel">Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cancel">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this data?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Delete</button>
            </div>
            </div>
        </div>
    </div>
</div>
@endsection