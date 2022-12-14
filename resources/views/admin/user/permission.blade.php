@extends('layouts/admin')

@section('title', 'User Permission')

@section('vendor-style')
{{-- Vendor Css files --}}
<link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
@endsection

@section('page-style')
{{-- Page Css files --}}
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('css/base/pages/app-user.css')) }}">
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">User Account - <strong>{{$user->first_name}} {{$user->last_name}}</strong></h4>
            </div>
            <div class="card-body">
                <form action="{{route('admin.user.permission.submit')}}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{$id}}">
                    <div class="col-12 px-0">
                        <h5 class="mb-1">
                            <i data-feather="layers" class="font-medium-4 mr-25"></i>
                            <span class="align-middle">Permissions </span>
                        </h5>

                        <div class="row">
                            @foreach (config('constants.acl_resources') as $resource_slug => $resource_name)

                            <div class="col-md-6 col-xl-3 ">
                                <div class="card shadow-none bg-transparent border-primary">
                                    <div class="card-body" style="height:220px;">
                                        <h4 class="card-title mb-0">{{$resource_name}}</h4>
                                        <hr>
                                        @foreach (config('constants.acl_permissions.'.$resource_slug) as
                                        $permission_slug)
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="permissions[]"
                                                id="{{ $resource_slug }}-{{$permission_slug}}"
                                                value="{{ $resource_slug }}-{{$permission_slug}}" @if(
                                                in_array("$resource_slug-$permission_slug",$user_permissions) )
                                                checked="checked" @endif />
                                            <label class="custom-control-label"
                                                for="{{ $resource_slug }}-{{$permission_slug}}">{{ Str::title(str_replace('-', ' ', $permission_slug)) }}</label>
                                        </div>

                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="row col-12 flex-row-reverse mt-2 p-0">
                        <button type="submit" class="btn btn-primary waves-effect waves-float waves-light">Save
                            Changes</button>
                        <a class="btn btn-outline-secondary mr-1 waves-effect" href="{{route('admin.user')}}">Cancel</a>
                    </div>
            </div>
            </form>
        </div>
    </div>
</div>
</section>
<!-- users edit ends -->
@endsection

@section('vendor-script')
{{-- Vendor js files --}}
<script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
@endsection

@section('page-script')
{{-- Page js files --}}
<script src="{{ asset(mix('js/scripts/pages/app-user-edit.js')) }}"></script>
<script src="{{ asset(mix('js/scripts/components/components-navs.js')) }}"></script>
@endsection