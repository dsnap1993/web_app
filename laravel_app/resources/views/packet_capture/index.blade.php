@extends('layouts.top_bar')
@section('content')
<div class="container">
    <div class="row justify-content-center">
            <table class="table">
                <thead>
                    <tr class="data-table-title">
                        <th scope="col-xl-12">No</th>
                        <th scope="col-xl-12">Time</th>
                        <th scope="col-xl-12">Source</th>
                        <th scope="col-xl-12">Destination</th>
                        <th scope="col-xl-12">Protocol</th>
                        <th scope="col-xl-12">Length</th>
                        <th scope="col-xl-12">Info</th>
                    </tr>
                </thead>
                <?php /*@foreach ($array as $data)
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
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                @endforeach*/?>
            </table>
                <a class="btn btn-primary" href="{{ route('dashboard') }}">
                        {{ __('Back to Dashboard') }}
                </a>
            </div>
            <div class="form-group row mb-0">
                <div class="col-md-8 offset-md-4"></div>
            </div>
    </div>
</div>
@endsection

