@extends('layouts/contentLayoutMaster ')
@section('title', 'Cutting Stock Details')
@section('content')
    <div class="row">
        <div class="card">
            <div class="card-header p-1">
                <div class="head-label">
                    <h4 class="card-title">Cutting Stock Details - <strong>{{ $cutting->cutting_work_number }}</strong></h4>
                </div>
                <div class="dt-action-buttons text-end">
                    <div class="dt-buttons d-inline-flex"> <a href="{{ route('factory.cutting') }}"><button
                                class="dt-button buttons-collection btn btn-outline-secondary me-2" tabindex="0"
                                aria-controls="DataTables_Table_0" type="button"
                                aria-haspopup="true"><span>Back</span></button></a><a
                            href="{{ route('factory.cutting.edit', $cutting->slug) }}"><button
                                class="dt-button create-new btn btn-primary" tabindex="0"
                                aria-controls="DataTables_Table_0" type="button" data-bs-toggle="modal"
                                data-bs-target="#modals-slide-in"><span>Edit</span></button></a> </div>
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

                            <span class="form-control">{{ $cutting->factory->factory_name }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Recieved Date</label>
                            <span class="form-control">{{ $cutting->cutting_date_time->format('d/m/Y') }}</span>

                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Balance Stock</label>
                            <span class="form-control">{{ $cutting->balance_cutting_rcn_stock }} Kg</span>

                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Cutting Type</label>
                            <span class="form-control">{{ ucwords($cutting->cutting_type) }}</span>

                        </div>
                    </div>
                </div>


                <div class="row col-12">
                    <h5 class="my-2">
                        <i data-feather="filter" class="font-medium-4 mr-25"></i>
                        <span class="align-middle">Cutting Input</span>
                    </h5>
                </div>
                @if ($cutting->cutting_type == 'traditional')
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Given RCN Bag</label>
                                <span class="form-control">{{ $cutting->given_rcn_bag }}</span>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Given RCN Weight</label>
                                <span class="form-control">{{ $cutting->given_rcn_weight }}</span>

                            </div>
                        </div>
                    </div>
                @endif
                @if ($cutting->cutting_type == 'machinery')
                    <?php
                    
                    $state_val_count = old('boiling_stocks') ? sizeof(old('boiling_stocks')) : 1;
                    $state_val_count = sizeof($cutting->cuttingMap);
                    
                    ?>
                    <div class="row">
                        <div class="col-12">
                            <table class="list-table table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Boiling Number</th>
                                        @foreach (Config::get('constants.grades') as $name => $grade)
                                            <th>{{ $name }} </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>

                                    @for ($i = 0; $i < $state_val_count; $i++)
                                        <tr>
                                            <td>
                                                @foreach ($factory_boilers as $boil)
                                                    @if ($boil->slug == $cutting->cuttingMap[$i]->boiling_slug)
                                                        {{ $boil->boiling_number }}
                                                    @endif
                                                @endforeach
                                            </td>
                                            @foreach (Config::get('constants.grades') as $name => $grade)
                                                <td>
                                                    {{ $cutting->cuttingMap[$i]->{$grade} }}
                                                </td>
                                            @endforeach



                                        </tr>
                                    @endfor

                                </tbody>
                            </table>
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
                    <div class="col-12">
                        <table class="list-table table table-bordered">
                            <thead>
                                <tr>
                                    <th>wholes</th>
                                    <th>brokens</th>
                                    <th>piruwel</th>
                                    <th>rejection</th>
                                    <th>uncut</th>
                                    <th>unscoop</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $cutting->wholes > 0 ? $cutting->wholes . ' Kg' : '' }}</td>
                                    <td>{{ $cutting->brokens > 0 ? $cutting->brokens . ' Kg' : '' }}</td>
                                    <td>{{ $cutting->piruwel > 0 ? $cutting->piruwel . ' Kg' : '' }}</td>
                                    <td>{{ $cutting->rejection > 0 ? $cutting->rejection . ' Kg' : '' }}</td>
                                    <td>{{ $cutting->uncut > 0 ? $cutting->uncut . ' Kg' : '' }}</td>
                                    <td>{{ $cutting->unscoop > 0 ? $cutting->unscoop . ' Kg' : '' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
