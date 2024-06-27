function setCookie(cname, cvalue) {
    var d = new Date();
    d.setTime(d.getTime() + (30*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}
function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    return vars;
}

var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
};


jQuery(function($) {

    $('.filterParamsExpend').click(function (e) {
        e.preventDefault();
        if(!$(this).hasClass('expended')) {
            $(this).addClass('expended');
            $(this).siblings().children('.checkboxes-box-inner-hidden').slideDown('fast');
        } else {
            $(this).removeClass('expended');
            $(this).siblings().children('.checkboxes-box-inner-hidden').slideUp('fast');
        }
    });

    window.catFilters = {};
    catFilters.checked = 0;
    catFilters.values = '';

    function specsFiltersAjax(){
        $.ajax(
            {
                type: "POST",
                dataType: "JSON",
                url: ajax_object.ajaxurl,
                data: {
                    'action': 'specs_filter_ajaxfunc',
                    'action_type': 'filter_products',
                    'filter_cat': specsData.term,
                    'filter_cats': catFilters.values
                },
                complete: function(results) {
                    var results = JSON.parse(results.responseText);
                    var success = results.success;
                    var data    = results.data;
                    var count   = parseInt(data.count);

                    if(success) {
                        var posts   = data.posts;
                        var noindex = data.noindex;

                        $('.filter-products-loop > .row').html(posts);
                        $('.wp-pagenavi').addClass('dnone');
                        $('.pgnavi-box').fadeOut();
                    }
                    else {
                        $('.filter-products-loop > .row').html("");
                        $('.nothing-found').removeClass('dnone');
                        $('.pgnavi-box').fadeOut();
                    }

                    if(count > 0) {
                        if (getLangCode == 'it') {
                            $('.filter-products-infobar .page-location').text('Risultati 1-' + count + ' di ' + count);
                        } else if (getLangCode == 'de') {
                            $('.filter-products-infobar .page-location').text('Zeige alle ' + count + ' Ergebnisse an');
                        } else {
                            $('.filter-products-infobar .page-location').text('Showing all ' + count + ' results');
                        }
                    } else {
                        $('.filter-products-infobar .page-location').text('');
                    }


                },
            }).responseJSON;
    }

    function checkCatFilters(pageload){
        catFilters.checked = 0;
        catFilters.values = '';
        catFilters.names = '';
        var separator = '';
        $('.checkbox-wrap').each(function(){
            if(catFilters.checked !== 0){separator = ',';}
            if($('input', this).is(":checked")){
                catFilters.checked ++;
                catFilters.values += separator + $('input', this).val();
                catFilters.names += separator + $('input', this).data('name');
            }
        });
        if(catFilters.checked > 0){
            if (history.pushState && !pageload) {
                var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?prod_spec_cats=' + catFilters.values;
                window.history.pushState({path:newurl},'',newurl);
            }
            $('.filter-page .filter-box .filters-controll-panel').slideDown();
            // BEGGINING OF FUNCTION TO SHOW FILTERS IN CLEAR ALL BOX
            // NEED TO CHANGE OTHER LOGIC TO SAVE FILTER IDS IN ORDER SELECTED INSTEAD OF REWRITING EACH TIME
            // MEANWHILE THIS IS NOT NEEDED AS THERE IS ONLY ONE TYPE OF FILTER
            // $('.filter-page .filter-box .filters-controll-panel .filters-list').html('');
            // var count = 0;
            // catfiltersArr = catFilters.values.split(',');
            // catfilterNamesArr = catFilters.names.split(',');
            // catfiltersArr.forEach(function(){
            //     $('.filter-page .filter-box .filters-controll-panel .filters-list').append(
            //         '<li class="col-md-6 btn-filter" >' +
            //         '<span class="removeFilter"><i class="fa fa-times-circle tip" title="Remove"></i></span>' +
            //         '<a href="'+ catfiltersArr[count] +'" class="filter-link">' + catfilterNamesArr[count] + '</a>' +
            //         '</li>'
            //     );
            //     count ++;
            // });
        } else {
            var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname;
            window.history.pushState({path:newurl},'',newurl);
            $('.filter-page .filter-box .filters-controll-panel').slideUp();
        }
        specsFiltersAjax();
    }

    if(getUrlParameter('prod_spec_cats')) {
        catFilters.values = getUrlParameter('prod_spec_cats');
        catfiltersArr = catFilters.values.split(',');
        $('.checkbox-wrap').each(function () {
            if($.inArray($('input', this).val(), catfiltersArr) !== -1){
                $('input', this).attr('checked', true);
            }
        });
        checkCatFilters(true);
    }

    $('button.btn.btn-link.clearFilters').click(function(){
        $('.checkbox-wrap input').each(function(){
            $(this).attr('checked', false);
        });
        checkCatFilters(false);
        $('.filter-page .filter-box .filters-controll-panel').slideUp();
    });

    $('.checkboxes-box .checkbox-wrap').change(function(){
        checkCatFilters(false);
    });

});