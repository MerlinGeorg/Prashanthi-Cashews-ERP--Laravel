@extends('layouts/contentLayoutMaster')

@section('title', isset($jobcategory) ? 'Edit Job Category' : 'Add Job Category')

@section('content')
    <section id="multiple-column-form">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form
                            action="{{ isset($jobcategory) ? route('jobcategory.update', $jobcategory->slug) : route('admin.jobcategory.store') }}"
                            method="POST">
                            @if (isset($jobcategory))
                                @method('PUT')
                                <input type="hidden" name="id" value="{{ $jobcategory->slug }}">
                            @endif
                            @csrf
                            <div class="row">
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="name">Job Category Name<label
                                            class="text-danger px-sm-25">
                                            *</label></label>
                                    <input type="text" maxlength="32" name="name" id="name"
                                        value="{{ old('name', $jobcategory->name ?? '') }}" class="form-control" />
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                <a href="{{ route('admin.jobcategory') }}"
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
    <script src="{{ asset(mix('js/scripts/app/jobcategory/jobcategory.js')) }}"></script>

@endsection
