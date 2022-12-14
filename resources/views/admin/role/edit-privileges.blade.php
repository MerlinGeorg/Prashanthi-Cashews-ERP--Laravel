@extends('layouts/contentLayoutMaster')

@section('title', 'Edit Privileges')

@section('content')
    <section id="multiple-column-form">
        <h4 class="card-title"><em class=" font-medium-1 ">Permissions for</em>
            {{ $role->name }} ( {{ $role->workLocationType() }} )
        </h4>
        <form action="{{ route('admin.role.privileges.save', $role->slug) }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-sm-12">
                    <div role="tablist" aria-multiselectable="false">
                        <div class="accordion" id="accordionACL">
                            @foreach ($work_location_types as $work_location => $name)
                                <div class="accordion-item1">
                                    <h2 class="accordion-header1" id="{{ $work_location }}">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#accordion-{{ $work_location }}" aria-expanded="false"
                                            aria-controls="accordionOne">
                                            {{ $name }}
                                        </button>
                                    </h2>
                                    <div id="accordion-{{ $work_location }}" class="accordion-collapse collapse "
                                        aria-labelledby="{{ $work_location }}" data-bs-parent="#accordionACL">
                                        <div class="accordion-body row">
                                            @if (isset($acl[$work_location]))
                                                @foreach ($acl[$work_location] as $resource)
                                                    @php
                                                        $prev_work_location_type = $resource->work_location_type;
                                                    @endphp
                                                    <div class="col-md-3">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <div
                                                                    class=" d-flex justify-content-between border-bottom mb-1">
                                                                    <h5>{{ $resource->resource_name }}</h5>
                                                                    <div class="form-check mb-1">
                                                                        <input type="checkbox"
                                                                            class="form-check-input select-all"
                                                                            id="select-all-{{ $resource->slug }}"
                                                                            @if ($resource->permissions->count() == count($role_permissions[$resource->slug] ?? [])) checked @endif
                                                                            data-slug="{{ $resource->slug }}"
                                                                            @if (!$change_access) disabled @endif />
                                                                        <label class="form-check-label text-right"
                                                                            for="select-all-{{ $resource->slug }}">
                                                                            All</label>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    @foreach ($resource->permissions as $permission)
                                                                        <div class="col-md-12">
                                                                            <div
                                                                                class="form-check form-check-inline form-check-primary my-25">
                                                                                <input type="checkbox"
                                                                                    class="form-check-input {{ $resource->slug }}"
                                                                                    data-parent="{{ $resource->slug }}"
                                                                                    name="permissions[]"
                                                                                    id="permission-{{ $permission->slug }}"
                                                                                    @if (!$change_access) disabled @endif
                                                                                    value="{{ $permission->slug }}"
                                                                                    @if (in_array($permission->slug, $role_permissions[$resource->slug] ?? [])) checked @endif />
                                                                                <label class="form-check-label"
                                                                                    for="permission-{{ $permission->slug }}">{{ $permission->name }}</label>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-center">
                <a href="{{ route('admin.role') }}"
                    class="dt-button buttons-collection btn btn-outline-secondary me-2 waves-effect">
                    Back </a>
                @if ($change_access)
                    <button type="submit" class="btn btn-primary"> Submit</button>
                @endif
            </div>
        </form>
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
    <script src="{{ asset(mix('js/scripts/components/components-accordion.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/form-input-mask.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/app/state-account.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/form-input-mask.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/forms/repeater/jquery.repeater.min.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/app/role/role.js')) }}"></script>
    <script>
        $(document).ready(function() {
            $(".select-all").on('change', function() {
                var $this = $(this);
                var $selector = $("." + $(this).data('slug'));

                if ($this.prop('checked')) {
                    $selector.prop('checked', true);
                } else {
                    $selector.prop('checked', false);
                }
            });

            $(".form-check-input").on("change", function() {
                var $class = $(this).data('parent');
                var total_count = $("input." + $class).length;
                var selected_count = $("input." + $class + ":checked").length;

                if (total_count == selected_count)
                    $("#select-all-" + $class).prop('checked', true);
                else
                    $("#select-all-" + $class).prop('checked', false);
            });
        });
    </script>

@endsection
