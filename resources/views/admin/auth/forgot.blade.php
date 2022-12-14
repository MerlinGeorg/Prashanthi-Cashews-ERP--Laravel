@php
$configData = Helper::applClasses();
@endphp
@extends('layouts/auth')

@section('title', 'Forgot Password')

@section('page-style')
  {{-- Page Css files --}}
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/base/app/authentication.css')) }}">
@endsection

@section('content')

    <!-- Forgot password-->
    <div class="d-flex col-lg-4 align-items-center auth-bg px-2 p-lg-5">
      <div class="col-12 col-sm-8 col-md-6 col-lg-12 px-xl-2 mx-auto">
        <h2 class="card-title fw-bold mb-1">Forgot Password? 🔒</h2>
        <p class="card-text mb-2">Enter your email and we'll send you instructions to reset your password</p>
        <form class="auth-forgot-password-form mt-2" method="POST" action="{{ route('admin.forgot.submit') }}">
        @csrf
        <div class="form-group">
          <label for="forgot-password-email" class="form-label">Email</label>
          <input type="email" class="form-control @error('email') is-invalid @enderror" id="forgot-password-email" name="email" value="{{ old('email') }}" placeholder="Email" aria-describedby="forgot-password-email" tabindex="1" autofocus />
            @error('email')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
          @enderror
        </div>
          @if (@session('error'))
          <span class="error">
            <p><strong>{{ @session('error') }}</strong></p>
          </span>
        @else
          <span class="error text-success">
            <p><strong>{{ @session('msg') }}</strong></p>
          </span>
        @endif
          <button class="btn btn-primary w-100" tabindex="2">Send reset link</button>
        </form>
        <p class="text-center mt-2">
          <a href="{{url('/admin/login')}}">
            <i data-feather="chevron-left"></i> Back to login
          </a>
        </p>
      </div>
    </div>
    <!-- /Forgot password-->
  
@endsection

@section('vendor-script')
<script src="{{asset(mix('vendors/js/forms/validation/jquery.validate.min.js'))}}"></script>
@endsection

@section('page-script')
<script src="{{ asset(mix('js/scripts/forms/form-validation.js')) }}"></script>
<script src="{{asset(mix('js/scripts/app/auth/forgot-password.js'))}}"></script>
@endsection
