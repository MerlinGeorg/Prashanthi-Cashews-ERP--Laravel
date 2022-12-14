// select2
$('.select2').each(function () {
    var $this = $(this);
    $this.wrap('<div class="position-relative"></div>');
    $this
    .select2({
        placeholder: $this.attr('placeholder') || 'Select value',
        dropdownParent: $this.parent()
    })
    .change(function () {
        $(this).valid();
    });
});