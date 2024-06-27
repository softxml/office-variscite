jQuery(function($){

    console.log('loaded');

    $(".wpColorPicker").wpColorPicker();

    // COPY TEXT & STUFF FROM DESKTOP
    function form2form(formA, formB) {
        $(":input[name]", formA).each(function() {
            var mobileFldName = $(this).attr("name").replace("][", "][mobile_");
            console.log(mobileFldName);
            $("[name=\'" + mobileFldName +"\']", formB).val($(this).val())
        });
    }

    $(".copyToMobile").click(function() {
        form2form(".desktop-settings", ".mobile-settings");
    });


    $(".main_text").trumbowyg({
        resetCss: true,
        btns: [
            ["viewHTML"],
            ["formatting"],
            "btnGrp-semantic",
            ["link"],
            ["insertImage"],
            "btnGrp-justify",
            "btnGrp-lists",
            ["fontsize"],
            ["foreColor", "backColor"],
            ["horizontalRule"],
            ["removeformat"],
            ["fullscreen"]
        ]
    });

});





jQuery(document).ready(function($) {


    // SELECT 2 MULTIPLE AND NORMAL KICKSTART SCRIPT
    $.fn.extend({
        select2_sortable: function(){
            var select = $(this);
            $(select).select2({
                width: "100%",
                createTag: function(params) {
                    return undefined;
                },
                ajax: {
                        url: ajaxurl, // AJAX URL is predefined in WordPress admin
                        dataType: "json",
                        delay: 250, // delay in ms while typing when to perform a AJAX search
                        data: function (params) {
                            return {
                                q: params.term, // search query
                                action: "sgetposts" // AJAX action for admin-ajax.php
                            };
                        },
                        processResults: function( data ) {
                        var options = [];
                        if ( data ) {
        
                            // data is the array of arrays, and each of them contains ID and the Label of the option
                            $.each( data, function( index, text ) { // do not forget that "index" is just auto incremented value
                                options.push( { id: text[0], text: text[1]  } );
                            });
        
                        }
                        return {
                            results: options
                        };
                    },
                    cache: true
                },
                allowClear: true,
                minimumResultsForSearch: -1,
                minimumInputLength: 3 // the minimum of symbols to input before perform a search
            });
            var ul = $(select).next(".select2-container").first("ul.select2-selection__rendered");
            ul.sortable({
                placeholder : "ui-state-highlight",
                forcePlaceholderSize: true,
                items       : "li:not(.select2-search__field)",
                tolerance   : "pointer",
                stop 		: function() {
                    $( $(ul).find(".select2-selection__choice").get().reverse() ).each(function() {
                        console.log(this);
                        var title = $(this).attr("title");
                        var option = $(select).find( "option:contains(" + title + ")" );
                        console.log(option);
                        $(select).prepend(option);
                    });
                }
            });
        }
    });


    $(".select2posts").each(function(){
        $(this).select2_sortable();
    })

    $(".select2-selection__rendered").click(function(){
        $(this).parents('label').find('.select2posts').val('').trigger('change');
    })



});