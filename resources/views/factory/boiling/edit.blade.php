@extends('layouts/contentLayoutMaster')

@section('title', 'Edit Boiling Stock')

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
                        <form action="{{ route('boiling.update', $boiling->slug) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row col-12">
                                <h5 class="my-2">
                                    <i data-feather="server" class="font-medium-4 mr-25"></i>
                                    <span class="align-middle">Factory Stock</span>
                                </h5>
                            </div>
                            <div class="row">
                                <div class="mb-1 col-md-3">
                                    <label class="form-label">Factory Name</label>
                                    <span class="form-control">{{ $boiling->factory->factory_name }} </span>
                                    <input type="hidden" id="factory_slug" name="factory_slug"
                                        value="{{ $boiling->factory->slug }}">
                                </div>
                                <div class="mb-1 col-md-3">
                                    <label class="form-label" for="factory_stock_slug">Lot Number</label>
                                    <span class="form-control">
                                        {{ $boiling->stockyardRcnStock->lot_number ?? '' }} </span>
                                    <input type="hidden" id="stockyard_rcn_stock_slug" name="stockyard_rcn_stock_slug"
                                        value="{{ $boiling->stockyardRcnStock->lot_number }}">
                                </div>
                                <div class="mb-1 col-md-3">
                                    <label class="form-label" for="boiling_date_time">Boiling Date<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" name="boiling_date_time" id="boiling_date_time"
                                        value="{{ old('boiling_date_time', $boiling->boiling_date_time) }}"
                                        class="form-control flatpickr-sizer-date-time  flatpickr-input"
                                        placeholder="DD-MM-YYYY HH:II" />
                                    @error('boiling_date_time')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <h5 class="my-2">
                                    <i data-feather="filter" class="font-medium-4 mr-25"></i>
                                    <span class="align-middle">Boiling Input</span>
                                </h5>
                                <?php
                                $state_val_count = old('sizering_stocks') ? sizeof(old('sizering_stocks')) : (sizeof($boiling->boilingMap) > 0 ? sizeof($boiling->boilingMap) : 1);
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
                                                                        <select class="form-select select2 sizering_stock1"
                                                                            name="sizering_slug" id="sizering_slug"
                                                                            data-selected="{{ old('sizering_stocks.' . $i . '.sizering_slug') }}">
                                                                            <option value="">Select</option>
                                                                            @foreach ($factory_sizers as $sizer)
                                                                                <option value="{{ $sizer->slug }}"
                                                                                    {{ $sizer->slug == old('sizering_stocks.' . $i . '.sizering_slug', $boiling->boilingMap[$i]->sizering_slug ?? '') ? 'selected' : '' }}>
                                                                                    {{ $sizer->sizering_number }}
                                                                                </option>
                                                                            @endforeach
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
                                                                                value="{{ $i < sizeof($boiling->boilingMap) ? old('sizering_stocks.' . $i . '.' . $grade, $boiling->boilingMap[$i]->{$grade}) : old('sizering_stocks.' . $i . '.' . $grade) }}"
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
                                                        </div>
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
                            </div>
                            <div class="row col-12">
                                <h5 class="my-2">
                                    <i data-feather="download" class="font-medium-4 mr-25"></i>
                                    <span class="align-middle">Boiling Output</span>
                                </h5>
                            </div>
                            <div class="row">
                                <div class="row border m-0 m-1 p-1 col-md-11">
                                    @foreach (Config::get('constants.grades') as $name => $grade)
                                        <div class="mb-1 col-md-1">
                                            <label class="form-label"
                                                for="{{ $grade }}_total_weight">{{ $name }}</label>
                                            <input type="text" placeholder="in Bags"
                                                name="{{ $grade }}_total_weight"
                                                id="{{ $grade }}_total_weight"
                                                value="{{ old($grade . '_total_weight', $boiling->{"{$grade}_total_weight"}) }}"
                                                class="form-control number-mask p-50" maxlength="6" />
                                        </div>
                                    @endforeach
                                    @foreach (Config::get('constants.grades') as $name => $grade)
                                        @error($grade . '_total_weight')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    @endforeach
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                    <a href="{{ route('factory.boiling') }}"><button
                                            class="dt-button buttons-collection btn btn-outline-secondary me-2"
                                            tabindex="0" aria-controls="DataTables_Table_0" type="button"
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
