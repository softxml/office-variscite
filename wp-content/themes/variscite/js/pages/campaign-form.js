(function($) {
    $('.open-form-popup').on('click', function() {
        $('.lp-campaign .panel-grid-cell:nth-child(2)').addClass('form-popup-opened');
    });
    $('.close-form-popup').on('click', function() {
        $('.lp-campaign .panel-grid-cell:nth-child(2)').removeClass('form-popup-opened');
    });
}(jQuery));