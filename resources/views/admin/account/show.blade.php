@extends('layouts/contentLayoutMaster ')
@section('title', 'Account Details')
@section('content')
    <div class="">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-1 border-bottom">
                    <div class="head-label">
                        <h4 class="card-title">Account Details - <strong>{{ $account->account_name }}</strong></h4>
                    </div>
                    <div class="dt-action-buttons text-end">
                        <div class="dt-buttons d-inline-flex">
                            <a href="{{ route('admin.account') }}"
                                class="dt-button buttons-collection btn btn-outline-secondary me-2">
                                <span>Back</span>
                            </a>
                            @if (\Helper::userAccess('office-account-edit'))
                                <a href="{{ route('admin.account.edit', $account->slug) }}"
                                    class="dt-button create-new btn btn-primary">
                                    <span><i data-feather="edit"></i> Edit</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="align-items-center my-1">
                            <i data-feather="user"> </i>
                            <strong> Account Informations</strong>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Name</label>
                                <span class="form-control">{{ $account->account_name }} </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Short Name</label>
                                <span class="form-control">{{ $account->account_short_name }} </span>
                            </div>
                        </div>
                    </div>
                    <div class="align-items-center my-1">
                        <i data-feather="lock"> </i>
                        <strong> GST Informations</strong>
                    </div>
                    @foreach ($account->subAccounts as $subaccount)
                        <div class="border p-2 mb-1">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="form-label">State</label>
                                    <span class="form-control">{{ $subaccount->account_state }} </span>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="form-label">GST</label>
                                    <span class="form-control">{{ $subaccount->account_gst }}
                                    </span>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="form-label">Address Line 1</label>
                                    <span class="form-control">{{ $subaccount->account_address_1 }}
                                    </span>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="form-label">Address Line 2</label>
                                    <span class="form-control">{{ $subaccount->account_address_2 }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

@endsection
