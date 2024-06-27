jQuery(function($) {
    var checkMobile = function() {
        var isTouch = ('ontouchstart' in document.documentElement);

        if ( isTouch ) {
            $('html').addClass('touch');
        }
        else {
            $('html').addClass('no-touch');
        }
    };
    checkMobile();

    function slideIcons() {
        if($('.lp-banner-icons').length > 0) {
            var $item = $('.lp-banner-icon-wrap'),
                visible = 2,
                index = 0,
                endIndex = ( $item.length / visible );

            $('.slide-right').click(function() {
                if(index < endIndex ) {
                    index++;
                    $item.animate({'left':'-=50%'});
                }
            });

            $('.slide-left').click(function() {
                if(index > 0) {
                    index--;
                    $item.animate({'left':'+=50%'});
                }
            });
        }
    }

    $(document).ready(function() {
        $('.lp-quote-btn .btn').on('click', function() {
            $('html, body').animate({scrollTop: $('#getQuote').offset().top}, 1000);
        });
        $('.lp-to-top .btn').on('click', function(e) {
            e.preventDefault();
            $('html, body').animate({scrollTop: '0px'}, 1000);
        })
        slideIcons();
    });

    $(window).on('load', function() {
        if($('.lp-banner-icons, .lp-quote-btn .btn').length > 0) {
            $('.lp-banner-icons, .lp-quote-btn .btn').addClass('loaded');
        }
    });

    $(window).on('scroll', function() {
        var getQuote = $('#getQuote'),
            productsRow = $('.products-row');

        if ($(window).scrollTop() > getQuote.offset().top) {
            $('.lp-quote-btn .btn').addClass('fade-out');
        } else {
            $('.lp-quote-btn .btn').removeClass('fade-out');
        }

        if ($(window).scrollTop() > productsRow.offset().top) {
            $('.lp-to-top .btn').addClass('fade-in');
        } else {
            $('.lp-to-top .btn').removeClass('fade-in');
        }
    });
});