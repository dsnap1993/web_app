@extends('layouts.top_bar')
@section('content')
<div class="container">
    <div class="row justify-content-center" id="list">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Change Password</div>
                <div class="card-body">
                    <form id="edit-profile" method="PUT" action="{{ route('profile_update') }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label>Current Password</label><br>
                            <input type="password">
                        </div>
                        <div class="form-group">
                            <label>New Password</label><br>
                            <input type="text" name="password">
                        </div>
                        <div class="form-group">
                            <label>Confirm Password</label><br>
                            <input type="text" name="confirm_passwd">
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