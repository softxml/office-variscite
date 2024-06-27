jQuery(function ($) {
	/*************************************************
	 ** HANDLE SPECS PAGE FORM SUBMISSION
	 *************************************************/

	window.dataLayer = window.dataLayer || [];

	function validateEmail(email) {
		var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(String(email).toLowerCase());
	}



	function submitQuoteForm(formBtn, formId, actionType, isAJAX = true) {

		formBtn.click(function () {
			formBtn.parent().toggleClass('btnLoader');

			if (isAJAX) {
				formBtn.attr('disabled', 'disabled');
				formBtn.prop('disabled', true);
			} else {

				setTimeout(function () {
					formBtn.attr('disabled', 'disabled');
					formBtn.prop('disabled', true);
				}, 200);
			}
		});

		if (isAJAX) {

			formBtn.click(function () {

				function push2DataLayer(fieldsObj) {
					var customEvent = {'event': 'contact_form_submit','customer_id':''};
					for (var key in fieldsObj) {
						if (fieldsObj.hasOwnProperty(key)) {
						  var curKey = key;
						  var curValue = fieldsObj[key];
						  switch (curKey) {
							case "email":
								customEvent[curKey] = curValue;
							  break;
							case "phone":
								customEvent[curKey+"_number"] = curValue;
							  break;
							case "first_name":
								customEvent["address."+curKey] = curValue;
							  break;
							case "last_name":
								customEvent["address."+curKey] = curValue;
							  break;
						   }
						}
					  }
					  dataLayer.push(customEvent);
					  console.log( dataLayer );
				}

				// COLLECT VALUES
				var form = document.getElementById(formId);
				var iEle = form.querySelectorAll('input, select, checkbox, textarea');
				var notice = $(form).find('.notice');
				var checks = false;

				var fldVals = {};

				$.each(iEle, function (index, value) {
					fldType = iEle[index].type;

					if (fldType == 'hidden' || fldType == 'text' || fldType == 'textarea') {
						fldVals[iEle[index].id] = iEle[index].value;
					}
					else if (fldType == 'select-one') {
						fldVals[iEle[index].id] = $('#' + iEle[index].id).val();
					}
					else if (fldType == 'checkbox') {
						if ($('#' + iEle[index].id).is(':checked')) {
							if (!fldVals[iEle[index].name]) { fldVals[iEle[index].name] = ''; }
							fldVals[iEle[index].name] += $('#' + iEle[index].id).val() + ';';
						}
					}
				});

				fldVals['curl'] = window.location.href;
				fldVals['Page_url__c'] = window.location.href;
				// console.log(fldVals);

				// CHECK IF REQUIRED
				var reqFields = fldVals.required.split(','),
					fieldMessage;

				$(reqFields).each(function (index, value) {
					if (value === 'email') {
						var emailVal = $('#email').val();
						if (!validateEmail(emailVal)) {
							checks = true;
							fieldMessage = 'A Valid Email';
							return false;
						}
					}
					if (value === 'first_name' || value === 'last_name' || value === 'company' || value === 'phone') {
						var nameVal = $('#first_name').val(),
							lnameVal = $('#last_name').val(),
							companyVal = $('#company').val(),
							phoneVal = $('#phone').val();

						if (nameVal === '') {
							checks = true;
							fieldMessage = 'First Name';
							return false;
						}
						if (lnameVal === '') {
							checks = true;
							fieldMessage = 'Last Name';
							return false;
						}
						if (companyVal === '') {
							checks = true;
							fieldMessage = 'Company Name';
							return false;
						}
						if (phoneVal === '') {
							checks = true;
							fieldMessage = 'Phone Number';
							return false;
						}
					}
					if (value === 'country_code') {
						if ($('select[name="country_code"]').val() === '') {
							checks = true;
							fieldMessage = 'Country';
							return false;
						}
					}
					if (value === 'country') {
						if ($('select[name="country"]').val() === '') {
							checks = true;
							fieldMessage = 'Country';
							return false;
						}
					}
					if (!fldVals[value]) {
						checks = true;
						inputid = value;
						fieldMessage = $('#' + inputid).attr('placeholder');
						return false;
					}
				});

				if (formId == 'prodQuoteForm') {
					var cpuPlatform = $('div.paddons-checkbox.required :checkbox:checked').length;
					var cpuPlatformField = $('div.paddons-checkbox.required').length;

					var fields_warn = "Please select Estimated Quantities";
					var somVals = [];
					/// roi 14.06.23 #1///
					var modules_warn = 'Please select Platform';
					/// roi 14.06.23 - #1 end///

					$.each($('input[name="quote-product"]:checked'), function () {
						somVals.push($(this).val());
						fldVals['quote-product'] = somVals.join(";");
					});	// COLLECT SELECTED SOMS CHECKBOXES

					/// roi 14.06.23 #2///
					if (cpuPlatformField > 0 && cpuPlatform < 1) {
						$('.quote-form .notice').html('<i class="fa fa-exclamation-triangle c6"></i> ' + modules_warn);
						checks = true;
						formBtn.attr('disabled', false);
						formBtn.parent().toggleClass('btnLoader');
						return false;
					}
					/// roi 14.06.23 #2 -end///

					var amntVals = $('select[name="quote-quantity"]').val();
					fldVals['quote-quantity'] = amntVals;

					if (amntVals === '') {
						$('.quote-form .notice').html('<i class="fa fa-exclamation-triangle c6"></i> ' + fields_warn);
						formBtn.attr('disabled', false);
						formBtn.parent().toggleClass('btnLoader');
						checks = true;
						return false;
					}
				}

				/// roi 14.06.23 #4///
				if (formId == 'quoteFormWidget') {
					var cpuPlatform = $('#som-checkboxes :checkbox:checked').length;
					var cpuPlatformField = $('#som-checkboxes').length;

					var fields_warn = "Please select Estimated Project Quantities";
					var somVals = [];

					var modules_warn = 'Please select SoM Platform';

					$.each($('input[name="System__c"]:checked'), function () {
						somVals.push($(this).val());
						fldVals['System__c'] = somVals.join(";");
					});	// COLLECT SELECTED SOMS CHECKBOXES

					if (cpuPlatformField > 0 && cpuPlatform < 1) {
						$('.quote-form .notice').html('<i class="fa fa-exclamation-triangle c6"></i> ' + modules_warn);
						checks = true;
						formBtn.attr('disabled', false);
						formBtn.parent().toggleClass('btnLoader');
						return false;
					}

					var amntVals = $('select[name="Projected_Quantities__c"]').val();
					fldVals['Projected_Quantities__c'] = amntVals;

					if (amntVals === '') {
						$('.quote-form .notice').html('<i class="fa fa-exclamation-triangle c6"></i> ' + fields_warn);
						formBtn.attr('disabled', false);
						formBtn.parent().toggleClass('btnLoader');
						checks = true;
						return false;
					}
				}
				/// roi 14.06.23 #4 -end///

				if (checks === true) {
					$(notice).html('<i class="fa fa-exclamation-triangle c6"></i> ' + fieldMessage + ' is Required');
					formBtn.attr('disabled', false);
					formBtn.parent().toggleClass('btnLoader');
					return false;
				} else {
					// SEND FORM
					$.post(ajax_object.ajaxurl, {
						action: 'global_ajaxfunc',
						action_type: actionType,
						form_data: fldVals,
					}, function (res) {
						if (res.success) {
							setTimeout(function () {
								formBtn.attr('disabled', false);
								formBtn.parent().toggleClass('btnLoader');
							}, 2000);
							
							// Push to dataLayer


							var event_name = $(form).find('#event_name').val();

							if (event_name) {
								push2DataLayer(fldVals);
								dataLayer.push({ 'event': event_name });
							}

							var thanksPageUrl = $(form).find('#thanks').val();
							window.location.href = thanksPageUrl;
						}
						else {
							formBtn.attr('disabled', false);
							formBtn.parent().toggleClass('btnLoader');
							notice.html('<i class="fa fa-exclamation-triangle c6"></i> ' + res.data.msg);
						}
					});
				}
			});
		}
	}

	// Check whether the URL contains the ?noajax param to submit the form without AJAX
	var curr_url = new URL(window.location.href),
		params = new URLSearchParams(curr_url.search);

	submitQuoteForm(
		$('.submitQuoteWidgetRequest'),
		'quoteFormWidget',
		'send_widget_quote',
		!params.has('noajax')
	);

	submitQuoteForm(
		$('.submitQuoteRequest'),
		'prodQuoteForm',
		'send_quote',
		!params.has('noajax')
	);

	/* added contact form popup submit request function*/
	function submitPopupForm(formBtn, formId, actionType) {
		formBtn.click(function () {

			formBtn.attr('disabled', 'disabled');
			formBtn.prop('disabled', true);
			formBtn.parent().toggleClass('btnLoader');

			// COLLECT VALUES
			var form = document.getElementById(formId);
			var iEle = form.querySelectorAll('input, select, checkbox, textarea');
			var notice = $(form).find('.notice');
			var checks = false;

			var fldVals = {};

			$.each(iEle, function (index, value) {
				fldType = iEle[index].type;

				if (fldType == 'hidden' || fldType == 'text' || fldType == 'textarea' || fldType == 'tel') {
					fldVals[iEle[index].id] = iEle[index].value;
				}
				else if (fldType == 'select-one') {
					fldVals[iEle[index].id] = $('#' + iEle[index].id).val();
				}
				else if (fldType == 'checkbox') {
					if ($('#' + iEle[index].id).is(':checked')) {
						if (!fldVals[iEle[index].name]) { fldVals[iEle[index].name] = ''; }
						fldVals[iEle[index].name] += $('#' + iEle[index].id).val() + ';';
					}
				}
			});

			fldVals['curl'] = window.location.href;
			fldVals['Page_url__c'] = window.location.href;
			// console.log(fldVals);

			// CHECK IF REQUIRED
			var reqFields = fldVals.popup_required.split(','),
				fieldMessage;

			$(reqFields).each(function (index, value) {
				if (value === 'popup_email') {
					var emailVal = $('#popup_email').val();
					if (!validateEmail(emailVal)) {
						checks = true;
						fieldMessage = 'A Valid Email';
						return false;
					}
				}
				if (value === 'popup_first_name' || value === 'popup_last_name' || value === 'popup_company' || value === 'popup_telephone') {
					var nameVal = $('#popup_first_name').val(),
						lnameVal = $('#popup_last_name').val(),
						companyVal = $('#popup_company').val(),
						phoneVal = $('#popup_telephone').val();

					if (nameVal === '') {
						checks = true;
						fieldMessage = 'First Name';
						return false;
					}
					if (lnameVal === '') {
						checks = true;
						fieldMessage = 'Last Name';
						return false;
					}
					if (companyVal === '') {
						checks = true;
						fieldMessage = 'Company Name';
						return false;
					}
					if (phoneVal === '') {
						checks = true;
						fieldMessage = 'Phone Number';
						return false;
					}
				}
				if (value === 'popup_country') {
					if ($('select[name="popup_country"]').val() === '') {
						checks = true;
						fieldMessage = 'Country';
						return false;
					}
				}

			});


			if (checks === true) {
				$(notice).html('<i class="fa fa-exclamation-triangle c6"></i> ' + fieldMessage + ' is Required');
				formBtn.attr('disabled', false);
				formBtn.parent().toggleClass('btnLoader');
				return false;
			} else {
				// SEND FORM
				$.post(ajax_object.ajaxurl, {
					action: 'global_ajaxfunc',
					action_type: actionType,
					form_data: fldVals,
				}, function (res) {
					if (res.success) {
						setTimeout(function () {
							formBtn.attr('disabled', false);
							formBtn.parent().toggleClass('btnLoader');
						}, 2000);

						var event_name = $(form).find('#event_name').val();

						if (event_name) {
							push2DataLayer(fldVals);
							dataLayer.push({ 'event': event_name });
						}

						var thanksPageUrl = $(form).find('#popup_thanks').val();
						window.location.href = thanksPageUrl;
					}
					else {
						formBtn.attr('disabled', false);
						formBtn.parent().toggleClass('btnLoader');
						notice.html('<i class="fa fa-exclamation-triangle c6"></i> ' + res.data.msg);
					}
				});
			}
		});
	}

	submitPopupForm($('.submitPopupFomrRequest'), 'contactFormPopup', 'send_popup_request');

	/* multi step form submit */
	function submitMultiStepForm(formBtn, formId, actionType) {
		formBtn.click(function () {

			formBtn.attr('disabled', 'disabled');
			formBtn.prop('disabled', true)
			formBtn.parent().toggleClass('btnLoader');

			// COLLECT VALUES
			var form = document.getElementById(formId);
			var iEle = form.querySelectorAll('input, select, checkbox, textarea');
			var notice = $(form).find('.notice');
			var checks = false;

			var fldVals = {};

			$.each(iEle, function (index, value) {
				fldType = iEle[index].type;

				if (fldType == 'hidden' || fldType == 'text' || fldType == 'textarea' || fldType == 'email') {
					fldVals[iEle[index].id] = iEle[index].value;
				}
				else if (fldType == 'select-one') {
					fldVals[iEle[index].id] = $('#' + iEle[index].id).val();
				}
				else if (fldType == 'checkbox') {
					if ($('#' + iEle[index].id).is(':checked')) {
						if (!fldVals[iEle[index].name]) { fldVals[iEle[index].name] = ''; }
						fldVals[iEle[index].name] += $('#' + iEle[index].id).val() + ';';
					}
				}
			});

			fldVals['curl'] = window.location.href;
			fldVals['Page_url__c'] = window.location.href;
			// console.log(fldVals);

			// CHECK IF REQUIRED
			var reqFields = fldVals.required.split(','),
				fieldMessage;

			$(reqFields).each(function (index, value) {

				if (value === 'email') {
					var emailVal = $('#email').val();

					if (!validateEmail(emailVal)) {
						checks = true;
						fieldMessage = 'A Valid Email';
						return false;
					}
				}
				if (value === 'first_name' || value === 'last_name' || value === 'phone' || value === 'quote-quantity') {
					var phoneVal = $('#phone').val(),
						last_name = $('#last_name').val(),
						first_name = $('#first_name').val(),
						quantity = $('#quote-quantity').val();

					if (phoneVal === '') {
						checks = true;
						fieldMessage = 'Phone Number';
						return false;
					}

					if (last_name === '') {
						checks = true;
						fieldMessage = 'Last Name';
						return false;
					}
					if (first_name === '') {
						checks = true;
						fieldMessage = 'First Name';
						return false;
					}
					if (quantity === '') {
						checks = true;
						fieldMessage = 'Estimated Quantity';
						return false;
					}
				}

				if (!fldVals[value]) {
					checks = true;
					inputid = value;
					fieldMessage = $('#' + inputid).attr('placeholder');
					return false;
				}
			});

			if (formId == 'quoteFormStep') {
				var somVals = [];
				fldVals['quote-product'] = $('#quote-product').val();
				var amntVals = $('#quote-quantity').val();
				fldVals['quote-quantity'] = amntVals;

				/// roi 14.06.23 #3///
				var cpuPlatform = $('div.paddons-checkbox.required :checkbox:checked').length;

				if (cpuPlatform < 1) {
					fieldMessage = "Please select Platform";
					formBtn.attr('disabled', false);
					formBtn.parent().toggleClass('btnLoader');
					checks = true;
				}
				/// roi 14.06.23 #3 -end///

				if (amntVals === null) {
					fieldMessage = "Please select Estimated Quantities";
					formBtn.attr('disabled', false);
					formBtn.parent().toggleClass('btnLoader');
					checks = true;
				}
			}

			if (checks === true) {
				$(notice).html('<i class="fa fa-exclamation-triangle c6"></i> ' + fieldMessage + ' is Required');
				formBtn.attr('disabled', false);
				formBtn.parent().toggleClass('btnLoader');
				return false;
			} else {
				// SEND FORM
				$.post(ajax_object.ajaxurl, {
					action: 'global_ajaxfunc',
					action_type: actionType,
					form_data: fldVals,
				}, function (res) {
					if (res.success) {
						setTimeout(function () {
							formBtn.attr('disabled', false);
							formBtn.parent().toggleClass('btnLoader');
						}, 2000);

						$('.multi-step').find('.form-inner').empty();
						$('.multi-step .stepwizard').remove();
						$('.multi-step').addClass('form-submited').find('.form-inner').append(res.data.response);
						push2DataLayer(fldVals);
						dataLayer.push({ 'event': 'step_two_submit' });
					}
					else {
						notice.html('<i class="fa fa-exclamation-triangle c6"></i> ' + res.data.msg);
						formBtn.attr('disabled', false);
						formBtn.parent().toggleClass('btnLoader');
					}
				});
			}
		});
	}

	submitMultiStepForm($('.submitMultiStepRequest'), 'quoteFormStep', 'send_multi_step_quote');

	function submitExitPopupForm(formBtn, formId, actionType) {
		formBtn.click(function () {

			formBtn.attr('disabled', 'disabled');
			formBtn.parent().toggleClass('btnLoader');

			// COLLECT VALUES
			var form = document.getElementById(formId);
			var iEle = form.querySelectorAll('input, select, checkbox, textarea');
			var notice = $(form).find('#conFormExitPopup .notice');
			var checks = false;

			var fldVals = {};

			$.each(iEle, function (index, value) {
				fldType = iEle[index].type;

				if (fldType == 'hidden' || fldType == 'text' || fldType == 'textarea') {
					fldVals[iEle[index].id] = iEle[index].value;
				}
				else if (fldType == 'select-one') {
					fldVals[iEle[index].id] = $('#' + iEle[index].id).val();
				}
				else if (fldType == 'checkbox') {
					if ($('#' + iEle[index].id).is(':checked')) {
						if (!fldVals[iEle[index].name]) { fldVals[iEle[index].name] = ''; }
						fldVals[iEle[index].name] += $('#' + iEle[index].id).val() + ';';
					}
				}
			});

			fldVals['curl'] = window.location.href;
			fldVals['Page_url__c'] = window.location.href;

			// CHECK IF REQUIRED
			var reqFields = fldVals.required.split(','),
				fieldMessage;

			$(reqFields).each(function (index, value) {
				if (value === 'email') {
					var emailVal = $('#conFormExitPopup #email').val();
					if (!validateEmail(emailVal)) {
						checks = true;
						fieldMessage = 'A Valid Email';
						return false;
					}
				}

				if (value === 'first_name' || value === 'last_name' || value === 'company' || value === 'phone' || value === 'country_exit') {
					var nameVal = $('#conFormExitPopup #first_name').val(),
						lnameVal = $('#conFormExitPopup #last_name').val(),
						companyVal = $('#conFormExitPopup #company').val(),
						phoneVal = $('#conFormExitPopup #phone').val(),
						countryVal = $('#conFormExitPopup #country_exit').val();

					if (nameVal === '') {
						checks = true;
						fieldMessage = 'First Name';
						return false;
					}
					if (lnameVal === '') {
						checks = true;
						fieldMessage = 'Last Name';
						return false;
					}
					if (companyVal === '') {
						checks = true;
						fieldMessage = 'Company Name';
						return false;
					}
					if (phoneVal === '') {
						checks = true;
						fieldMessage = 'Phone Number';
						return false;
					}
					if (countryVal === '') {
						checks = true;
						fieldMessage = 'Country';
						return false;
					}
				}

			});


			if (checks === true) {
				$(notice).html('<i class="fa fa-exclamation-triangle c6"></i> ' + fieldMessage + ' is Required');
				formBtn.attr('disabled', false);
				formBtn.parent().toggleClass('btnLoader');
				return false;
			} else {
				// SEND FORM
				console.log(actionType);
				$.post(ajax_object.ajaxurl, {
					action: 'global_ajaxfunc',
					action_type: actionType,
					form_data: fldVals,
				}, function (res) {
					formBtn.attr('disabled', false);
					formBtn.parent().toggleClass('btnLoader');
					if (res.success) {
						var event_name = $(form).find('#event_name').val();

						if (event_name) {
							push2DataLayer(fldVals);
							dataLayer.push({ 'event': event_name });
						}

						var thanksPageUrl = $(form).find('#thanks').val();
						window.location.href = thanksPageUrl;
					}
					else {

						notice.html('<i class="fa fa-exclamation-triangle c6"></i> ' + res.data.msg);
					}
				});
			}
		});
	}

	submitExitPopupForm($('.submitExitPopupForm'), 'conFormExitPopup', 'send_exitPopup_form');
});