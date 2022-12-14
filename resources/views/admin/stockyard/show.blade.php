@extends('layouts/contentLayoutMaster ')
@section('title', 'Stockyard Details')
@section('content')
    <div class="row">
        <div class="card">
            <div class="card-header p-1 border-bottom mb-1">
                <div class="head-label">
                    <h4 class="card-title">Stockyard - <strong>{{ $stockyard->stockyard_name }}</strong></h4>
                </div>
                <div class="dt-action-buttons text-end">
                    <div class="dt-buttons d-inline-flex">
                        <a href="{{ route('admin.stockyard') }}"
                            class="dt-button buttons-collection btn btn-outline-secondary me-2">
                            <span>Back</span>
                        </a>
                        @if (\Helper::userAccess('office-stockyard-edit'))
                            <a href="{{ route('admin.stockyard.edit', $stockyard->slug) }}"
                                class="dt-button create-new btn btn-primary">
                                <span><i data-feather="edit"></i> Edit</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Name</label>
                            <span class="form-control">{{ $stockyard->stockyard_name }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Short Name</label>
                            <span class="form-control">{{ $stockyard->stockyard_short_name }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Registration / Door No </label>
                            <span class="form-control">{{ $stockyard->stockyard_reg_number }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Office</label>
                            <span class="form-control">{{ $stockyard->office->office_name }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">State</label>
                            <span class="form-control">{{ $stockyard->stockyard_state }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Account</label>
                            <span class="form-control">{{ $stockyard->subaccount->account->account_name }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Address Line 1</label>
                            <span class="form-control">{{ $stockyard->contact_address_1 }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Address Line 2</label>
                            <span class="form-control">{{ $stockyard->contact_address_2 }} </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Pincode</label>
                            <span class="form-control">{{ $stockyard->stockyard_pincode }} </span>
                        </div>
                    </div>
                </div>

                <div class="align-items-center my-1">
                    <i data-feather="lock"> </i>
                    <strong> Warehouse Informations</strong>
                </div>

                <div class="row">
                    @foreach ($stockyard->warehouses as $warehouse)
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Warehouse Name</label>
                                <span class="form-control">{{ $warehouse->warehouse_name }} </span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Account</label>
                                <span class="form-control">{{ $warehouse->subaccount->account->account_name }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
