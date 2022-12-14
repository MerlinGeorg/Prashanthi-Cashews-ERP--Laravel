@extends('layouts/contentLayoutMaster')

@section('title', 'User Group List')

@section('vendor-style')
    {{-- vendor css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/rowGroup.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/sweetalert2.min.css')) }}">
@endsection

@section('content')
    <section id="office-page">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class=" border-bottom p-1">
                        <div class="head-label row">
                            <div class="col-md-4">

                            </div>
                            @if (\Helper::userAccess('office-user-group-add'))
                                <div class="col-md-8 align-items-center text-end">
                                    <div class="dt-buttons">
                                        <a href="{{ route('admin.usergroup.create') }}"
                                            class="dt-button create-new btn btn-primary"><i data-feather="plus"></i>Add</a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <table class="list-table table">
                        <thead>
                            <tr>
                                <th>User Group Name</th>
                                <th>Slug</th>
                                <th width="20%">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <!--/ Basic table -->


@endsection


@section('vendor-script')
    {{-- vendor files --}}
    <script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/jquery.dataTables.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.buttons.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.bootstrap5.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.responsive.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/responsive.bootstrap5.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
@endsection
@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/app/usergroup/list.js')) }}"></script>

@endsection
