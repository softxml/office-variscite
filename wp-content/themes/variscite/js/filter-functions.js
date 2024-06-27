/*************************************************
 ** FUNC: GRAB ALL URL PARAMTERS
 *************************************************/
 function getUrlVars() {
	var vars = [], hash;
	var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');

	for(var i = 0; i < hashes.length; i++) {
		//hash = hashes[i].split(',');
		hash = hashes[i].split('=');
		vars.push(hash[0]);
		vars[hash[0]] = hash[1];
	}
	return vars;
}


/*********************************************
 ** FUNC: REPLACE ALL IN STRING
 *********************************************/
// String.prototype.replaceAll = function(search, replacement) {
// 	var target = this;
// 	return target.replace(new RegExp(search, 'g'), replacement);
// };



/*********************************************
 ** FUNC: TRIM FOR JS
 *********************************************/
String.prototype.trim = function () {
	return this.replace(/^\s+|\s+$/g, '');
};



/*************************************************
 ** STRING TO ID
 *************************************************/
function str2id(str) {
	if(str) {
		str = str.toLowerCase();
		str = str.replace(' ', '_');
	}
	return str;
}



jQuery(document).ready(function($) {

	urlVarsTemp = getUrlVars();
	if( $(urlVarsTemp).length < 2 ){
		$('.filter-tabs-wrap input[type="checkbox"]').each(function() {
			$(this).removeAttr('checked');
		});
	}


	/*********************************************
	 ** TOGGLE FILTER LIST
	 *********************************************/
	function toggleFiltersList() {
		var listLength = $('.filters-list').children('li').length;

		if(listLength > 0) { $('.filters-controll-panel').slideDown('fast'); }
		else { $('.filters-controll-panel').slideUp('fast'); }
	}


	/*********************************************
	 ** CHECK ALL CHECKBOX BASED ON URL
	 ** AND ADD LINKS TO FILTER LIST
	 *********************************************/
	onload_urlparams_checkboxs = function(){

		checkQuery = window.location.search;

		if(checkQuery) {

			urlPrms = getUrlVars();
			if (urlPrms && urlPrms !== null) {
				$(urlPrms).each(function (key, value) {

					key = value;

					params = decodeURIComponent(urlPrms[value]);
					params = params.replaceAll(',,', ',');
					params = params.split(',');
					base_url = window.location.href.split('?')[0];

					// CHECK CHECBOXES

					$(params).each(function (paramKey, paramVal) {
						if ($('#' + key).length) {
							
							var field_type;
							if(typeof $('#' + key).attr('data-type') !== typeof undefined && $('#' + key).attr('data-type') !== false) {
								field_type = $('#' + key).attr('data-type');
							} else {
								field_type = $('#' + key).closest('.parent-group').attr('data-type');
							}
							// var fld_val = this.replaceAll('%20', ' ');
							var fld_val = paramVal.replaceAll('+', ' ');
							var fld_str;
							var field_id;
							var field_val;
							var data_source;
							var data_group = key;
							var crange = '';


							if(field_type == 'checkbox' || field_type == 'btngroup') {
								$(':checkbox, .filterBtnIconHover').each(function () {
									if($(this).val() == fld_val) {
										fld_str = fld_val;
										if(field_type == 'checkbox') {
											field_id = $('input[value="' + fld_val + '"]').attr('id');
											field_val = $('input[value="' + fld_val + '"]').val();
											data_source = $('input[value="' + fld_val + '"]').parents('.checkboxes-box').attr('data-source');
											$(':checkbox[value="' + fld_val + '"]').prop('checked', 'true');

										} else if (field_type == 'btngroup') {
											field_id = $('button[value="' + fld_val + '"]').attr('id');
											field_val = $('button[value="' + fld_val + '"]').val();
											data_source = $('button[value="' + fld_val + '"]').parents('.btn-group').attr('data-source');
											$('button[value="' + fld_val + '"]').addClass('active');

										}

										var tag_url = base_url + '?' + data_group + '=' + field_val;
										var filter_listItem = '<li class="col-md-6 btn-filter" data-source="' + data_source + '" data-spec="' + data_group + '" field-id="' + field_id + '" field-val="' + field_val + '" field-type="' + field_type + '"> <span class="removeFilter"><i class="fa fa-times-circle tip" title="Remove"></i></span> <a href="' + tag_url + '" class="filter-link">' + fld_str + '</span></a></li>';
										$('.filters-list').append(filter_listItem);
									}
								});
							} else if (field_type == 'range') {
								field_val = this;
								var setrng_val = this.split('~');
								field_id = 'range-' + key;
								crange = document.getElementById(field_id);
								data_source = $('#' + key).parents('.collapse-wrap').find('.range-wrap').attr('data-source');
								fld_str = $('#' + key).parents('.collapse-wrap').find('.collapse-head a').text();

								crange.noUiSlider.updateOptions({
									start: [setrng_val[0], setrng_val[1]],
								});

								var tag_url = base_url + '?' + data_group + '=' + field_val;
								var filter_listItem = '<li class="col-md-6 btn-filter" data-source="' + data_source + '" data-spec="' + data_group + '" field-id="' + field_id + '" field-val="' + field_val + '" field-type="' + field_type + '"> <span class="removeFilter"><i class="fa fa-times-circle tip" title="Remove"></i></span> <a href="' + tag_url + '" class="filter-link">' + fld_str + '</span></a></li>';
								$('.filters-list').append(filter_listItem);
							}
							// var tag_url = base_url + '?' + data_group + '=' + field_val;
							// var filter_listItem = '<li class="col-md-6 btn-filter" data-source="' + data_source + '" data-spec="' + data_group + '" field-id="' + field_id + '" field-val="' + field_val + '" field-type="' + field_type + '"> <span class="removeFilter"><i class="fa fa-times-circle tip" title="Remove"></i></span> <a href="' + tag_url + '" class="filter-link">' + fld_str + '</span></a></li>';
							// $('.filters-list').append(filter_listItem);
							toggleFiltersList();
						}
					});
				});
			}
		}
	}
	onload_urlparams_checkboxs();
});



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
	 ** TEMP NEW "GO TO URL" FUNC
	 *************************************************/
	function buildQueryUrl(reload = false) {

		var filter_params 	= {};
		var gotourl 		= '';

		$('.filters-list li').each(function(key, value) {

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
			params = params.filter(function(v){return v!==''});
			finalparams = finalparams + key + '=' + params + '&';
			finalparams = finalparams.replace('=,', '=');
		});

		if(reload) {
			plink	= $('#page_plink').val();
			gotourl = plink.split('?')[0];
		}
		else { gotourl = window.location.href.split('?')[0]; }

		gotourl = gotourl + '?' + finalparams;
		if (gotourl.lastIndexOf('?') == gotourl.length - 1){
			gotourl = gotourl.substring(0, gotourl.length - 1);
		}
		if (gotourl.lastIndexOf('&') == gotourl.length - 1){
			gotourl = gotourl.substring(0, gotourl.length - 1);
		}
		window.location.href = gotourl;
	}


	/*************************************************
	 ** ADD FILTER TO FILTER CONTROL PANEL
	 ** grey section under search
	 *************************************************/
	$(".refresh-filter input").click(function () {
		// Collect needed data
		var data_label = $("label[for='" + $(this).attr("id") + "']").text();
		var data_source = $(this).parent().parent().attr("data-source");
		// var data_spec           = $(this).parent().parent().attr('data-spec');
		var data_spec = $(this).parents(".collapse.parent-group").attr("id");
		var field_type = $(this).attr("type");
		var field_id = $(this).attr("id");
		var field_val = $(this).val();

		if ($(this).attr("type") == "checkbox") {
			if ($('.filters-list li[field-id="' + field_id + '"]').length > 0) {
				$('.filters-list li[field-id="' + field_id + '"]').remove();
			} else {
				var filter_listItem =
					'<li class="col-md-6 btn-filter" data-source="' +
					data_source +
					'" data-spec="' +
					data_spec +
					'" field-type="' +
					field_type +
					'" field-id="' +
					field_id +
					'" field-val="' +
					field_val +
					'"> <span class="removeFilter"><i class="fa fa-times-circle tip" title="Remove"></i></span> <a href="#" class="filter-link">' +
					data_label +
					"</span></a></li>";
				$(".filters-list").append(filter_listItem);
			}
		}

		toggleFiltersList();
		buildQueryUrl(true);
	});


	/*************************************************
	 ** BUTTON CLICK OS (+ ADD TO FILTER LIST & GO2URL)
	 *************************************************/
	$('.refresh-filter .filterBtnIconHover').click(function() {
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

		toggleFiltersList();
		buildQueryUrl();
	});



	/*************************************************
	 ** APPLY RANGE (+ ADD TO FILTER LIST & GO2URL)
	 *************************************************/
	$('.refresh-filter .apply-range').click(function() {

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

		// toggleFiltersList();
		buildQueryUrl();
	});





	/*************************************************
	 ** REMOVE FILTER BY BUTTON
	 *************************************************/
	$('.removeFilter').live('click', function() {

		var field_type  = $(this).parents('li').attr('field-type');
		var field_id    = $(this).parents('li').attr('field-id');


		if( field_type == 'checkbox' ) {
			$('label[for="'+field_id+'"]').trigger( "click" );
			$('input#' + field_id).prop('checked', false);
		}

		$(this).parents('li').remove();
		toggleFiltersList();

		buildQueryUrl();
	});




	/*************************************************
	 ** CLEAR FILTERS
	 *************************************************/
	$('.clearFilters').click(function() {
		window.location.href = window.location.href.split('?')[0];
	});



	/*********************************************
	 ** MATCH HEIGHTS
	 *********************************************/
	var fpro_height     = $('.not-mobile .filter-page-body .container-wrap').height();
	var ftabs_height    = $('.not-mobile .filter-page-body .filter-tabs-wrap').height();

	// if(fpro_height > ftabs_height) {$('.filter-page-body .filter-tabs-wrap').css('height', fpro_height);}
	// else  {$('.filter-page-body .filter-box').css('height', ftabs_height);}
	$('.filter-page-body .filter-box').css('height', ftabs_height);		// RECENT CHANGE



	/*********************************************
	 ** PRODUCT GRID / ROW VARIATION
	 *********************************************/
	$('.product-layout').click(function() {
		$('.product-layout').each(function() { $(this).removeClass('active'); });

		var action = $(this).attr('id');

		$(this).addClass('active');
		$('.filter-products-wrap').attr('data-viewstate', action);
	});





	/*********************************************
	 ** MOBILE: TOGGLE FILTER SIDEBAR
	 *********************************************/
	$('.toggleFilterbar , .btn.innertoggleFilterbar').click(function() {
		$('.filter-box').toggleClass('openMobile');
		$('button.btn.toggleFilterbar').toggleClass('toggleOpen');
		$('div.filter_mobile_wrap').toggleClass('closed');
		$('div.filter_mobile_wrap_overlay').toggleClass('active');
	});
	// $('.btn.innertoggleFilterbar').click(function(){
	// 	$('.filter-box').toggleClass('openMobile');
	// });



});