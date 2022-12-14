@extends('layouts/contentLayoutMaster')

@section('title', 'Staff')

@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/toastr.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/sweetalert2.min.css')) }}">
@endsection
@section('page-style')
    <!-- Page css files -->
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/extensions/ext-component-toastr.css')) }}">
@endsection

@section('content')

    <section id="user-page">
        <div class="row">
            <div class=" col-md-3">
                <div class="card">
                    <div class="card-header flex-row justify-content-between">
                        <div class="avatar bg-light-success p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="users" class="font-medium-4"></i>
                            </div>
                        </div>
                        <div class="">
                            <h2 class="fw-bolder text-end">{{ $active_users }}</h2>
                            <p class="card-text">Active Staff</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class=" col-md-3">
                <div class="card">
                    <div class="card-header flex-row justify-content-between">
                        <div class="avatar bg-light-warning p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="users" class="font-medium-4"></i>
                            </div>
                        </div>
                        <div class="">
                            <h2 class="fw-bolder text-end">{{ $pending_users }}</h2>
                            <p class="card-text">Pending Staff</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class=" col-md-3">
                <div class="card">
                    <div class="card-header flex-row justify-content-between">
                        <div class="avatar bg-light-danger p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="users" class="font-medium-4"></i>
                            </div>
                        </div>
                        <div class="">
                            <h2 class="fw-bolder text-end">{{ $inactive_users }}</h2>
                            <p class="card-text">Inactive Staff</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class=" col-md-3">
                <div class="card">
                    <div class="card-header flex-row justify-content-between">
                        <div class="avatar bg-light-info p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="users" class="font-medium-4"></i>
                            </div>
                        </div>
                        <div class="">
                            <h2 class="fw-bolder text-end">{{ $total_users }}</h2>
                            <p class="card-text">Total Staff</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- List DataTable -->
        <div class="row">
            <div class="col-12">
                <div class="card invoice-list-wrapper table-responsive">
                    @if (\Helper::userAccess('office-staff-add'))
                        <div class="card-header border-bottom p-1">
                            <div class="head-label">
                                <h6 class="mb-0"></h6>
                            </div>
                            <div class="dt-action-buttons align-items-end">
                                <div class="dt-buttons d-inline-flex">
                                    <a href="{{ route('admin.staff.create') }}"
                                        class="dt-button create-new btn btn-primary"><i data-feather="plus"></i>Add</a>
                                </div>
                            </div>
                        </div>
                    @endif
                    <table class="list-table table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Employee No</th>
                                <th>Staff Name</th>
                                <th class="text-truncate">Roles</th>
                                <th>User Group</th>
                                <th>Username</th>
                                <th>Status</th>
                                <th class="cell-fit">Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <!--/ List DataTable -->
    </section>

@endsection

@section('vendor-script')
    <!-- vendor files -->
    <script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/jquery.dataTables.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.buttons.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.bootstrap5.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.responsive.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/responsive.bootstrap5.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
@endsection
@section('page-script')
    <!-- Page js files -->
    <script src="{{ asset(mix('js/scripts/app/user/list.js')) }}"></script>
@endsection
