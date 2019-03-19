@extends('layouts.top_bar')
@section('content')
<div class="container">
    <div class="row justify-content-center" id="list">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ Session::get('name') }}'s profile</div>
                <div class="card-body">
                    <form id="edit-profile" method="PUT" action="{{ route('profile_update') }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label>Name</label><br>
                            <input type="text" name="name" id="name" value="{{ $data['name'] }}" required>
                        </div>
                        <div class="form-group">
                            <label>Email Address</label><br>
                            <input type="text" name="email" id="email" value="{{ $data['email'] }}" required>
                        </div>
                        <button type="button" class="btn btn-secondary" href="{{ route('dashboard') }}">Cancel</button>
                        <input type="submit" class="btn btn-primary" value="Save changes">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection