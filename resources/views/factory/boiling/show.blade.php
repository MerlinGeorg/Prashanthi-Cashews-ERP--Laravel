@extends('layouts/contentLayoutMaster ')
@section('title', 'Boiling Stock Details')
@section('content')
    <div class="">
        <div class="card">
            <div class="card-header p-1 border-bottom">
                <div class="head-label">
                    <h4 class="card-title">Boiling Stock Details - <strong>{{ $boiling->boiling_number }}</strong></h4>
                </div>
                <div class="dt-action-buttons text-end">
                    <div class="dt-buttons d-inline-flex">
                        <a href="{{ route('factory.boiling') }}"
                            class="dt-button buttons-collection btn btn-outline-secondary me-2">
                            <span>Back</span>
                        </a>
                        @if (\Helper::userAccess('factory-boiling-edit'))
                            <a href="{{ route('factory.boiling.edit', $boiling->slug) }}"
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
                            <span class="form-control">{{ $boiling->factory->factory_name }} </span>
                        </div>
                    </div>
                    <div class="mb-1 col-md-3">
                        <label class="form-label" for="factory_stock_slug">Lot Number</label>
                        <span class="form-control">
                            {{ $boiling->stockyardRcnStock->lot_number ?? '' }} </span>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">Recieved Date Time</label>
                            <span class="form-control">{{ $boiling->boiling_date_time->format('d-m-Y') }} </span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">Total Bags</label>
                            <span class="form-control">{{ $boiling->total_boiling_weight ?? 0 }} Bags </span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">Balance Bags</label>
                            <span class="form-control">{{ $boiling->balance_boiling_weight ?? 0 }} Bags </span>
                        </div>
                    </div>
                </div>


                <div class="row col-12">
                    <h5 class="my-2">
                        <i data-feather="filter" class="font-medium-4 mr-25"></i>
                        <span class="align-middle">Boiling Input</span>
                    </h5>
                </div>
                <div class="row">
                    <div class="col-12">
                        <table class="list-table table table-bordered">
                            <thead>
                                <tr>
                                    <th>Sizering Number</th>
                                    @foreach (Config::get('constants.grades') as $name => $grade)
                                        <th>{{ $name }} </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>

                                @if ($boiling->boilingMap)
                                    @foreach ($boiling->boilingMap as $boilsizer)
                                        <tr>
                                            <td>{{ $boilsizer->sizering->sizering_number }}</td>
                                            @foreach (Config::get('constants.grades') as $name => $grade)
                                                <td>{{ $boilsizer->{"{$grade}"} > 0 ? $boilsizer->{"{$grade}"} . ' Kgs' : '' }}
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row col-12">
                    <h5 class="my-2">
                        <i data-feather="filter" class="font-medium-4 mr-25"></i>
                        <span class="align-middle">Boiling Output</span>
                    </h5>
                </div>
                <div class="row">
                    <div class="col-12">
                        <table class="list-table table table-bordered">
                            <thead>
                                <tr>
                                    @foreach (Config::get('constants.grades') as $name => $grade)
                                        <th>{{ $name }} </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    @foreach (Config::get('constants.grades') as $name => $grade)
                                        <td>{{ $boiling->{"{$grade}_total_weight"} > 0 ? $boiling->{"{$grade}_total_weight"} . ' Bags' : '' }}
                                        </td>
                                    @endforeach

                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
