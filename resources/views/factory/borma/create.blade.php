@extends('layouts/contentLayoutMaster')
@section('title', 'Add Borma Stock')

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
                        <form action="{{ route('factory.borma.store') }}" method="POST">
                            @csrf
                            <div class="row col-12">
                                <h5 class="my-2">
                                    <i data-feather="server" class="font-medium-4 mr-25"></i>
                                    <span class="align-middle">Borma Stock</span>
                                </h5>
                            </div>
                            <div class="row">
                                <div class="mb-1 col-md-3">
                                    <label class="form-label" for="factory_slug">Factory
                                        <label class="text-danger px-sm-25"> *</label>
                                    </label>
                                    <select class="form-select select2" name="factory_slug" id="factory_slug"
                                        value="{{ old('factory_slug') }}">
                                        <option value="">Select</option>
                                        @if ($factories)
                                            @foreach ($factories as $factory)
                                                @if ($factory->slug == old('factory_slug'))
                                                    <option value="{{ $factory->slug }}" selected>
                                                        {{ $factory->factory_name }}</option>
                                                @else
                                                    <option value="{{ $factory->slug }}">
                                                        {{ $factory->factory_name }}
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
                                    <label class="form-label" for="borma_work_date_time">
                                        Borma Date
                                        <label class="text-danger px-sm-25"> *</label>
                                    </label>
                                    <input type="text" name="borma_work_date_time" id="borma_work_date_time"
                                        value="{{ old('borma_work_date_time') }}"
                                        class="form-control flatpickr-sizer-date-time  flatpickr-input"
                                        placeholder="DD-MM-YYYY" />
                                    @error('borma_work_date_time')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="row col-12">
                                    <h5 class="my-2">
                                        <i data-feather="filter" class="font-medium-4 mr-25"></i>
                                        <span class="align-middle">Cutting Input</span>
                                    </h5>
                                </div>
                                <?php
                                $state_val_count = old('cutting_stocks') ? sizeof(old('cutting_stocks')) : 1;
                                ?>
                                <div data-repeater-list="cutting_stocks" class="">
                                    <div data-repeater-item="" class="align-items-end">
                                        <div class="row d-flex align-items-end">
                                            <!-- <div class="row"> -->
                                            <div class="invoice-repeater">
                                                <div data-repeater-list="cutting_stocks" class="">
                                                    @for ($i = 0; $i < $state_val_count; $i++)
                                                        <!-- <div data-repeater-list="cutting_stocks" class=""> -->
                                                        <div data-repeater-item=""
                                                            class="d-block align-items-end p-2 mx-25 border mb-1">
                                                            <div class="row align-items-end">
                                                                <div class="col-md-3 col-12">
                                                                    <div class="mb-1">
                                                                        <label class="form-label"
                                                                            for="cutting_slug">Cutting Stock</label>
                                                                        <select class="form-select select2 cutting_stock"
                                                                            name="cutting_slug" id="cutting_slug"
                                                                            value="{{ old('cutting_stocks.' . $i . '.cutting_slug') }}">
                                                                            <option value="">Select</option>
                                                                        </select>
                                                                        @error('cutting_stocks.' . $i . '.cutting_slug')
                                                                            <div class="alert alert-danger">{{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2 col-12">
                                                                    <div class="mb-1">
                                                                        <label class="form-label"
                                                                            for="experiences">Wholes</label>
                                                                        <input placeholder="in Kgs" type="text"
                                                                            maxlength="128" name="wholes" id="wholes"
                                                                            value="{{ old('cutting_stocks.' . $i . '.wholes') }}"
                                                                            class="form-control number-mask" />
                                                                        @error('cutting_stocks.' . $i . '.wholes')
                                                                            <div class="alert alert-danger">{{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2 col-12">
                                                                    <div class="mb-1">
                                                                        <label class="form-label"
                                                                            for="experiences">Brokens</label>
                                                                        <input placeholder="in Kgs" type="text"
                                                                            maxlength="128" name="brokens" id="brokens"
                                                                            value="{{ old('cutting_stocks.' . $i . '.brokens') }}"
                                                                            class="form-control number-mask" />
                                                                        @error('cutting_stocks.' . $i . '.brokens')
                                                                            <div class="alert alert-danger">{{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2 col-12">
                                                                    <div class="mb-1">
                                                                        <label class="form-label"
                                                                            for="experiences">Piruwal</label>
                                                                        <input placeholder="in Kgs" type="text"
                                                                            maxlength="128" name="piruwal" id="piruwal"
                                                                            value="{{ old('cutting_stocks.' . $i . '.piruwal') }}"
                                                                            class="form-control number-mask" />
                                                                        @error('cutting_stocks.' . $i . '.piruwal')
                                                                            <div class="alert alert-danger">{{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-1 col-12 text-end">
                                                                    <div class="mb-1">
                                                                        <button
                                                                            class="btn btn-outline-danger text-nowrap px-1 waves-effect"
                                                                            data-repeater-delete="" type="button"> <i
                                                                                data-feather="x" class="font-medium-2"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- </div> -->
                                                    @endfor
                                                </div>
                                                <div class="col-md-12 col-12 text-center px-5 py-1">
                                                    <button
                                                        class="btn btn-icon btn-outline-success waves-effect waves-float waves-light"
                                                        id="repeater-button" type="button" data-repeater-create=""> <i
                                                            data-feather="plus" class="font-medium-2"></i> </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row col-12">
                                    <h5 class="my-2">
                                        <i data-feather="download" class="font-medium-4 mr-25"></i>
                                        <span class="align-middle">Cutting Output</span>
                                    </h5>
                                </div>
                                <div class="row ">
                                    <div class="row border m-0 m-1 p-1 col-md-12">
                                        <div class="mb-1 col-md-2 ">
                                            <label class="form-label" for="wholes">Wholes</label>
                                            <input type="text" placeholder="in Bags" name="wholes" id="wholes"
                                                value="{{ old('wholes') }}" class="form-control number-mask" />
                                            @error('wholes')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-1 col-md-2">
                                            <label class="form-label" for="brokens">Brokens</label>
                                            <input type="text" placeholder="in Bags" step="0.01" name="brokens" id="brokens"
                                                value="{{ old('brokens') }}" class="form-control number-mask" />
                                            @error('brokens')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-1 col-md-2">
                                            <label class="form-label" for="piruwal">Piruwal</label>
                                            <input type="text" placeholder="in Bags" step="0.01" name="piruwal" id="piruwal"
                                                value="{{ old('piruwal') }}" class="form-control number-mask" />
                                            @error('piruwal')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                        <a href="{{ route('factory.borma') }}">
                                            <button class="dt-button buttons-collection btn btn-outline-secondary me-2"
                                                tabindex="0" aria-controls="DataTables_Table_0" type="button"
                                                aria-haspopup="true"><span>Back</span></button>
                                        </a>
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
    $sizeringArray = json_encode(old('cutting_stocks'));
    ?>
    <script>
        getfactoryofchange();
        factorySizeringStock();
        // let boilingDate = new Date("{{ old('borma_work_date_time') }}");
        let sizeringStocks = JSON.stringify(<?php echo $sizeringArray; ?>);
        sizeringStocks = JSON.parse(sizeringStocks);
        if (sizeringStocks && sizeringStocks.length > 0) {
            setTimeout(function() {
                $.each($('.sizering_stock'), function(index, value) {
                    $(this).val(sizeringStocks[index].sizering_slug);
                    $(this).val(sizeringStocks[index].sizering_slug).select2();
                });
            }, 100);
        }
        $(".flatpickr-sizer-date-time").flatpickr({
            dateFormat: "Y-m-d", //change format also 
            // enableTime: true,
            // weekNumbers: true,
            // altInput: true,
            // altFormat: "d-m-Y H:i",
            // time_24hr: true,
            defaultDate: 'today'
        });
    </script>
@endsection
