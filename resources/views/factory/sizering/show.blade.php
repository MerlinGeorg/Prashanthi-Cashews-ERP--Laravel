@extends('layouts/contentLayoutMaster ')
@section('title', 'Sizering Stock Details')
@section('content')
    <div class="">
        <div class="card">
            <div class="card-header p-1 border-bottom">
                <div class="head-label">
                    <h4 class="card-title">Sizering Stock Details - <strong>{{ $sizering->sizering_number }}</strong>
                    </h4>
                </div>
                <div class="dt-action-buttons text-end">
                    <div class="dt-buttons d-inline-flex">
                        <a href="{{ route('factory.sizering') }}"
                            class="dt-button buttons-collection btn btn-outline-secondary me-2">
                            <span>Back</span>
                        </a>
                        @if (\Helper::userAccess('factory-sizering-edit') && $sizering->boilingMap->count() == 0)
                            <a href="{{ route('factory.sizering.edit', $sizering->slug) }}"
                                class="dt-button create-new btn btn-primary">
                                <span><i data-feather="edit"></i> Edit</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row col-12">
                    <h5 class="my-2">
                        <i data-feather="server" class="font-medium-4 mr-25"></i>
                        <span class="align-middle">Factory Stock</span>
                    </h5>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Factory Name</label>
                            <span class="form-control">{{ $sizering->factory->factory_name }} </span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Lot Number</label>
                            <span class="form-control">{{ $sizering->stockyardRcnStock->lot_number ?? '' }} </span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Recieved Date Time</label>
                            <span class="form-control">{{ $sizering->sizering_date_time->format('d-m-Y') }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row col-12">
                    <h5 class="my-2">
                        <i data-feather="filter" class="font-medium-4 mr-25"></i>
                        <span class="align-middle">Sizering Input Stock</span>
                    </h5>
                </div>
                <div class="row border p-1 m-0 col-md-6">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">RCN Weight</label>
                            <span class="form-control">{{ $sizering->rcn_weight }} </span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">RCN Bag</label>
                            <span class="form-control">{{ $sizering->rcn_bag }} </span>
                        </div>
                    </div>
                </div>
                <div class="row col-12">
                    <h5 class="my-2">
                        <i data-feather="download" class="font-medium-4 mr-25"></i>
                        <span class="align-middle">Sizering Output Stock</span>
                    </h5>
                </div>

                <div class="row">
                    <div class="col-md-10 border row m-1 p-1 mr-0">
                        <h5> Total - <span class="badge bg-success">
                                {{ $sizering->total_sizering_rcn_stock ?? 0 }}
                                Kg </span></h5>
                        @foreach (Config::get('constants.grades') as $name => $grade)
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label">{{ $name }}</label>
                                    <span class="form-control">{{ $sizering->{"{$grade}_total_weight"} ?? 0 }} Kg
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="col-md-10 border row m-1 p-1 mr-0">
                        <h5> Balance - <span class="badge bg-danger">
                                {{ $sizering->balance_sizering_rcn_stock ?? 0 }}
                                Kg </span></h5>
                        @foreach (Config::get('constants.grades') as $name => $grade)
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label">{{ $name }}</label>
                                    <span class="form-control">{{ $sizering->{"{$grade}_balance_weight"} ?? 0 }} Kg
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 border row m-1 p-1 mr-0">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="form-label">Foreign Matter</label>
                                <span class="form-control">{{ $sizering->foreign_matter_total_weight ?? 0 }} </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
