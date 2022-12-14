@extends('layouts/contentLayoutMaster ')
@section('title', 'Borma Stock Details')
@section('content')
    <div class="">
        <div class="card">
            <div class="card-header p-1 border-bottom">
                <div class="head-label">
                    <h4 class="card-title">Borma Stock Details - <strong>{{ $borma->borma_number }}</strong></h4>
                </div>
                <div class="dt-action-buttons text-end">
                    <div class="dt-buttons d-inline-flex">
                        <a href="{{ route('factory.borma') }}"
                            class="dt-button buttons-collection btn btn-outline-secondary me-2">
                            <span>Back</span>
                        </a>
                        @if (\Helper::userAccess('factory-borma-edit'))
                            <a href="{{ route('factory.borma.edit', $borma->slug) }}"
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
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Factory Name</label>
                            <span class="form-control">{{ $borma->factoryDetails->factory_name }} </span>
                        </div>
                    </div>
                    <div class="mb-1 col-md-3">
                        <label class="form-label" for="factory_stock_slug">Lot Number</label>
                        <span class="form-control">
                            {{ $borma->stockyardRcnStock->lot_number ?? '' }} </span>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Recieved Date Time</label>
                            <span class="form-control">{{ $borma->borma_work_date_time->format('d-m-Y') }} </span>
                        </div>
                    </div>
                </div>


                <div class="row col-12">
                    <h5 class="my-2">
                        <i data-feather="filter" class="font-medium-4 mr-25"></i>
                        <span class="align-middle">Borma Input</span>
                    </h5>
                </div>
                <div class="row">
                    <div class="row">
                        <div class="row border m-0 m-1 p-1 col-md-6">
                            {{-- <div class="mb-1 col-md-4 ">
                                <label class="form-label" for="wholes">Cutting Stock</label>
                                <span class="form-control">{{ $borma->cutting_stock }} </span>
                            </div>
                            <div class="mb-1 col-md-4 ">
                                <label class="form-label" for="wholes">Wholes</label>
                                <span class="form-control">{{ $borma->wholes }} </span>
                            </div>
                            <div class="mb-1 col-md-4">
                                <label class="form-label" for="brokens">Brokens</label>
                                <span class="form-control">{{ $borma->brokens }} </span>
                            </div>
                            <div class="mb-1 col-md-4">
                                <label class="form-label" for="piruwal">Piruwal</label>
                                <span class="form-control">{{ $borma->piruwal }} </span>
                            </div> --}}

                        </div>
                    </div>
                </div>

                <div class="row col-12">
                    <h5 class="my-2">
                        <i data-feather="filter" class="font-medium-4 mr-25"></i>
                        <span class="align-middle">Borma Output</span>
                    </h5>
                </div>
                <div class="row">
                    <div class="row border m-0 m-1 p-1 col-md-6">
                        <div class="mb-1 col-md-4 ">
                            <label class="form-label" for="wholes">Wholes</label>
                            <span class="form-control">{{ $borma->wholes }} </span>
                        </div>
                        <div class="mb-1 col-md-4">
                            <label class="form-label" for="brokens">Brokens</label>
                            <span class="form-control">{{ $borma->brokens }} </span>
                        </div>
                        <div class="mb-1 col-md-4">
                            <label class="form-label" for="piruwal">Piruwal</label>
                            <span class="form-control">{{ $borma->piruwal }} </span>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
