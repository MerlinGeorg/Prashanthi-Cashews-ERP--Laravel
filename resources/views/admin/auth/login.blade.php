@php
$configData = Helper::applClasses();
@endphp
@extends('layouts/auth')

@section('title', 'Login Page')

@section('page-style')
  {{-- Page Css files --}}
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/base/app/authentication.css')) }}">
@endsection

@section('content')
    <!-- Login-->
    <div class="d-flex col-lg-4 align-items-center auth-bg px-2 p-lg-5">
      <div class="col-12 col-sm-8 col-md-6 col-lg-12 px-xl-2 mx-auto">
        <h2 class="card-title fw-bold mb-1">Welcome to {{config('app.name')}}! 👋</h2>
        <p class="card-text mb-2">Please sign-in to your account </p>
        <form class="auth-login-form mt-2" action="{{url('admin/login')}}" method="POST">
            @csrf
            @if (@session('access_error'))
            <span class="error">
                <p><strong>{{ @session('access_error') }}</strong></p>
            </span>
            @endif
            <div class="mb-1">
                <label class="form-label" for="email_or_username">Email / Username</label>
                <input class="form-control" id="email_or_username" type="text" name="email_or_username" placeholder="Email or Username" value="{{old('email_or_username')}}" autofocus="" tabindex="1" />
                @if ($errors->has('email_or_username'))
                    <span class="error">
                        <p>{{ $errors->first('email_or_username') }}</p>
                    </span>
                @endif               
          
            </div>
            
        <div class="mb-1">
            <div class="d-flex justify-content-between">
              <label class="form-label" for="password">Password</label>
              <a href="{{url('admin/forgot')}}">
                <small>Forgot Password?</small>
              </a>
            </div>
            <div class="input-group input-group-merge form-password-toggle">
              <input class="form-control form-control-merge" id="password" type="password" name="password" placeholder="············" aria-describedby="login-password" tabindex="2" />
              <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
            </div>
            @if ($errors->has('password'))
            <span class="error">
              <p>{{ $errors->first('password') }}</p>
            </span>
          @endif
          @if (@session('error'))
            <span class="error">
              <p>{{ @session('error') }}</p>
            </span>
          @endif
          </div>
          <div class="mb-1">
            <div class="form-check">
              <input class="form-check-input" id="remember-me" type="checkbox" tabindex="3" />
              <label class="form-check-label" for="remember-me"> Remember Me</label>
            </div>
          </div>
          <button class="btn btn-primary w-100" tabindex="4">Sign in</button>
        </form>
        {{-- <p class="text-center mt-2">
          <span>New on our platform?</span>
          <a href="{{url('staff/register')}}"><span>&nbsp;Create an account</span></a>
        </p> --}}
        
      </div>
    </div>
    <!-- /Login-->
@endsection

@section('vendor-script')
<script src="{{asset(mix('vendors/js/forms/validation/jquery.validate.min.js'))}}"></script>
@endsection

@section('page-script')
<script src="{{ asset(mix('js/scripts/forms/form-validation.js')) }}"></script>
<script src="{{asset(mix('js/scripts/app/auth/login.js'))}}"></script>
@endsection
