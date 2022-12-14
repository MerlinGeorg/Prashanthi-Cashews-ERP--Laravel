@extends('layouts/contentLayoutMaster')

@section('title', 'Factory RCN Inward')

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
                        <form action="{{ route('factory-rcn-inward.update', $factory_rcn_inward_data->id) }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Truck Reg No.</label>
                                        <span
                                            class="form-control">{{ $factory_rcn_inward_data->outwardRcnDetails->truck_reg_number }}
                                        </span>
                                    </div>

                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Lot No.</label>
                                        <span
                                            class="form-control">{{ $factory_rcn_inward_data->outwardRcnDetails->stockyardRcnStockDetails->lot_number }}
                                        </span>
                                    </div>

                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Factory</label>
                                        <span class="form-control">{{ $factory_rcn_inward_data->factory->factory_name }}
                                        </span>
                                    </div>

                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Contact No.</label>
                                        <span
                                            class="form-control">{{ $factory_rcn_inward_data->outwardRcnDetails->contact_number }}
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">DC No.</label>
                                        <span
                                            class="form-control">{{ $factory_rcn_inward_data->outwardRcnDetails->dc_number }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">EWB No.</label>
                                        <span
                                            class="form-control">{{ $factory_rcn_inward_data->outwardRcnDetails->ewb_number }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <h5 class="my-2">
                                        <i data-feather="package" class="font-medium-4 mr-25"></i>
                                        <span class="align-middle">GD-Details</span>
                                    </h5>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">RCN Bags (No.s)</label>
                                        <span
                                            class="form-control">{{ (int) $factory_rcn_inward_data->outwardRcnDetails->rcn_bags }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">RCN Net Weight</label>
                                        <span
                                            class="form-control">{{ $factory_rcn_inward_data->outwardRcnDetails->rcn_net_weight }}
                                            Kg
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Tare Weight</label>
                                        <span
                                            class="form-control">{{ $factory_rcn_inward_data->outwardRcnDetails->tare_weight }}
                                            Kg
                                        </span>
                                    </div>
                                </div>


                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Out Turn </label>
                                        <span
                                            class="form-control">{{ $factory_rcn_inward_data->outwardRcnDetails->out_turn }}
                                            Lbs
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Nut Count(No.s)</label>
                                        <span
                                            class="form-control">{{ $factory_rcn_inward_data->outwardRcnDetails->nut_count }}
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Rejection</label>
                                        <span
                                            class="form-control">{{ $factory_rcn_inward_data->outwardRcnDetails->rejection }}
                                            %
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">Moisture Level</label>
                                        <span class="form-control">
                                            {{ $factory_rcn_inward_data->outwardRcnDetails->moisture_level == 0? 'Dry': ($factory_rcn_inward_data->outwardRcnDetails->moisture_level == 1? 'Semi': 'Un Dry') }}
                                        </span>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <h5 class="my-2">
                                        <i data-feather="package" class="font-medium-4 mr-25"></i>
                                        <span class="align-middle">GR-Details</span>
                                    </h5>
                                </div>

                                <div class="mb-1 col-md-3">
                                    <label class="form-label" for="rcn_net_weight">Received RCN Weight (Kg)<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" name="rcn_net_weight" id="rcn_net_weight"
                                       
                                        class="form-control" />
                                    @error('rcn_net_weight')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-1 col-md-3">
                                    <label class="form-label" for="rcn_bags">Received RCN Bags<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="number" name="rcn_bags" id="rcn_bags"
                                    
                                        class="form-control" />
                                    @error('rcn_bags')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-1 col-md-3">
                                    <label class="form-label" for="status">Status<label class="text-danger px-sm-25">
                                            *</label></label>
                                    <select class="form-select select2" name="status" id="status"
                                        value="{{ old('status') }}">
                                        @foreach (\Helper::inWardOutWardConditionalStatus($factory_rcn_inward_data->outwardRcnDetails->status) as $index => $status)
                                            <option value="{{ $index }}" @if (old('status', $factory_rcn_inward_data->outwardRcnDetails->status) == $index) selected='selected' @endif>
                                                {{ $status }}</option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-1 col-md-3 @if (old('status', $factory_rcn_inward_data->outwardRcnDetails->status) != 2) d-none @endif" id="receive">
                                    <label class="form-label" for="received_date_time">Received Date Time<label
                                            class="text-danger px-sm-25"> *</label></label>
                                    <input type="text" name="received_date_time" id="received_date_time"
                                        value="{{ old('received_date_time',$factory_rcn_inward_data->outwardRcnDetails->received_date_time? $factory_rcn_inward_data->outwardRcnDetails->received_date_time->format('d-m-Y h:i:s'): '') }}"
                                        class="form-control date-time-picker" />
                                    @error('received_date_time')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                    <a href="{{ route('factory.factory-rcn-inward') }}"><button
                                            class="dt-button buttons-collection btn btn-outline-secondary me-2" tabindex="0"
                                            aria-controls="DataTables_Table_0" type="button"
                                            aria-haspopup="true"><span>Back</span></button></a>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </div>
                            <input type="hidden" name="slug" id="slug" value="{{ $factory_rcn_inward_data->slug }}">
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
        jQuery(function($) {
            $(".date-time-picker").flatpickr({
                dateFormat: "d-m-Y H:i:s",
                enableTime: true,
            });

            var outward_id, factoryShortName, slug, status;
            $('#factoryList, #outwardList').on('change', function(e) {
                outward_id = $('#outwardList :selected').val();
                factoryShortName = $('#factoryList :selected').data('short-name');

                $.ajax({
                    url: "{{ url('/stockyard/factory-rcn-inward/get-rcn-stock') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "outward_id": outward_id
                    },
                    success: function(response) {
                        if (response.result.stockyard_rcn_stock_details.lot_number) {
                            slug = 'IN-' + factoryShortName + '-' + response.result
                                .stockyard_rcn_stock_details.lot_number;
                            $('#slug').val(slug);
                        } else {
                            $('#slug').val("");
                        }
                    }
                })
            });

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
        });
    </script>
@endsection
