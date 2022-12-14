@extends('layouts/contentLayoutMaster')

@section('title', 'Edit Account')

@section('content')

    <section id="multiple-column-form">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('account.update', $account->slug) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="align-items-center my-1">
                                <i data-feather="user"> </i>
                                <strong> Account Informations</strong>
                            </div>
                            <div class="row">
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="qualification">Name<label class="text-danger px-sm-25">
                                            *</label></label>
                                    <input type="text" maxlength="32" name="account_name" id="account_name"
                                        value="{{ old('account_name', $account->account_name) }}" class="form-control" />
                                    @error('account_name')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="experiences">Short Name<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" maxlength="32" name="account_short_name" id="account_short_name"
                                        value="{{ old('account_name', $account->account_short_name) }}"
                                        class="form-control" />
                                    @error('account_short_name')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="align-items-center my-1">
                                <i data-feather="lock"> </i>
                                <strong> GST Informations</strong>
                            </div>
                            <?php
                            $state_val_count = old('state_account') ? sizeof(old('state_account')) : ($account->subAccounts ? sizeof($account->subAccounts) : 1);
                            ?>
                            <div data-repeater-list="state_account" class="">
                                <div data-repeater-item="" class="align-items-end">
                                    <div class="row d-flex align-items-end">
                                        <!-- <div class="row"> -->
                                        <div class="invoice-repeater">
                                            <div data-repeater-list="state_account" class="">
                                                @for ($i = 0; $i < $state_val_count; $i++)
                                                    <div data-repeater-list="state_account" class="">
                                                        <div data-repeater-item=""
                                                            class="d-block align-items-end p-2 mx-25 border mb-1">
                                                            <div class="row align-items-end">
                                                                <div class="col-md-6 col-12">
                                                                    <div class="mb-1">
                                                                        <label class="form-label"
                                                                            for="account_state">State<label
                                                                                class="text-danger px-sm-25">
                                                                                *</label></label>
                                                                        <select class="form-select select2"
                                                                            name="account_state" id="account_state"
                                                                            value="{{ old('state_account.' . $i . '.account_state', isset($account->subAccounts[$i]) ? $account->subAccounts[$i]['account_state'] : '') }}">
                                                                            <option value="">Select</option>
                                                                            @if (config('constants.states'))
                                                                                @foreach (config('constants.states') as $state)
                                                                                    @if ($state == old('state_account.' . $i . '.account_state', isset($account->subAccounts[$i]) ? $account->subAccounts[$i]['account_state'] : ''))
                                                                                        <option value="{{ $state }}"
                                                                                            selected>{{ $state }}
                                                                                        </option>
                                                                                    @else
                                                                                        <option value="{{ $state }}">
                                                                                            {{ $state }}</option>
                                                                                    @endif
                                                                                @endforeach
                                                                            @endif
                                                                        </select>
                                                                        @error('state_account.' . $i . '.account_state')
                                                                            <div class="alert alert-danger">{{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-5 col-12">
                                                                    <div class="mb-1">
                                                                        <label class="form-label"
                                                                            for="experiences">GST<label
                                                                                class="text-danger px-sm-25">
                                                                                *</label></label>
                                                                        <input type="text" maxlength="128"
                                                                            name="account_gst" id="account_gst"
                                                                            value="{{ old('state_account.' . $i . '.account_gst', isset($account->subAccounts[$i]) ? $account->subAccounts[$i]['account_gst'] : '') }}"
                                                                            class="form-control" />
                                                                        @error('state_account.' . $i . '.account_gst')
                                                                            <div class="alert alert-danger">{{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-1 col-12 text-end">
                                                                    <div class="mb-1">
                                                                        <button
                                                                            class="btn btn-outline-danger text-nowrap px-1 waves-effect"
                                                                            data-repeater-delete="" type="button">
                                                                            <i data-feather="x" class="font-medium-2"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 col-12">
                                                                    <div class="mb-1">
                                                                        <label class="form-label"
                                                                            for="experiences">Address
                                                                            Line 1<label class="text-danger px-sm-25">
                                                                                *</label></label>
                                                                        <input type="text" maxlength="128"
                                                                            name="account_address_1" id="account_address_1"
                                                                            value="{{ old('state_account.' . $i . '.account_address_1', isset($account->subAccounts[$i]) ? $account->subAccounts[$i]['account_address_1'] : '') }}"
                                                                            class="form-control" />
                                                                        @error('state_account.' . $i . '.account_address_1')
                                                                            <div class="alert alert-danger">{{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-5 col-12">
                                                                    <div class="mb-1">
                                                                        <label class="form-label"
                                                                            for="experiences">Address
                                                                            Line 2</label>
                                                                        <input type="text" maxlength="128"
                                                                            name="account_address_2" id="account_address_2"
                                                                            value="{{ old('state_account.' . $i . '.account_address_2', isset($account->subAccounts[$i]) ? $account->subAccounts[$i]['account_address_2'] : '') }}"
                                                                            class="form-control" />
                                                                        @error('state_account.' . $i . '.account_address_2')
                                                                            <div class="alert alert-danger">{{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endfor
                                            </div>
                                            <div class="col-md-12 col-12 text-center px-5 py-1">
                                                <button
                                                    class="btn btn-icon btn-outline-success waves-effect waves-float waves-light"
                                                    type="button" data-repeater-create="">
                                                    <i data-feather="plus" class="font-medium-2"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 mt-1 text-center">
                                        <a href="{{ route('admin.account') }}"><button
                                                class="dt-button buttons-collection btn btn-outline-secondary me-2"
                                                tabindex="0" aria-controls="DataTables_Table_0" type="button"
                                                aria-haspopup="true"><span>Back</span></button></a>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
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
    <script src="{{ asset(mix('js/scripts/forms/repeater/jquery.repeater.min.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/app/account/form-repeater.js')) }}"></script>
@endsection
