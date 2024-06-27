(function ($) {
    'use strict';

    $.extend(true, $.trumbowyg, {
        langs: {
            // jshint camelcase:false
            en: {
                fontSize: {
                    '14': '14px',
                    '16': '16px',
                    '18': '18px',
                    '20': '20px',
                    '22': '22px',
                    '24': '24px',
                    '26': '26px',
                    '28': '28px',
                    '30': '30px',
                    '32': '32px',
                    '34': '34px',
                    '36': '36px',
                    '38': '38px',
                    '40': '40px',
                    '42': '42px',
                    '44': '44px',
                    '46': '46px',
                    '48': '48px',
                    '50': '50px',
                    '52': '52px',
                    '54': '54px',
                    '56': '56px',
                    '58': '58px',
                    '60': '60px',
                }
            }
        }
    });
    // jshint camelcase:true

    // Add dropdown with font sizes
    $.extend(true, $.trumbowyg, {
        plugins: {
            fontsize: {
                init: function (trumbowyg) {
                    trumbowyg.addBtnDef('fontsize', {
                        dropdown: buildDropdown(trumbowyg)
                    });
                }
            }
        }
    });
    function buildDropdown(trumbowyg) {
        var dropdown = [];
        var sizes = ['14', '16', '18', '20', '22', '24', '26', '28', '30', '32', '34', '36', '38', '40', '42', '46', '48', '50', '52', '54', '56', '58', '60'];

        $.each(sizes, function(index, size) {
            trumbowyg.addBtnDef('fontsize_' + size, {
                text: '<span style="font-size: ' + size + 'px;">' + trumbowyg.lang.fontSize[size] + '</span>',
                hasIcon: false,
                fn: function(){
                    trumbowyg.expandRange();
                    trumbowyg.execCmd('fontSize', index+1, true);
                }
            });
            dropdown.push('fontsize_' + size);
        });

        return dropdown;
    }
})(jQuery);
