@extends('layouts/contentLayoutMaster')

@section('title', 'Add Inward RCN')

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
                        <form action="{{ url('stockyard/inward-rcn/save-rcn') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="mb-1 col-md-4">
                                    <label class="form-label" for="truck_reg_number">Truck Reg No.<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" name="truck_reg_number" id="truck_reg_number"
                                        class="form-control text-uppercase" value="{{ old('truck_reg_number') }}"
                                        placeholder="KL-02-AA-0123" maxlength="15" />
                                    @error('truck_reg_number')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-4">
                                    <label class="form-label" for="container_number">Container No.<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" name="container_number" id="container_number"
                                        value="{{ old('container_number') }}" class="form-control alphanumeric"
                                        maxlength="32" />
                                    @error('container_number')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-4">
                                    <label class="form-label" for="seal_number">Seal No.<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" name="seal_number" id="seal_number"
                                        value="{{ old('seal_number') }}" class="form-control alphanumeric"
                                        maxlength="32" />
                                    @error('seal_number')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-4">
                                    <label class="form-label" for="contact_number">Contact No.<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text">IN (+91)</span>
                                        <input type="text" maxlength="10" name="contact_number" id="contact_number"
                                            value="{{ old('contact_number') }}" class="form-control number-mask" />
                                    </div>
                                    @error('contact_number')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-4">
                                    <label class="form-label" for="dc_number">DC No.</label>
                                    <input type="text" name="dc_number" id="dc_number" value="{{ old('dc_number') }}"
                                        class="form-control alphanumeric" maxlength="32" />
                                    @error('dc_number')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-4">
                                    <label class="form-label" for="ewb_number">EWB No.</label>
                                    <input type="text" name="ewb_number" id="ewb_number" value="{{ old('ewb_number') }}"
                                        class="form-control alphanumeric" maxlength="32" />
                                    @error('ewb_number')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <h5 class="my-2">
                                        <i data-feather="package" class="font-medium-4 mr-25"></i>
                                        <span class="align-middle">Details</span>
                                </h5>
                                
                                <div class="mb-1 col-md-4">
                                    <label class="form-label" for="rcn_net_weight">RCN Net Weight (Kg)<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="number" name="rcn_net_weight" id="rcn_net_weight"
                                        value="{{ old('rcn_net_weight') }}" class="form-control number-mask"
                                        maxlength="10" placeholder="(Kilogram)" />
                                    @error('rcn_net_weight')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-4">
                                    <label class="form-label" for="rcn_bags">RCN Bags (No.s)<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="number" name="rcn_bags" id="rcn_bags" value="{{ old('rcn_bags') }}"
                                        class="form-control number-mask" maxlength="10" placeholder="(No.s)" />
                                    @error('rcn_bags')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-4">
                                    <label class="form-label" for="tare_weight">Tare Weight (Kg)<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="number" name="tare_weight" id="tare_weight"
                                        value="{{ old('tare_weight') }}" class="form-control number-mask" maxlength="10"
                                        placeholder="(Kilogram)" />
                                    @error('tare_weight')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-4">
                                    <label class="form-label" for="out_turn">Out Turn (Lbs)<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" name="out_turn" id="out_turn" value="{{ old('out_turn') }}"
                                        class="form-control number-mask" placeholder="(Lbs)" maxlength="10" />
                                    @error('out_turn')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-4">
                                    <label class="form-label" for="nut_count">Nut Count (No.s)<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" name="nut_count" id="nut_count" value="{{ old('nut_count') }}"
                                        class="form-control number-mask" placeholder="(No.s)" maxlength="10" />
                                    @error('nut_count')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-4">
                                    <label class="form-label" for="rejection">Rejection (%)<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="number" name="rejection" id="rejection" value="{{ old('rejection') }}"
                                        class="form-control number-mask" placeholder="(Percentage)" maxlength="3" max="100"
                                        step=".01" />
                                    @error('rejection')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- <div class="mb-1 col-md-4">
                                    <label class="form-label" for="rejection">Document<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="file" name="document" id="document" class="form-control" />
                                    @error('document')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div> -->
                                <div class="mb-1 col-md-4">
                                    <label class="form-label" for="moisture_level">Moisture Level<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <select class="form-select select2" name="moisture_level" id="moisture_level"
                                        value="{{ old('moisture_level') }}">
                                        <!-- <option value="" disabled selected>-Select-</option> -->
                                        <option value="0" {{ old('moisture_level') == 0 ? 'selected' : '' }}>Dry</option>
                                        <option value="1" {{ old('moisture_level') == 1 ? 'selected' : '' }}>Semi
                                        </option>
                                        <option value="2" {{ old('moisture_level') == 2 ? 'selected' : '' }}>Un Dry
                                        </option>
                                    </select>
                                    @error('moisture_level')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <h5 class="my-2">
                                        <i data-feather="truck" class="font-medium-4 mr-25"></i>
                                        <span class="align-middle">Status</span>
                                </h5>
                                <div class="mb-1 col-md-4">
                                    <label class="form-label" for="status">Status<label class="text-danger px-sm-25">
                                            *</label></label>
                                    <select class="form-select select2" name="status" id="status"
                                        value="{{ old('status') }}">
                                        <!-- <option value="" disabled selected>-Select-</option> -->
                                        <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Schedule</option>
                                        <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Dispatch</option>
                                        <option value="2" {{ old('status') == 2 ? 'selected' : '' }}>Received</option>
                                    </select>
                                    @error('status')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-4 @if (old('status') == 0) d-none @endif" id ='dispatch'>
                                    <label class="form-label" for="dispatched_date_time" id>Dispatched Date Time<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" id="fp-default" name="dispatched_date_time"
                                        value="{{ old('dispatched_date_time') }}" class="form-control date-time-picker"
                                        placeholder="DD-MM-YYYY HH:MM:SS" />
                                    @error('dispatched_date_time')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-1 col-md-4 d-none" id='receive'>
                                    <label class="form-label" for="received_date_time">Received Date Time<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" id="fp-default" name="received_date_time"
                                        value="{{ old('received_date_time') }}" class="form-control date-time-picker"
                                        placeholder="DD-MM-YYYY HH:MM:SS" />
                                    @error('received_date_time')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div> 
                                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                    <a href="{{ route('admin.stockyard') }}"><button
                                            class="dt-button buttons-collection btn btn-outline-secondary me-2" tabindex="0"
                                            aria-controls="DataTables_Table_0" type="button"
                                            aria-haspopup="true"><span>Back</span></button></a>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                            <input type="hidden" name="stockyard_slug" value="{{ $stockyard_slug }}">
                            <input type="hidden" name="stockyard_rcn_stock_slug" value="{{ $stockyardrcn_slug }}"
                                id="stockyardRcnSlug">
                            <input type="hidden" name="stockyard_rcn_lot_number" id="stockyardRcnLotNumber"
                                value="{{ $stockyard_rcn_data->lot_number }}">
                            <input type="hidden" name="slug" id="slug">
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
    <script src="{{ asset(mix('js/scripts/forms/form-input-mask.js')) }}">
    </script>
    <script>
        $(document).ready(function() {
            var sealNumber, slug, lotNumber;
            sealNumber = $('#seal_number').val();

            $("#status").on('change', function() {
                var status = $("#status").val();
                if (status == 0) {
                    $("#dispatch, #receive").addClass('d-none');
                } else if (status == 1) {
                    $("#receive").addClass('d-none');
                    $("#dispatch").removeClass('d-none');
                } else {
                    $("#dispatch, #receive").removeClass('d-none');
                }
            });

            if (sealNumber) {
                generateSlug(sealNumber);
            }

            $(".date-time-picker").flatpickr({
                dateFormat: "d-m-Y H:i:00",
                enableTime: true,
            });

            $('#seal_number').focusout(function() {
                sealNumber = $(this).val();
                generateSlug(sealNumber);
            });

            //Slug Generation
            function generateSlug(sealNumber) {
                lotNumber = $('#stockyardRcnLotNumber').val();
                slug = lotNumber + '-' + sealNumber;
                if (sealNumber) {
                    $('#slug').val(slug);
                }
            }
        })
    </script>
@endsection
