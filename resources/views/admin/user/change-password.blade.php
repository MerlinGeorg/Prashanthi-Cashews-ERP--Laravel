@extends('layouts/contentLayoutMaster')

@section('title', 'Change Password')

@section('vendor-style')
    {{-- Vendor Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/wizard/bs-stepper.min.css')) }}">
@endsection

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-wizard.css')) }}">
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Staff - <strong>{{ $staff->name }}</strong></h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="change-password-form" method="POST" action="{{ route('admin.staff.password.submit') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="slug" value="{{ $staff->slug }}">
                        <div class="row col-12">
                            <h5 class="mb-1">
                                <i data-feather="lock" class="font-medium-4 mr-25"></i>
                                <span class="align-middle">Change Password </span>
                            </h5>
                        </div>
                        <div class="row">

                            <div class="form-group form-password-toggle col-md-6">
                                <label class="form-label" for="password">Password</label>
                                <input type="password" name="password" id="password" class="form-control"
                                    placeholder="Password" />
                                <div class="invalid-feedback">Please enter password</div>
                            </div>
                            <div class="form-group form-password-toggle col-md-6">
                                <label class="form-label" for="confirm_password">Confirm Password</label>
                                <input type="password" name="confirm_password" class="form-control"
                                    placeholder="Confirm Password" />
                                <div id="password-required" class="invalid-feedback">Please enter confirm password</div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 text-center mt-2">
                            <a href="{{ route('admin.staff') }}"><button
                                    class="dt-button buttons-collection btn btn-outline-secondary me-2" tabindex="0"
                                    aria-controls="DataTables_Table_0" type="button"
                                    aria-haspopup="true"><span>Back</span></button></a>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('vendor-script')
    {{-- Vendor js files --}}
    <script src="{{ asset(mix('vendors/js/forms/wizard/bs-stepper.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
@endsection

@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/forms/form-validation.js')) }}"></script>
    <script>
        $(document).ready(function() {
            $("#change-password-form").validate({
                rules: {
                    'password': {
                        required: true
                    },
                    'confirm_password': {
                        required: true,
                        equalTo: '#password'
                    }
                },
                messages: {

                    password: "Please enter password",
                    confirm_password: {
                        required: "Please enter confirm password",
                        confirm_password: "Confirm password not match"
                    }
                }
            });
        });
    </script>
@endsection
