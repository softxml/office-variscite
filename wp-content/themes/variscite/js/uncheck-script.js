(function($) {
    $(window).load(function() {
        var wpmlCheckbox = $(':checkbox[value="synchronise"]');

        if (wpmlCheckbox.length > 0) {
            if (wpmlCheckbox.is(':checked')) {
                wpmlCheckbox.attr('checked', false);
            }
        }
    });
}(jQuery));