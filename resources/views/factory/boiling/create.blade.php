@extends('layouts/contentLayoutMaster')

@section('title', 'Add Boiling Stock')

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
                        <form action="{{ route('factory.boiling.store') }}" method="POST">
                            @csrf
                            <div class="row col-12">
                                <h5 class="my-2">
                                    <i data-feather="server" class="font-medium-4 mr-25"></i>
                                    <span class="align-middle">Factory Stock</span>
                                </h5>
                            </div>
                            <div class="row">
                                <div class="mb-1 col-md-3">
                                    <label class="form-label" for="factory_slug">Factory<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <select class="form-select select2" name="factory_slug" id="factory_slug"
                                        value="{{ old('factory_slug') }}">
                                        <option value="">Select</option>
                                        @if ($factories)
                                            @foreach ($factories as $factory)
                                                @if ($factory->slug == old('factory_slug'))
                                                    <option value="{{ $factory->slug }}" selected>
                                                        {{ $factory->factory_name }}</option>
                                                @else
                                                    <option value="{{ $factory->slug }}">{{ $factory->factory_name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('factory_slug')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-3">
                                    <label class="form-label" for="stockyard_rcn_stock_slug">Lot Number<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <select class="form-select select2" name="stockyard_rcn_stock_slug"
                                        id="stockyard_rcn_stock_slug"
                                        data-selected="{{ old('stockyard_rcn_stock_slug') }}">
                                        <option value="">Select</option>
                                    </select>
                                    @error('stockyard_rcn_stock_slug')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-3">
                                    <label class="form-label" for="boiling_date_time">Boiling Date<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" name="boiling_date_time" id="boiling_date_time"
                                        value="{{ old('boiling_date_time') }}"
                                        class="form-control flatpickr-sizer-date-time  flatpickr-input"
                                        placeholder="DD-MM-YYYY HH:II" />
                                    @error('boiling_date_time')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="row col-12">
                                    <h5 class="my-2">
                                        <i data-feather="filter" class="font-medium-4 mr-25"></i>
                                        <span class="align-middle">Boiling Input</span>
                                    </h5>
                                </div>
                                <?php
                                $state_val_count = old('sizering_stocks') ? sizeof(old('sizering_stocks')) : 1;
                                ?>
                                <div data-repeater-list="sizering_stocks" class="">
                                    <div data-repeater-item="" class="align-items-end">
                                        <div class="row d-flex align-items-end">
                                            <!-- <div class="row"> -->
                                            <div class="invoice-repeater">
                                                <div data-repeater-list="sizering_stocks" class="">
                                                    @for ($i = 0; $i < $state_val_count; $i++)
                                                        <!-- <div data-repeater-list="sizering_stocks" class=""> -->
                                                        <div data-repeater-item=""
                                                            class="d-block align-items-end p-2 mx-25 border mb-1">
                                                            <div class="row align-items-end">
                                                                <div class="col-md-2 col-12">
                                                                    <div class="mb-1">
                                                                        <label class="form-label"
                                                                            for="sizering_slug">Sizering Stock</label>
                                                                        <select class="form-select select2 sizering_stock"
                                                                            name="sizering_slug" id="sizering_slug"
                                                                            data-selected="{{ old('sizering_stocks.' . $i . '.sizering_slug') }}">
                                                                            <option value="">Select</option>

                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                @foreach (Config::get('constants.grades') as $name => $grade)
                                                                    <div class="col-md-1 col-12">
                                                                        <div class="mb-1">
                                                                            <label class="form-label"
                                                                                for="experiences">{{ $name }}</label>
                                                                            <input placeholder="in Kgs" type="text"
                                                                                maxlength="6" name="{{ $grade }}"
                                                                                id="{{ $grade }}"
                                                                                value="{{ old('sizering_stocks.' . $i . '.' . $grade) }}"
                                                                                class="form-control number-mask p-50" />
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                                <div class="col-md-1 col-12 text-end">
                                                                    <div class="mb-1">
                                                                        <button
                                                                            class="btn btn-outline-danger text-nowrap px-1 waves-effect"
                                                                            data-repeater-delete="" type="button">
                                                                            <i data-feather="x" class="font-medium-2"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @error('sizering_stocks.' . $i . '.sizering_slug')
                                                            <div class="alert alert-danger">{{ $message }}
                                                            </div>
                                                        @enderror
                                                        @foreach (Config::get('constants.grades') as $name => $grade)
                                                            @error('sizering_stocks.' . $i . '.' . $grade)
                                                                <div class="alert alert-danger">{{ $message }}
                                                                </div>
                                                            @enderror
                                                        @endforeach

                                                    @endfor

                                                </div>

                                                <div class="col-md-12 col-12 text-center px-5 py-1">
                                                    <button
                                                        class="btn btn-icon btn-outline-success waves-effect waves-float waves-light"
                                                        id="repeater-button" type="button" data-repeater-create="">
                                                        <i data-feather="plus" class="font-medium-2"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row col-12">
                                    <h5 class="my-2">
                                        <i data-feather="download" class="font-medium-4 mr-25"></i>
                                        <span class="align-middle">Boiling Output</span>
                                    </h5>
                                </div>
                                <div class="row">
                                    <div class="row border m-0 m-1 p-1 col-md-12">
                                        @foreach (Config::get('constants.grades') as $name => $grade)
                                            <div class="mb-1 col-md-1">
                                                <label class="form-label"
                                                    for="{{ $grade }}_total_weight">{{ $name }}</label>
                                                <input type="text" placeholder="in Bags"
                                                    name="{{ $grade }}_total_weight"
                                                    id="{{ $grade }}_total_weight"
                                                    value="{{ old($grade . '_total_weight') }}"
                                                    class="form-control number-mask p-50" maxlength="6" />
                                            </div>
                                        @endforeach
                                        @foreach (Config::get('constants.grades') as $name => $grade)
                                            @error($grade . '_total_weight')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                    <a href="{{ route('factory.boiling') }}"><button
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
    <script src="{{ asset(mix('js/scripts/app/factory/stock/boiling/boiling.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/app/factory/stock/boiling/form-repeater.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
    <?php
    $sizeringArray = json_encode(old('sizering_stocks'));
    ?>
    <script>
        $(document).ready(function() {

            fetchLotNumber();
            factorySizeringStock(updateAllSizeringStock = true);

            $(".flatpickr-sizer-date-time").flatpickr({
                dateFormat: "Y-m-d", //change format also 
                // enableTime: true,
                defaultDate: 'today'
            });
        });
    </script>
@endsection
