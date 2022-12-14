@extends('layouts/contentLayoutMaster')

@section('title', 'Edit Cutting Stock')
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
                        <form action="{{ route('factory.cutting.update', $cutting->slug) }}" method="POST">
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

                                    <span class="form-control">{{ $cutting->factory->factory_name }} </span>
                                    <input type="hidden" id="factory_slug" name="factory_slug"
                                        value="{{ $cutting->factory->slug }}">
                                </div>
                                <div class="mb-1 col-md-3">
                                    <label class="form-label" for="stockyard_rcn_stock_slug">Lot Number<label
                                            class="text-danger px-sm-25"> *</label></label>

                                    <span class="form-control">{{ $cutting->stockyardRcnStock->lot_number ?? '' }}</span>
                                    <input type="hidden" id="stockyard_rcn_stock_slug" name="stockyard_rcn_stock_slug"
                                        value="{{ $cutting->stockyardRcnStock->slug }}">
                                </div>
                                <div class="mb-1 col-md-3">
                                    <label class="form-label" for="cutting_date_time">Cutting Date<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" name="cutting_date_time" id="cutting_date_time"
                                        value="{{ old('cutting_date_time') }}"
                                        class="form-control flatpickr-sizer-date-time  flatpickr-input"
                                        placeholder="DD-MM-YYYY HH:II" />
                                    @error('cutting_date_time')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-3">
                                    <label class="form-label" for="cutting_type">Cutting Type<label
                                            class="text-danger px-sm-25"> *</label></label>

                                    <span class="form-control">{{ ucwords($cutting->cutting_type) }}</span>
                                    <input type="hidden" id="cutting_type" name="cutting_type"
                                        value="{{ $cutting->cutting_type }}">
                                </div>
                                <div class="row col-12 ">
                                    <h5 class="my-2">
                                        <i data-feather="filter" class="font-medium-4 mr-25"></i>
                                        <span class="align-middle">Cutting Inputs</span>
                                    </h5>
                                </div>
                                @if ($cutting->cutting_type == 'traditional')
                                    <div class="mb-1 col-md-3 traditional_sec">
                                        <label class="form-label" for="given_rcn_bag">Given RCN Bag<label
                                                class="text-danger px-sm-25"> *</label></label>
                                        <input type="number" name="given_rcn_bag" id="given_rcn_bag"
                                            value="{{ $cutting->given_rcn_bag }}" class="form-control"
                                            placeholder="in Bags" />
                                        @error('given_rcn_bag')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-1 col-md-3 traditional_sec">
                                        <label class="form-label" for="given_rcn_weight">Given RCN Weight<label
                                                class="text-danger px-sm-25"> *</label></label>
                                        <input type="number" name="given_rcn_weight" id="given_rcn_weight"
                                            value="{{ $cutting->given_rcn_weight }}" class="form-control"
                                            placeholder="in Kg" />
                                        @error('given_rcn_weight')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @else
                                    <?php
                                    
                                    $state_val_count = old('boiling_stocks') ? sizeof(old('boiling_stocks')) : 1;
                                    $state_val_count = old('boiling_stocks') ? sizeof(old('boiling_stocks')) : (sizeof($cutting->cuttingMap) > 0 ? sizeof($cutting->cuttingMap) : 1);
                                    
                                    ?>
                                    <div data-repeater-list="boiling_stocks" class="">
                                        <div data-repeater-item="" class="align-items-end">
                                            <div class="row d-flex align-items-end">
                                                <!-- <div class="row"> -->
                                                <div class="invoice-repeater">
                                                    <div data-repeater-list="boiling_stocks" class="">
                                                        @for ($i = 0; $i < $state_val_count; $i++)
                                                            <!-- <div data-repeater-list="sizering_stocks" class=""> -->
                                                            <div data-repeater-item=""
                                                                class="d-block align-items-end p-2 mx-25 border mb-1">
                                                                <div class="row align-items-end">
                                                                    <div class="col-md-2 col-12">
                                                                        <div class="mb-1">
                                                                            <label class="form-label"
                                                                                for="boiling_slug">Boiling Stock</label>
                                                                            <select
                                                                                class="form-select select2 sizering_stock1"
                                                                                name="boiling_slug" id="boiling_slug"
                                                                                data-selected="{{ old('boiling_stocks.' . $i . '.boiling_slug') }}">
                                                                                <option value="">Select</option>

                                                                                @foreach ($factory_boilers as $boil)
                                                                                    <option value="{{ $boil->slug }}"
                                                                                        {{ $boil->slug == old('boiling_stocks.' . $i . '.boiling_slug', $cutting->cuttingMap[$i]->boiling_slug ?? '') ? 'selected' : '' }}>
                                                                                        {{ $boil->boiling_number }}
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
                                                                                <input type="text" maxlength="6"
                                                                                    name="{{ $grade }}"
                                                                                    id="{{ $grade }}"
                                                                                    value="{{ $i < sizeof($cutting->cuttingMap) ? old('boiling_stocks.' . $i . '.' . $grade, $cutting->cuttingMap[$i]->{$grade}) : old('boiling_stocks.' . $i . '.' . $grade) }}"
                                                                                    class="form-control number-mask p-50"
                                                                                    placeholder="in Bags" />
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                    <div class="col-md-1 col-12 text-end">
                                                                        <div class="mb-1">
                                                                            <button
                                                                                class="btn btn-outline-danger text-nowrap px-1 waves-effect"
                                                                                data-repeater-delete="" type="button">
                                                                                <i data-feather="x"
                                                                                    class="font-medium-2"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                    @error('boiling_stocks.' . $i . '.boiling_slug')
                                                                        <div class="alert alert-danger">{{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                    @foreach (Config::get('constants.grades') as $name => $grade)
                                                                        @error('boiling_stocks.' . $i . '.' . $grade)
                                                                            <div class="alert alert-danger">
                                                                                {{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    @endforeach
                                                                </div>
                                                            </div>


                                                            <!-- </div> -->
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
                                @endif
                                <div class="row col-12">
                                    <h5 class="my-2">
                                        <i data-feather="filter" class="font-medium-4 mr-25"></i>
                                        <span class="align-middle">Cutting Output</span>
                                    </h5>
                                </div>
                                <div class="row">
                                    <div class="mb-1 col-md-2">
                                        <label class="form-label" for="wholes">Wholes</label>
                                        <input type="text" placeholder="in Kg" name="wholes" id="wholes"
                                            value="{{ $cutting->wholes }}" class="form-control number-mask" />
                                        @error('wholes')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-1 col-md-2">
                                        <label class="form-label" for="brokens">Brokens</label>
                                        <input type="text" placeholder="in Kg" step="0.01" name="brokens"
                                            id="brokens" value="{{ $cutting->brokens }}"
                                            class="form-control number-mask" />
                                        @error('brokens')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-1 col-md-2">
                                        <label class="form-label" for="piruwel">Piruwel</label>
                                        <input type="text" placeholder="in Kg" step="0.01" name="piruwel"
                                            id="piruwel" value="{{ $cutting->piruwel }}"
                                            class="form-control number-mask" />
                                        @error('piruwel')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-1 col-md-2">
                                        <label class="form-label" for="rejection">Rejection</label>
                                        <input type="text" placeholder="in Kg" step="0.01" name="rejection"
                                            id="rejection" value="{{ $cutting->rejection }}"
                                            class="form-control number-mask" />
                                        @error('rejection')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-1 col-md-2">
                                        <label class="form-label" for="uncut">Uncut</label>
                                        <input type="text" placeholder="in Kg" step="0.01" name="uncut"
                                            id="uncut" value="{{ $cutting->uncut }}"
                                            class="form-control number-mask" />
                                        @error('uncut')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-1 col-md-2">
                                        <label class="form-label" for="unscoop">Unscoop</label>
                                        <input type="text" placeholder="in Kg" step="0.01" name="unscoop"
                                            id="unscoop" value="{{ $cutting->unscoop }}"
                                            class="form-control number-mask" />
                                        @error('unscoop')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                    <a href="{{ route('factory.cutting') }}"><button
                                            class="dt-button buttons-collection btn btn-outline-secondary me-2"
                                            tabindex="0" aria-controls="DataTables_Table_0" type="button"
                                            aria-haspopup="true"><span>Back</span></button></a>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
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
    <script src="{{ asset(mix('js/scripts/app/factory/stock/cutting/cutting.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/app/factory/stock/cutting/form-repeater.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
    <?php
    $sizeringArray = json_encode(old('boiling_stocks'));
    ?>
    <script>
        // let cuttingDate = new Date("{{ old('cutting_date_time', $cutting->cutting_date_time) }}");
        $(".flatpickr-sizer-date-time").flatpickr({
            dateFormat: "Y-m-d", //change format also 
            //   enableTime: true,
            // weekNumbers: true,
            //    altInput: true,
            //     altFormat: "d-m-Y H:i",
            //  time_24hr: true,
            defaultDate: 'today'
        });
    </script>
@endsection
