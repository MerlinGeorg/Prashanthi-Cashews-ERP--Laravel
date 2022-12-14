@extends('layouts/contentLayoutMaster')

@section('title', 'View RCN Stock - Mix')

@section('content')
    <section id="multiple-column-form">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header p-1 border-bottom">
                        <div class="head-label">
                            <h4 class="card-title">RCN Stock - <strong>{{ $stockyard_rcn_data->lot_number }}</strong>
                            </h4>
                        </div>
                        <div class="dt-action-buttons text-end">
                            <div class="dt-buttons d-inline-flex">
                                <a href="{{ route('stockyard.rcn-stock') }}"
                                    class="dt-button buttons-collection btn btn-outline-secondary me-2 waves-effect">Back</a>
                                @if (\Helper::userAccess('stockyard-rcn-edit'))
                                    <a href="{{ route('stockyard.rcn-stock.edit_mix', $stockyard_rcn_data->slug) }}"
                                        class="btn btn-primary">
                                        <i data-feather="edit"></i> Edit
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body ">
                        <form action="{{ route('stockyard.rcn-stocks.updateMix', $stockyard_rcn_data->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row mt-1">
                                
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="stockyard_slug">Stockyard</label>
                                    <span class="form-control" >{{ $stockyard_rcn_data->stockyardDetails->stockyard_name }}</span>
                            
                                </div>
      
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="lot_number">Lot No.</label>
                                    <span class="form-control" >{{ $stockyard_rcn_data->lot_number }}</span>

                                 
                                </div>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="lot_number">Account Lot No.</label>
                                    <span class="form-control" >{{ $stockyard_rcn_data->account_lot_number }}</span>
                                </div>
                    
                                <h5 class="my-2">
                                    <i data-feather="database" class="font-medium-4 mr-25"></i>
                                    <span class="align-middle">Stock Details</span>
                                </h5>
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="account_id">Account</label>
                                    <span class="form-control" >{{$stockyard_rcn_data->account['account_name']  }}</span>

                                
                                </div> 
                                <div class="mb-1 col-md-6">
                                    <label class="form-label" for="sub_account_id">Sub Account</label>
                                    <span class="form-control" name="sub_account_id" id="subAccountList"
                                        value="{{$sub_account->id}}">
                                        {{ $sub_account->account_state }}
                                </span>
                               
                                </div>
                                <div class="mb-1 col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">RCN Weight(Kg) </label>
                                        <span  name="rcn_kg" id="rcn_kg" value="{{ old('rcn_kg',$stockyard_rcn_data->bl_despatched_rcn_weight) }}"
                                        class="form-control number-mask"/>
                                        {{$stockyard_rcn_data->bl_despatched_rcn_weight}}
                                        </span>
                                     
                                    </div>
                                </div>
                                <div class="mb-1 col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Bags</label>
                                        <span type="number" name="rcn_bags" id="rcn_bags" value="{{ old('rcn_bags',(int)$stockyard_rcn_data->bl_despatched_rcn_bags) }}"
                                        class="form-control number-mask">
                                        {{$stockyard_rcn_data->bl_despatched_rcn_bags}}
                                        </span>
                                    
                                    </div>
                                </div>
                               
                        

                           
                          
                                      <div class="mb-1 ">
                                    <label class="form-label" for="lot_number">Parent Lot Numbers</label>
                                    @foreach ($lot_numbers as $lot_numbers)
                                        
                                       
                    <div class="row"> <div class="col-md-6">
                                    <span class="form-control mt-2 " >{{ $lot_numbers->stockyard_rcn_stock_slug }}</span> </div><div class="col-md-3">   Rcn Bags<span class="form-control col-md-3" >{{ $lot_numbers->rcn_bags }}</span> </div>
                                    <div class="col-md-3"> Rcn Weight <span class="form-control col-md-3" >{{ $lot_numbers->rcn_weight }}</span> </div>
                             </div>  
                                    <input type="hidden" name="lot_number" id="lot_number" class="form-control"
                                        value="{{ $lot_numbers->stockyard_rcn_stock_slug }}" readonly /> 
                                    @endforeach

                                   
                                </div>
              
                         
                                <div class="row">
                                    <div class="col-12">
                                  
                                        <h5 class="my-2">
                                            <i data-feather="database" class="font-medium-4 mr-25"></i>
                                            <span class="align-middle">Current Stock</span>
                                        </h5>
                                       
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">RCN  </label>
                                            <span class="form-control">{{ $stockyard_rcn_data->balance_rcn_stock }} Kg</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Bags</label>
                                            <span class="form-control">{{(int) $stockyard_rcn_data->balance_rcn_bag }} </span>
                                        </div>
                                    </div>
                                    
                                </div>
                                @if (\Helper::userAccess('stockyard-rcn-adjust-rcn-current-stock'))
                               
                                @endif
                              
                            </div>
                            
                            <input type="hidden" name="slug" id="slug" value="{{ $stockyard_rcn_data->slug }}">
                            <input type="hidden" name="account_lot_number" id="account_lot_number" value="{{$stockyard_rcn_data->account_lot_number}}">
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
    <script>
        $(document).ready(function() {
            fetchWarehouses();

            $('#accountList').on('change', function(e) {
                var acc_slug = e.target.value;
                $.ajax({
                    url: "{{ url('stockyard/rcn-stock/sub-account-list') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "acc_slug": acc_slug
                    },
                    success: function(response) {
                        $('select[name="sub_account_id"]').empty();
                        $.each(response.sub_accounts, function(key, value) {
                            $('select[name="sub_account_id"]').append(
                                '<option value=" ' + value
                                .id + '">' + value.account_state + '</option>');
                        })
                    }
                })
            });

            // $('#stockyardList').on('change', function(e) {
            //     fetchWarehouses();
            // });

            function fetchWarehouses() {
                var stockyard_slug =  "{{$stockyard_rcn_data->stockyard_slug}}";
                $.ajax({
                    url: "{{ url('admin/stockyard/warehouses') }}/" + stockyard_slug,
                    type: "GET",
                    success: function(response) {
                        let $warehouse = $('#warehouse');
                        let selected = $warehouse.data('selected');
                        $warehouse.html('<option value=""> -Select- </option>');
                        $.each(response, function(slug, name) {
                            $warehouse.append('<option value="' + slug + '" >' +
                                name + '</option>');
                        });
                        $warehouse.val(selected).select2();
                    }

                })
            }
        })
    </script>
@endsection
