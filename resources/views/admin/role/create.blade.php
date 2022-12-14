@extends('layouts/contentLayoutMaster')

@section('title', isset($role) ? 'Edit Role' : 'Add Role')

@section('content')
    <section id="multiple-column-form">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ isset($role) ? route('role.update', $role->slug) : route('admin.role.store') }}"
                            method="POST">
                            @if (isset($role))
                                @method('PUT')
                                <input type="hidden" name="id" value="{{ $role->slug }}">
                            @endif
                            @csrf
                            <div class="row">
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="name">Role Name<label class="text-danger px-sm-25">
                                            *</label></label>
                                    <input type="text" maxlength="32" name="name" id="name"
                                        value="{{ old('name', $role->name ?? '') }}" class="form-control" />
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="name">Work Location Type<label
                                            class="text-danger px-sm-25">*</label></label>
                                    <select name="work_location_type" id="work_location_type"
                                        class="form-select select select2">
                                        <option value="">Select</option>
                                        @foreach ($work_location_types as $work_location_type_slug => $work_location_type)
                                            <option value="{{ $work_location_type_slug }}"
                                                {{ old('work_location_type', $role->work_location_type ?? '') == $work_location_type_slug ? 'selected' : '' }}>
                                                {{ $work_location_type }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('work_location_type')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                <a href="{{ route('admin.role') }}"
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
    <script src="{{ asset(mix('js/scripts/app/role/role.js')) }}"></script>

@endsection
