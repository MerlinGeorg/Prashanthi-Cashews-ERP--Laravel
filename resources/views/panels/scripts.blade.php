<!-- BEGIN: Vendor JS-->
<script src="{{ asset(mix('vendors/js/vendors.min.js')) }}"></script>
<!-- BEGIN Vendor JS-->
<!-- BEGIN: Page Vendor JS-->
<script src="{{ asset(mix('vendors/js/ui/jquery.sticky.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
@yield('vendor-script')
<!-- END: Page Vendor JS-->
<!-- BEGIN: Theme JS-->
<script src="{{ asset(mix('js/core/app-menu.js')) }}"></script>
<script src="{{ asset(mix('js/core/app.js')) }}"></script>

<!-- custome scripts file for user -->
<script src="{{ asset(mix('js/core/scripts.js')) }}"></script>

@if ($configData['blankPage'] === false)
    <script src="{{ asset(mix('js/scripts/customizer.js')) }}"></script>
@endif
<!-- END: Theme JS-->
<!-- BEGIN: Page JS-->
@yield('page-script')

<script src="{{ asset(mix('js/scripts/forms/form-select2.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>
<!-- END: Page JS-->
<script>
    $(document).ready(function() {
        $("select.form-control").select2();

        $(document).on('keydown', '.alphanumeric', function(e) {
            var a = e.key;
            if (a.length == 1) return /[a-z]|[0-9]/i.test(a);
            return true;
        });

        @if (Session::has('success'))
            toastr['success'](
            '{{ Session::get('success') }}',
            'Success!',
            {
            closeButton: true,
            tapToDismiss: false,
            }
            );
        @endif

        @if (Session::has('error'))
            toastr['error'](
            '{{ Session::get('error') }}',
            'Error!',
            {
            closeButton: true,
            tapToDismiss: false,
            }
            );
        @endif
    });
</script>
