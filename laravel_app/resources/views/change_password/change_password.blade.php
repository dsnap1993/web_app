@extends('layouts.top_bar')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header" id="card-header">Change Password</div>
                <div class="card-body">
                    <form id="edit-profile" method="PUT" action="{{ route('profile_update') }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label>Current Password</label><br>
                            <input type="password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>New Password</label><br>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Confirm Password</label><br>
                            <input type="password" name="confirm_passwd" class="form-control" required>
                        </div>
                        <button type="button" class="btn btn-secondary" onclick="history.back()">Cancel</button>
                        <input type="submit" class="btn btn-primary" value="Save changes">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection