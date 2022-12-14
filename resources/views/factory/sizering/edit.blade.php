@extends('layouts/contentLayoutMaster')

@section('title', 'Edit Sizering Stock')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/plugins/forms/pickers/form-flat-pickr.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/plugins/forms/pickers/form-pickadate.css')) }}">
@endsection

@section('content')
    <section id="multiple-column-form">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('sizering.update', $sizering->slug) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row col-12">
                                <h5 class="my-2">
                                    <i data-feather="server" class="font-medium-4 mr-25"></i>
                                    <span class="align-middle">Factory Stock</span>
                                </h5>
                            </div>
                            <div class="row">
                                <div class="mb-1 col-md-4">
                                    <label class="form-label" for="factory_slug">Factory<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <span class="form-control">{{ $sizering->factory->factory_name }}</span>
                                    <input type="hidden" name="factory_slug" id="factory_slug"
                                        value="{{ old('factory_slug', $sizering->factory_slug) }}">

                                    @error('factory_slug')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-3">
                                    <label class="form-label" for="factory_stock_slug">Lot Number<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <span class="form-control">{{ $sizering->stockyardRcnStock->lot_number }}</span>
                                    <input type="hidden" name="factory_stock_slug" id="factory_stock_slug"
                                        value="{{ old('factory_stock_slug', $sizering->factory_stock_slug) }}">

                                    @error('factory_stock_slug')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-3">
                                    <label class="form-label" for="sizering_date_time">Received Date<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" name="sizering_date_time" id="sizering_date_time"
                                        value="{{ old('sizering_date_time', $sizering->sizering_date_time) }}"
                                        class="form-control flatpickr-sizer-date-time  flatpickr-input"
                                        placeholder="DD-MM-YYYY HH:II" />
                                    @error('sizering_date_time')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row col-12">
                                <h5 class="my-2">
                                    <i data-feather="filter" class="font-medium-4 mr-25"></i>
                                    <span class="align-middle">Sizering Input Stock</span>
                                </h5>
                            </div>
                            <div class="row border p-1 m-0 col-md-6">
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="rcn_weight">RCN Weight<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" step="0.01" placeholder="in Kgs" name="rcn_weight" id="rcn_weight"
                                        value="{{ old('rcn_weight', $sizering->rcn_weight) }}" class="form-control"
                                        maxlength="6" />
                                    @error('rcn_weight')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="rcn_bag">RCN Bag<label class="text-danger px-sm-25">
                                            *</label></label>
                                    <input type="text" step="0.01" placeholder="in Bags" placeholder="in Bags"
                                        name="rcn_bag" id="rcn_bag" value="{{ old('rcn_bag', $sizering->rcn_bag) }}"
                                        class="form-control" maxlength="6" />
                                    @error('rcn_bag')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row col-12">
                                <h5 class="my-2">
                                    <i data-feather="download" class="font-medium-4 mr-25"></i>
                                    <span class="align-middle">Sizering Output Stock</span>
                                </h5>
                            </div>
                            <div class="row">
                                <div class="col-md-11 border row m-1 p-1 mr-0">
                                    @foreach (Config::get('constants.grades') as $name => $grade)
                                        <div class="mb-1 col-md-1">
                                            <label class="form-label"
                                                for="{{ $grade }}_total_weight">{{ $name }}</label>
                                            <input type="text" placeholder="in Kgs" name="{{ $grade }}_total_weight"
                                                id="{{ $grade }}_total_weight"
                                                value="{{ old($grade . '_total_weight', $sizering->{"{$grade}_total_weight"}) }}"
                                                class="form-control number-mask p-50" maxlength="6" />
                                        </div>
                                    @endforeach

                                    <div class="mb-1 col-md-2">
                                        <label class="form-label" for="foreign_matter_total_weight">Foreign
                                            Matter</label>
                                        <input type="text" placeholder="in Kgs" step="0.01"
                                            name="foreign_matter_total_weight" id="foreign_matter_total_weight"
                                            value="{{ old('foreign_matter_total_weight', $sizering->foreign_matter_total_weight) }}"
                                            class="form-control number-mask p-50" maxlength="6" />

                                    </div>
                                    @foreach (Config::get('constants.grades') as $name => $grade)
                                        @error($grade . '_total_weight')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    @endforeach
                                    @error('foreign_matter_total_weight')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                    @error('total_output_weight')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12 text-center p-1">
                                    <a href="{{ route('factory.sizering') }}"><button
                                            class="dt-button buttons-collection btn btn-outline-secondary me-2" tabindex="0"
                                            aria-controls="DataTables_Table_0" type="button"
                                            aria-haspopup="true"><span>Back</span></button></a>
                                    <button type="submit" class="btn btn-primary">Submit</button>
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
    <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.date.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.time.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/scripts/forms/pickers/form-pickers.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/app/state-account.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/form-input-mask.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/repeater/jquery.repeater.min.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/app/factory/stock/stock.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/app/factory/stock/sizering/sizering.js')) }}"></script>
    <script>
        let selectedStock = "{{ old('factory_stock_slug', $sizering->factory_stock_slug) }}";
        getfactoryofchange();
        let defaultDate = "{{ old('sizering_date_time', $sizering->sizering_date_time) }}";
        $(".flatpickr-sizer-date-time").flatpickr({
            dateFormat: "Y-m-d", //change format also 
            // enableTime: true,
            defaultDate: defaultDate
        });
    </script>
@endsection
