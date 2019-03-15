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
                                <th>Data Name</th>
                                <th>Data Summary</th>
                                <th>File Name</th>
                                <th>Created At</th>
                                <th></th>
                            </tr>
                            @foreach ($array as $data)
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
                            @endforeach
                        @else
                            There are no capture data. 
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal of Editing -->
    <div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header" id="editModalHeader">
                <h5 class="modal-title" id="editModalLabel">Editing</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cancel">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="edit-form" method="PUT" action="{{ route('dashboard_update') }}">
            <div class="modal-body">
                {{ csrf_field() }}
                <div class="form-group">
                    <label>Data Name</label>
                    <input type="text" id="modal-data-name">
                </div>
                <div class="form-group">
                    <label>Data Summary</label>
                    <input type="text" id="modal-data-summary">
                </div>
                <div class="form-group">
                    <label>File Name</label>
                    <input type="text" id="modal-file-name">
                </div>
                <div class="form-group">
                    <input type="hidden" id="edit-data-id">
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
                    <input type="hidden" id="delete-data-id">
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