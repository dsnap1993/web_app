@extends('layouts.top_bar')
@section('content')
<div class="container">
    <div class="row justify-content-center">
            <div class="card">
                <div class="card-header">{{ Session::get('name') }}'s dashboard</div>
                <div class="card-body">
                    @if (count($array) >= 1)
                        <table class="table">
                            <thead>
                                <tr class="data-table-title">
                                    <th scope="col-xl-12">Data Name</th>
                                    <th scope="col-xl-12">Data Summary</th>
                                    <th scope="col-xl-12">File Name</th>
                                    <th scope="col-xl-12">Created At</th>
                                    <th scope="col-xl-12"></th>
                                </tr>
                            </thead>
                            @foreach ($array as $data)
                                <tbody>
                                    <tr class="data-row">
                                        <td class="data-name">
                                            <a href="{{ route('packet_capture_index', $data['data_id']) }}">{{ $data['data_name'] }}</a>
                                        </td>
                                        <td class="data-summary">{{ $data['data_summary'] }}</td>
                                        <td class="file-name">
                                            <a href="{{ route('packet_capture_index', $data['data_id']) }}">{{ $data['file_name'] }}</a>
                                        </td>
                                        <td>{{ $data['created_at'] }}</td>
                                        <td>
                                            <button type="button" class="btn btn-primary" id="edit-button" data-id="{{ $data['data_id'] }}">
                                                Edit
                                            </button>
                                            <button type="button" class="btn btn-primary" id="delete-button" data-id="{{ $data['data_id'] }}">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            @endforeach
                        </table>
                    @else
                        There are no capture data. 
                    @endif
                    <a class="btn btn-primary" href="{{ route('packet_capture_new') }}">
                        Packet Capture
                    </a>
                </div>
            </div>
    </div>
    <!-- Modal of Editing -->
    <div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header" id="editModalHeader">
                <h5 class="modal-title" id="editModalLabel">Edit Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cancel">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="edit-form" method="PUT" action="{{ route('dashboard_update') }}">
            <div class="modal-body">
                {{ csrf_field() }}
                <div class="form-group">
                    <label>Data Name</label>
                    <input type="text" name="data_name" id="modal-data-name">
                </div>
                <div class="form-group">
                    <label>Data Summary</label>
                    <input type="text" name="data_summary" id="modal-data-summary">
                </div>
                <div class="form-group">
                    <label>File Name</label>
                    <input type="text" name="file_name" id="modal-file-name">
                </div>
                <div class="form-group">
                    <input type="hidden" name="data_id" id="edit-data-id">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <input type="submit" class="btn btn-primary" value="Save changes">
            </div>
            </form>
            </div>
        </div>
    </div>
    <!-- Modal of Deleting -->
    <div class="modal fade" id="modal-delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header" id="deleteModalHeader">
                <h5 class="modal-title" id="deleteModalLabel">Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cancel">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="delete-form" method="DELETE" action="{{ route('dashboard_delete') }}">
            <div class="modal-body">
                Are you sure you want to delete this data?
                <div class="form-group">
                    <input type="hidden" name="data_id" id="delete-data-id">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <input type="submit" id="delete-button" class="btn btn-primary" value="Delete">
            </div>
            </form>
            </div>
        </div>
    </div>
</div>
@endsection