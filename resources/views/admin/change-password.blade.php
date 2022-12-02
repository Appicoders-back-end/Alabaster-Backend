@extends('admin.layouts.app')

@section('content')
    <!-- Start Page title and tab -->
    <div class="mt-4">
        <div class="container-fluid">
            <div class="card">
                <div class="row">
                    <div class="col-md-12 grid-margin stretch-card">
                        <div class="card-body">
                            <h4 class="text-dark font-weight-bold col-9">Change Password</h4>
                            <form method="post" action="{{route('admin.updatePassword')}}" class="forms-sample">
                                @csrf
                                <div class="mb-3">
                                    <label for="oldPassword" class="form-label">Old Password</label>
                                    <input type="password" name="old_password" class="form-control" id="oldPassword"
                                           autocomplete="off"
                                           placeholder="Old Password">
                                    @if (isset($errors) && $errors->has('old_password'))
                                        <p class="help-block m-1">
                                            <strong
                                                class="text-danger">{{ $errors->first('old_password') }}</strong>
                                        </p>
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label for="newPassword" class="form-label">New Password</label>
                                    <input type="password" name="new_password" class="form-control" id="newPassword"
                                           placeholder="New Password">
                                    @if (isset($errors) && $errors->has('new_password'))
                                        <p class="help-block m-1">
                                            <strong
                                                class="text-danger">{{ $errors->first('new_password') }}</strong>
                                        </p>
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label for="confirmNewPassword" class="form-label">Confirm New Password</label>
                                    <input type="password" name="confirm_new_password" class="form-control"
                                           id="confirmNewPassword" autocomplete="off"
                                           placeholder="Confirm New Password">
                                    @if (isset($errors) && $errors->has('confirm_new_password'))
                                        <p class="help-block m-1">
                                            <strong
                                                class="text-danger">{{ $errors->first('confirm_new_password') }}</strong>
                                        </p>
                                    @endif
                                </div>
                                <button type="submit" class="btn btn-dark me-2">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
