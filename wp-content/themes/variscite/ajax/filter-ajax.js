/*************************************************
** FUNC: GRAB ALL URL PARAMTERS
*************************************************/
function getUrlVars() { var vars = [], hash; var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&'); for(var i = 0; i < hashes.length; i++) { hash = hashes[i].split('='); vars.push(hash[0]); vars[hash[0]] = hash[1]; } return vars; }


/*********************************************
** CHECK IF NO INDEX META TAG EXISTS
*********************************************/
function isItNoIndexed() { var noIndexMeta = document.querySelector('meta[content="noindex, follow"]'); if(!noIndexMeta) { return false; } else { return true; } }


jQuery(function($){



    /*************************************************
    ** FUNC: BUILD A FILTER LIST ITEM (QUICK SIDEFUNC)
    *************************************************/
    function toggleFiltersList() {
        var listLength = $('.filters-list').children('li').length;

        if(listLength > 0) { $('.filters-controll-panel').slideDown('fast'); }
        else { $('.filters-controll-panel').slideUp('fast'); }
	}


    /*************************************************
    ** FUNC: BUILD URL BASED ON SELECTED PARAMS
    **       AND REPLACE CURRENT QUERY.
    *************************************************/
    function ajaxPushNewUrl() {

		var filter_params 	= {};
		var gotourl 		= '';

		$('.filters-list li').each(function(key, value) {

			// <li class="col-md-6 btn-filter" data-source="spec" field-id="nxp-i.mx7">
			// <li class="col-md-6 btn-filter" data-source="spec" data-spec="cpu-name" field-type="checkbox" field-id="nxp-i.mx6" field-val="NXP i.MX6">

			var comma           = ',';
			var data_source     = $(this).attr('data-source').trim();
			var data_spec       = $(this).attr('data-spec');
			var field_id        = $(this).attr('field-id');
			var field_val		= $(this).attr('field-val');

			if(!filter_params.hasOwnProperty(data_spec)) { filter_params[data_spec] = []; }

			filter_params[data_spec][key] = field_val;
		});


		var finalparams = '';
		var urlparams = $.map( filter_params, function( value, key ) {
			ckey 	= filter_params[key] + '';
			params 	= ckey.split(',');

			finalparams = finalparams + key + '=' + params + '&';
			finalparams = finalparams.replace('=,,', '=');
			finalparams = finalparams.replace('=,', '=');
		});

		newurl = window.location.href.split('?')[0];
        newurl = newurl + '?' + finalparams;

        if (newurl.lastIndexOf('&') == newurl.length - 1){
            newurl = newurl.substring(0, newurl.length - 1);
        }

        window.history.pushState('object', document.title, newurl);

        $(window).bind('popstate', function(){
            window.location.href = window.location.href;
        });
	}


    /*********************************************
    ** REMOVE DUPLICATES IN LOW RESULTS
    ** INCASE WE NEED TO DO SO.
    *********************************************/
    function removeDuplicateFeaturedProducts(count) {
        if( count <= $('#lowResultsAmount').val() ) {
            $('.low-amount .row .filter-pitem').each(function() {
                var fprodid = $(this).attr('data-prodid');
                $(this).removeClass('dnone');

                if( $('.filter-products-loop  .row .filter-pitem[data-prodid="'+fprodid+'"]').length ) {
                    $(this).addClass('dnone');
                }
            });
            $('.low-amount').fadeIn('fast');
        }
        else { $('.low-amount').fadeOut('fast'); }
    }


	/*********************************************
	** AJAX FILTER
	*********************************************/
	$(''+
        '.ajax-filter input,' +
        '.ajax-filter .apply-range, ' +
        '.ajax-filter .filterBtnIconHover')
        .click(function() {

        if( !$('.nothing-found').hasClass('dnone') ) { $('.nothing-found').addClass('dnone'); }

		// FIRST RECORD SOME PRE-DEFINED DATA
        var filter_data = [];

        // ADD or REMOVE CLICKED ELEMENT TO FILTER-LIST
        if( $(this).attr('type') == 'checkbox') {
            var data_label          = $("label[for='"+$(this).attr('id')+"']").text();
            var data_source         = $(this).parent().parent().attr('data-source');
            // var data_spec           = $(this).parent().parent().attr('data-spec');
            var field_type          = $(this).attr('type');
            var field_id            = $(this).attr('id');
            var field_val			= $(this).val();


            // FIELD ID OF PARENT AND SOMETIMES SUB PARENT DETERMINES THE TARGET META_KEY...
            var data_spec   = $(this).parents('.parent-group').attr('id');


			if( $('.filters-list li[field-id="'+field_id+'"]').length > 0 ) {
				$('.filters-list li[field-id="'+field_id+'"]').remove();
			}
			else {
				var filter_listItem = '<li class="col-md-6 btn-filter" data-source="'+data_source+'" data-spec="'+data_spec+'" field-type="'+field_type+'" field-id="'+field_id+'" field-val="'+field_val+'"> <span class="removeFilter"><i class="fa fa-times-circle tip" title="Remove"></i></span> <a href="#" class="filter-link">'+data_label+'</span></a></li>';
				$('.filters-list').append(filter_listItem);
            }
        }
        else if($(this).hasClass('filterBtnIconHover')) {
            var data_label          = $(this).attr('data-name');
            var data_source         = $(this).parent().attr('data-source');
            var data_spec           = $(this).parents('.collapse').attr('id');
            var field_type          = 'btngroup';
            var field_id            = $(this).attr('id');
            var field_val			= $(this).val();


			if( $('.filters-list li[field-id="'+field_id+'"]').length > 0 ) {
                $('.filters-list li[field-id="'+field_id+'"]').remove();
                $(this).removeClass('active');
			}
			else {
                // first remove other in same button family
                if( $('li[data-spec="'+data_spec+'"]').length > 0 ) {
                    $('li[data-spec="'+data_spec+'"]').remove();
                }

                // clean other buttons active class
                $(this).parents().find('.filterBtnIconHover').removeClass('active');

                // APPEND NEW ITEM
				var filter_listItem = '<li class="col-md-6 btn-filter" data-source="'+data_source+'" data-spec="'+data_spec+'" field-type="'+field_type+'" field-id="'+field_id+'" field-val="'+field_val+'"> <span class="removeFilter"><i class="fa fa-times-circle tip" title="Remove"></i></span> <a href="#" class="filter-link">'+data_label+'</span></a></li>';
                $('.filters-list').append(filter_listItem);
                $(this).addClass('active');
            }
        }
        else if( $(this).hasClass('apply-range') ) {

            var rangeId 		= $(this).attr('data-range');
            var botvalStr 		= $(rangeId).find('.noUi-handle-lower').text();  				botvalStr = botvalStr.replace(' ', '');
            var topvalStr 		= $(rangeId).find('.noUi-handle-upper').text();  				topvalStr = topvalStr.replace(' ', '');
            var botval 			= Math.round( $(rangeId).find('.noUi-handle-lower').attr('aria-valuetext') );
            var topval 			= Math.round( $(rangeId).find('.noUi-handle-upper').attr('aria-valuetext') );

            // set button data (For ajax)
            $(this).attr('data-from', botval);
            $(this).attr('data-to', topval);

            // Collect needed data
            // var data_label		= $(this).parents('.collapse-wrap').find('.collapse-head a').text() + ': ' + topvalStr + ' - ' + botvalStr;
            var data_label		= $(this).parents('.collapse-wrap').find('.collapse-head a').text();
            var data_source		= $(this).parents('.range-wrap').attr('data-source');
            var data_spec		= $(this).parents('.collapse').attr('id');
            var field_id		= $(this).attr('id');
            var field_val		= $(this).attr('data-from') + '~' + $(this).attr('data-to');


            var filter_listItem = '<li class="col-md-6 btn-filter" data-source="'+data_source+'" data-spec="'+data_spec+'" field-type="range" field-id="'+field_id+'" field-val="'+field_val+'"> <span class="removeFilter"><i class="fa fa-times-circle tip" title="Remove"></i></span> <a href="#" class="filter-link">'+data_label+'</span></a></li>';

            if( $('.filters-list li[field-id="'+field_id+'"]').length > 0 ) {
                $('.filters-list li[field-id="'+field_id+'"]').replaceWith(filter_listItem);
            }
            else {
                $('.filters-list').append(filter_listItem);
            }
        }

        toggleFiltersList();
        ajaxPushNewUrl();


		// BUILD PARAMS FROM LIST
		$('.filters-list li').each(function(key, value) {

			// <li class="col-md-6 btn-filter" data-source="spec" field-id="nxp-i.mx7">
			// <li class="col-md-6 btn-filter" data-source="spec" data-spec="cpu-name" field-type="checkbox" field-id="nxp-i.mx6" field-val="NXP i.MX6">

			var comma           = ',';
			var data_source     = $(this).attr('data-source').trim();
			var data_spec       = $(this).attr('data-spec');
			var field_id        = $(this).attr('field-id');
			var field_val		= $(this).attr('field-val');

			// filter_data[key] = data_source + comma + data_spec + comma + field_id + comma + field_val;
			filter_data[key] = [data_source, data_spec, field_id, field_val];
        });

        // SEND DATA VIA AJAX
        $.ajax(
            {
            type: "POST",
            dataType: "JSON",
            url: ajax_object.ajaxurl,
            data: {
                'action': 'filter_ajaxfunc',
                'action_type': 'filter_products',
                'filter_pageid': $('#page_id').val(),
                'filter_cats': $('#cats').val(),
                'filter_data': filter_data
            },
            complete: function(results) {
                // console.log(results);
                var results = JSON.parse(results.responseText);
                var success = results.success;
                var data    = results.data;
                var time    = results.data.time;
                var count   = parseInt(data.count);

                if(success) {
                    var posts   = data.posts;
                    var noindex = data.noindex;

                    $('.filter-products-loop > .row').html(posts);
                    $('.wp-pagenavi').addClass('dnone');
                    $('.pgnavi-box').fadeOut();
                    removeDuplicateFeaturedProducts(count);
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

	});

});