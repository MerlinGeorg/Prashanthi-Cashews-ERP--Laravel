@extends('layouts/contentLayoutMaster')

@section('title', isset($usergroup) ? 'Edit User Group' : 'Add User Group')

@section('content')
    <section id="multiple-column-form">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form
                            action="{{ isset($usergroup) ? route('usergroup.update', $usergroup->slug) : route('admin.usergroup.store') }}"
                            method="POST">
                            @if (isset($usergroup))
                                @method('PUT')
                                <input type="hidden" name="id" value="{{ $usergroup->slug }}">
                            @endif
                            @csrf
                            <div class="row">
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="name">User Group Name<label
                                            class="text-danger px-sm-25">
                                            *</label></label>
                                    <input type="text" maxlength="32" name="name" id="name"
                                        value="{{ old('name', $usergroup->name ?? '') }}" class="form-control" />
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                <a href="{{ route('admin.usergroup') }}"
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
    <script src="{{ asset(mix('js/scripts/app/usergroup/usergroup.js')) }}"></script>

@endsection
