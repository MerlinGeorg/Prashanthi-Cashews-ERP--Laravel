@php
$configData = Helper::applClasses();
@endphp
@extends('layouts/auth')

@section('title', 'Reset Password')

@section('page-style')
  {{-- Page Css files --}}
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/base/app/authentication.css')) }}">
@endsection

@section('content')
<div class="d-flex col-lg-4 align-items-center auth-bg px-2 p-lg-5">
  <div class="col-12 col-sm-8 col-md-6 col-lg-12 px-xl-2 mx-auto">
    <h4 class="card-title mb-1">Reset Password 🔒</h4>
    <p class="card-text mb-2">Your new password must be different from previously used passwords</p>

    <form class="auth-reset-password-form mt-2" method="POST" action="{{ route('admin.reset.submit') }}">
      @csrf
      <input type="hidden" name="token" value="{{ $token }}">

      <div class="form-group mb-1">
        <label for="email" class="form-label">Email</label>
        <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Email" aria-describedby="email" tabindex="1" autofocus value="{{ $email ?? old('email') }}" />
          @if (@session('error'))
          <span class="error" role="alert">
            <strong>{{ @session('error') }}</strong>
          </span>
        @endif
      </div>

      <div class="form-group mb-1">
        <div class="d-flex justify-content-between">
          <label for="password" class="form-label">New Password</label>
        </div>
        <div class="input-group input-group-merge form-password-toggle @error('password') is-invalid @enderror">
            <input class="form-control form-control-merge @error('password') is-invalid @enderror" id="reset-password-new" type="password" name="password" placeholder="············" aria-describedby="reset-password-new" autofocus="" tabindex="1" />
            <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
        </div>
        
        @error('password')
          <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
          </span>
        @enderror
      </div>
      <div class="form-group mb-1">
        <div class="d-flex justify-content-between">
          <label for="password_confirmation" class="form-label">Confirm Password</label>
        </div>
        <div class="input-group input-group-merge form-password-toggle">
            <input class="form-control form-control-merge" id="reset-password-confirm" type="password" name="password_confirmation" placeholder="············" aria-describedby="reset-password-confirm" tabindex="2" />
            <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
        </div>
      </div>
      <button type="submit" class="btn btn-primary btn-block" tabindex="4">Set New Password</button>
    </form>

    <p class="text-center mt-2">
      @if (Route::has('admin.login'))
      <a href="{{ route('admin.login') }}">
        <i data-feather="chevron-left"></i> Back to login
      </a>
      @endif
    </p>
  </div>
</div>
<!-- /Reset Password v1 -->
@endsection

@section('vendor-script')
<script src="{{asset(mix('vendors/js/forms/validation/jquery.validate.min.js'))}}"></script>
@endsection

@section('page-script')
<script src="{{asset(mix('js/scripts/app/auth/reset-password.js'))}}"></script>
@endsection
