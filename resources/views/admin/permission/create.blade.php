@extends('layouts/contentLayoutMaster')

@section('title', isset($permission) ? 'Edit Permission' : 'Add Permission')

@section('content')
    <section id="multiple-column-form">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form
                            action="{{ isset($permission) ? route('permission.update', $permission->slug) : route('admin.permission.store') }}"
                            method="POST">
                            @if (isset($permission))
                                @method('PUT')
                                <input type="hidden" name="slug" value="{{ $permission->slug }}">
                            @endif
                            @csrf
                            <div class="row">
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="name">Permission Name<label
                                            class="text-danger px-sm-25">
                                            *</label></label>
                                    <input type="text" maxlength="32" name="name" id="name"
                                        value="{{ old('name', $permission->name ?? '') }}" class="form-control" />
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="name">Resource<label class="text-danger px-sm-25">
                                            *</label></label>
                                    <select name="resource_slug" id="resource_slug" class="form-select select select2">
                                        <option>Select </option>
                                        @foreach ($resources as $resource)
                                            <option value="{{ $resource->slug }}" @if (old('resource_slug', $permission->resource_slug ?? '') == $resource->slug)
                                                selected
                                        @endif >
                                        {{ $resource->resource_name }}
                                        ( {{ $work_location_types[$resource->work_location_type] ?? '' }} )
                                        </option>
                                        @endforeach
                                    </select>

                                    @error('resource_slug')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                <a href="{{ route('admin.permission') }}"
                                    class="dt-button buttons-collection btn btn-outline-secondary me-2"
                                    type="button"><span>Back</span></button></a>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('vendor-script')
    <script src="{{ asset(mix('vendors/js/forms/wizard/bs-stepper.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/cleave/cleave.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/cleave/addons/cleave-phone.in.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset(mix('js/scripts/forms/form-input-mask.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/app/state-account.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/form-input-mask.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/repeater/jquery.repeater.min.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/app/permission/permission.js')) }}"></script>

@endsection
