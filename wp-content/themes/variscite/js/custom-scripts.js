// (function($) {
//     if(! $('body').hasClass('page-template-thanks-page')){
//
//         var fixedCls = '.top-menu-box';
//         var oldSSB = $.fn.modal.Constructor.prototype.setScrollbar;
//         $.fn.modal.Constructor.prototype.setScrollbar = function () {
//             oldSSB.apply(this);
//             if (this.bodyIsOverflowing && this.scrollbarWidth)
//                 $(fixedCls).css('padding-right', this.scrollbarWidth);
//         };
//
//         var oldRSB = $.fn.modal.Constructor.prototype.resetScrollbar;
//         $.fn.modal.Constructor.prototype.resetScrollbar = function () {
//             oldRSB.apply(this);
//             $(fixedCls).css('padding-right', '');
//         }
//
//     }
// }(jQuery));

// jQuery(function ($) {
//     // cookie notice
//     const cookieName = 'cookie_notice';
//     var timer;
//     var timerStart;
//     var timeSpentOnSite = getTimeSpentOnSite();
//
//     function getTimeSpentOnSite() {
//         timeSpentOnSite = parseInt(sessionStorage.getItem('timeSpentOnSite'));
//         timeSpentOnSite = isNaN(timeSpentOnSite) ? 0 : timeSpentOnSite;
//         return timeSpentOnSite;
//     }
//
//     function startCounting() {
//         timerStart = Date.now();
//         timer = setInterval(function() {
//             timeSpentOnSite = getTimeSpentOnSite()+(Date.now()-timerStart);
//             sessionStorage.setItem('timeSpentOnSite', timeSpentOnSite);
//             timerStart = parseInt(Date.now());
//             // Convert to seconds
//             // console.log(parseInt(timeSpentOnSite/1000));
//             if (parseInt(timeSpentOnSite/1000) >= 60) { // show popup for 1 minute
//                 hideCookiePopup();
//             }
//         },0);
//     }
//
//     // Check cookie
//     function checkCookie() {
//         let date = new Date();
//         let cookieNotice = localStorage.getItem(cookieName);
//
//         let isSet = cookieNotice && ( date.getTime() < parseInt(cookieNotice) );
//
//         if (!isSet) {
//             localStorage.removeItem(cookieName);
//
//             setTimeout(function() {
//                 $('.cookie-notice').show();
//             }, 500);
//         }
//     };
//
//     // Cookie button click
//     $('.close-cookie-notice').on('click', function (e) {
//         e.preventDefault();
//
//         let time = new Date().getTime() + (86400000  * 30); // 30 days
//         localStorage.setItem(cookieName, time);
//
//         hideCookiePopup();
//
//         $('[id^="mega-menu-item-wpml"] .mega-sub-menu li').each(function(){
//             var urlObj  = new URL($(this).find('a').attr('href'));
//             var url = urlObj.origin + '/setcookienotice.html';
//             $('body').append('<iframe class="setcookienoticeiframe" style="display:none;" src="' + url + '"></iframe>');
//         });
//
//         setTimeout(function() {
//             $('.setcookienoticeiframe').remove();
//         }, 10000);
//     });
//
//     function hideCookiePopup() {
//         $('.cookie-notice').hide();
//         $('#mobileMenuWrap .navbar-fixed-bottom').css("bottom", "");
//         $('body').css("padding-bottom", "");
//     }
//
//     startCounting();
//
//     $(document).ready(function () {
//         checkCookie();
//         setTimeout(function() {
//             popupPosition();
//         }, 500);
//     });
//
//     $(window).on('resize', popupPosition);
//
//     function popupPosition() {
//         if(window.matchMedia('(max-width: 767px)').matches) {
//             var popupHeight = $('.cookie-notice').outerHeight();
//             // console.log(popupHeight);
//             if($('.cookie-notice').is(":visible")) {
//                 $('#mobileMenuWrap .navbar-fixed-bottom').css("bottom", popupHeight + "px");
//             } else {
//                 $('#mobileMenuWrap .navbar-fixed-bottom').css("bottom", "");
//             }
//         } else {
//             var popupHeight = $('.cookie-notice').outerHeight();
//             if($('.cookie-notice').is(":visible")) {
//                 $('body').css("padding-bottom", popupHeight + "px");
//             } else {
//                 $('body').css("padding-bottom", "");
//             }
//         }
//     }
//     // END cookie notice
//
//     $('#prodQuoteForm select#country_code').on('change', function(){
//         if($(this).val() !== '') {
//             $(this).addClass('selected');
//         } else {
//             $(this).removeClass('selected');
//         }
//     });
//
// });

jQuery(function ($) {

    $(window).on('load', function() {
        prefill_from_localstorage();
    });

    $('body').on('update_checkout update_order_review', function() {
        prefill_from_localstorage();
    });

    function prefill_from_localstorage() {

        if(localStorage.hasOwnProperty('sf_medium')) {

            $('[name="Campaign_medium__c"]').val(
                localStorage.getItem('sf_medium')
            ).attr('value', localStorage.getItem('sf_medium'));
        }

        if(localStorage.hasOwnProperty('sf_source')) {

            $('[name="Campaign_source__c"]').val(
                localStorage.getItem('sf_source')
            ).attr('value', localStorage.getItem('sf_source'));
        }

        if(localStorage.hasOwnProperty('sf_term')) {

            $('[name="Campaign_term__c"]').val(
                localStorage.getItem('sf_term')
            ).attr('value', localStorage.getItem('sf_term'));
        }

        if(localStorage.hasOwnProperty('sf_campaign')) {

            $('[name="Paid_Campaign_Name__c"]').val(
                localStorage.getItem('sf_campaign')
            ).attr('value', localStorage.getItem('sf_campaign'));
        }

        if(localStorage.hasOwnProperty('sf_content')) {

            $('[name="Campaign_content__c"]').val(
                localStorage.getItem('sf_content')
            ).attr('value', localStorage.getItem('sf_content'));
        }

        // if(typeof ga !=="undefined" && typeof ga.getAll() !=="undefined" && typeof ga.getAll()[0]!=="undefined"){
        //     $('input[name="GA_id__c"]').val(ga.getAll()[0].get('clientId')).attr('value', ga.getAll()[0].get('clientId'));
        // }
        $('input[name="Page_url__c"]').val(window.location).attr('value', window.location);
        $('input[name="curl"]').val(window.location).attr('value', window.location);
    }

// Update the GA_id field on the form.
//     $(window).load(function () {
//         if(typeof ga !=="undefined" && typeof ga.getAll() !=="undefined" && typeof ga.getAll()[0]!=="undefined"){
//
//             var hidden_gaid;
//
//             if ((hidden_gaid = $('#GA_id')) && $('#GA_id').length > 0) {
//                 var gaid = ga.getAll()[0].get('clientId');
//                 hidden_gaid.val(gaid);
//             }
//         }
//         // console.log('pretimeout log');
//         setTimeout(function(){
//             if(typeof ga !=="undefined" && typeof ga.getAll() !=="undefined" && typeof ga.getAll()[0]!=="undefined"){
//                 // ga(function () {
//                     $("#GA_id__c").val(ga.getAll()[0].get('clientId'));
//                     // console.log('timeout log');
//                 // });
//             }
//         },2500);
//
//     });

    // if(typeof ga !=="undefined" && typeof ga.getAll() !=="undefined" && typeof ga.getAll()[0]!=="undefined"){
    //     ga(function () {
    //         $("#GA_id__c").val(ga.getAll()[0].get('clientId'));
    //     });
    // }
    // console.log('end of file log');

    /* testimonial slider */

    if( $('.js-customer-say').length > 0 ) {
        var slidesNum = $('.js-customer-say .item').length;

        const swiperAcc = new Swiper('.js-customer-say', {
            loop: true,
            slidesPerView: 1,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev'
            },
        });
    }

    function form_placeholders() {
        if ($('.step-form').length > 0 || $('.contact-popup').length > 0 || $('.contact-form-inline').length > 0) {
            $(".floating-labels input, .floating-labels textarea").not('input[type="tel"]').focus(function () {
                $(this).parents(".floating-labels").addClass("active");
            }).blur(function () {
                var ival = $(this).val();
                if (ival.length < 1) {
                    $(this).parents(".floating-labels").removeClass("active");
                }
            });
            $(".floating-labels input, .floating-labels textarea").not('input[type="tel"]').each(function () {
                var ival = $(this).val();
                if (ival.length > 1) {
                    $(this).parents(".floating-labels").addClass("active");
                }
            })
        }
    }
    form_placeholders();
    $(window).on("load", function(){
        form_placeholders();
    });
    
    /* product document toggle */
    $('.js-document-toggle').on('click', function(event) {
        event.preventDefault();
        $(this).toggleClass('active');
        $('.document-inner').slideToggle();
    });

    /* intlTelInput phone script */
    // if($("#telephone").length > 0) {
    //     $("#telephone").intlTelInput();
    // }

    // $('.iti__flag-container').click(function() { 
    //     var countryCode = $('.iti__selected-flag').attr('title');
    //     var countryCode = countryCode.replace(/[^0-9]/g,'')
    //     $('#telephone').val("");
    //     $('#telephone').val("+"+countryCode+" "+ $('#telephone').val());
    // });

    /* open contact popup form */
     $('.js-link-popup-form').on('click', function(event){
        event.preventDefault();
        $('.contact-form-popup').fadeIn();
    });

    /* close contact popup form */
    $('.js-close-popup').on('click', function(event){
        event.preventDefault();
        $('.contact-form-popup').fadeOut();
    });

    /* manual country field */
    $('#country_text').on('change', function(event){
        event.preventDefault();
        
        if($(this).is(':checked')) {
            $('.field-company-manually').show();
            
        } else {
            $('.field-company-manually').hide();
        }
    });

    /* step form */
    var navListItems = $('.multi-step .steps-item a'),
    allWells = $('.setup-content-block'),
    allNextBtn = $('.nextBtn');
 
    navListItems.on('click', function (e) {
        e.preventDefault();

        var $target = $($(this).attr('href')),
            $item = $(this);
        
        if( typeof $target != 'undefined' && $target.selector == '#step-form-1') {
            // $('.multi-step .steps-item a[href="#step-form-2"]').addClass('disabled');
            $item = $('.multi-step .steps-item a[href="#step-form-1"]');
            $('#quote-formbox').removeClass('active-step-2');
        } 
 
        if( typeof $target != 'undefined' && $target.selector == '#step-form-2') {
            isValid = validate_step_form('step-form-1');

            if(!isValid) {
                return false;    
            }
        }

        if (!$item.hasClass('disabled')) {
            navListItems.removeClass('step-active').addClass('step-default');
            $item.addClass('step-active');
            allWells.hide();
            $target.show();
        }
    });

    allNextBtn.click(function () {
        var curStep = $(this).closest(".setup-content-block"),
            curStepBtn = curStep.attr("id"),
            nextStepWizard = $('.multi-step .steps-item a[href="#' + curStepBtn + '"]').parent().next().children("a"),
            curInputs = curStep.find("input[type='text'],input[type='tel'], select, input[type='email'], input[type='checkbox']"),
            isValid = true;

        $(".form-control").removeClass("has-error");

        for(var i = 0; i < curInputs.length; i++) {
            if (!curInputs[i].validity.valid) {
                isValid = false;
                $(curInputs[i]).closest(".form-control").addClass("has-error");
            }
        }
        console.log(isValid);
        if (isValid) {
            curStep.hide();
            nextStepWizard.removeClass('disabled').trigger('click');
        } 
    });
    
    function validateStepEmail(email) {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }

    function validate_step_form(step) {
        console.log(step);
        var stepid 	= document.getElementById(step);
        var iEle 	= stepid.querySelectorAll('input, select, checkbox, textarea');
        var notice	= stepid.querySelector('.notice');
        var checks 	= false;

        var fldVals = {};

        $.each(iEle, function( index, value ) {
            fldType = iEle[index].type;
            
            if(fldType == 'text' || fldType == 'textarea' || fldType == 'email') {
                fldVals[iEle[index].id] = iEle[index].value;
            }
            else if(fldType == 'select-one') {
                fldVals[iEle[index].id] = $('#' + iEle[index].id).val();
            }
            else if(fldType == 'checkbox') {
                if ($('#' + iEle[index].id).is(':checked')) {
                    if(!fldVals[iEle[index].name]) {fldVals[iEle[index].name] = '';}
                    fldVals[iEle[index].name] += $('#' + iEle[index].id).val() + ';';
                }
            }
        });
        console.log(fldVals);
        // CHECK IF REQUIRED
        var reqFields = $('#required-step-1').val().split(','),
            fieldMessage;

        $(reqFields).each(function(index, value) {

            if(value === 'email') {
                var emailVal = $('#email').val();
                
                if(!validateStepEmail(emailVal) ) {
                    checks = true;
                    fieldMessage = 'A Valid Email';
                    return false;
                }
            }

            if (value === 'first_name' || value === 'last_name' || value === 'phone' || value === 'company' || value === 'country' ) {
                var phoneVal = $('#phone').val(),
                    country = $('#country').val(),
                    company = $('#company').val(),
                    last_name = $('#last_name').val(),
                    first_name = $('#first_name').val();

                    if( first_name === '' ) {
                        checks = true;
                        fieldMessage = 'First Name';
                        return false;
                    }
 
                    if( last_name === '' ) {
                        checks = true;
                        fieldMessage = 'Last Name';
                        return false;
                    }
                    
                    if( company === '' ) {
                        checks = true;
                        fieldMessage = 'Company';
                        return false;
                    }

                    if( country === '' ) {
                        checks = true;
                        fieldMessage = 'Country';
                        return false;
                    } 
                    
                    if( phoneVal === '' ) {
                        checks = true;
                        fieldMessage = 'Phone Number';
                        return false;
                    }
            }
 
        });

        if(checks === true) {
            $(notice).html('<i class="fa fa-exclamation-triangle c6"></i> ' + fieldMessage + ' is Required');
            return false;
        } 

        return true;
    }

    function check_validation(curStep) {
        var curStepBtn = curStep.attr("id"),
        curInputs = curStep.find("input[type='text'],input[type='tel'], select, input[type='email'], input[type='checkbox']"),
        isValid = true;
        var notice	= $('#quoteFormStep').find('.notice');
        
        $(".form-control").removeClass("has-error");

        for(var i = 0; i < curInputs.length; i++) {
            if (!curInputs[i].validity.valid) {
                isValid = false;
                $(curInputs[i]).closest(".form-control").addClass("has-error");
                
                $(notice).html('<i class="fa fa-exclamation-triangle c6"></i> ' + fieldMessage + ' is Required');
            } else {
                if($(curInputs[i]).parent().find('.label-error').length == 1) {
                    $(curInputs[i]).parent().find('.label-error').remove();
                }
            }
        }
        return isValid;
    }

    $('div.setup-panel div a.step-active').trigger('click');
    
    
    if($('.multi-step').length > 0) {
        $('.multi-step input[name="full_name"]').on('focusout ', function(event){
            event.preventDefault();
            
            var full_name = $(this).val();
            var names = full_name.split(" ");
            
            $('.multi-step input[name="first_name"]').val(names[0]);
            $('.multi-step input[name="last_name"]').val(names[1]);
        });
    }

    $('.back-btn').on('click', function(event){
        event.preventDefault();
        console.log('cc');
        $('.multi-step .steps-item li a[href="#step-form-1"]').trigger('click');

    });
    
    $('.js-quote-product').SumoSelect({
        placeholder: 'SoM Platforms I\'m Interested In...',
    });

    $('.js-quantity-product').SumoSelect({
        placeholder: 'Estimated Project Quantity...',
    });

});

//// contact form Som checkboxes///////
var expanded = false;

function showCheckboxes() {
    var checkboxes = document.getElementById("som-checkboxes");
    if (!expanded) {
        checkboxes.style.display = "block";
        expanded = true;
    } else {
        checkboxes.style.display = "none";
        expanded = false;
    }
}

jQuery(function($){
    if($('body').is('.contactus-page')){
        document.addEventListener("click", function(event) {
            if (event.target.closest(".som-multiselect")) return
            document.getElementById("som-checkboxes").style.display = "none";
            expanded = false;
        })
    }


});

//// exit popup cookie 180 days///////

jQuery(function($){
    if($('body').is('.page-template-thanks-page')){
        var date = new Date();
        date.setDate(date.getDate() + 180);
        var expires = "expires="+ date.toUTCString();

        document.cookie = "exit-popup=true;" +expires+"; path=/;"
    }
});

<!-- google_optimizer_anti_clicker snippet (recommended)  -->
if($('body').is('.single-specs')){
    (function(a,s,y,n,c,h,i,d,e){s.className+=' '+y;h.start=1*new Date;
        h.end=i=function(){s.className=s.className.replace(RegExp(' ?'+y),'')};
        (a[n]=a[n]||[]).hide=h;setTimeout(function(){i();h.end=null},c);h.timeout=c;
    })(window,document.documentElement,'async-hide','dataLayer',4000,
        {'OPT-NP2T46B':true});
}


/// video HP lightbox ///
if ($('.hero-with-video-bg').length){
    console.log('vidvid')

    const videoBtn = $('#hp-hero-play-btn');
    const variLightbox = $('.hp-hero-video-wrap');

    $(videoBtn).on('click', function (){

        let vidID = this.getAttribute('data-video_link');

        setTimeout(function() {
            $(variLightbox).fadeIn();

        }, 500);

    });

    function stop_video(){
        $('.hp-hero-video-wrap').fadeOut();
        var videos = $('#vidPlayer');
        Array.prototype.forEach.call(videos, function (video) {
            var src = video.src;
            video.src = src;
        });
    }

    $('.closebox').on('click', function (){
        stop_video();
    })

    $('#video-wrap').on('click', function (){
        stop_video();
    })

    $(document).on("keyup", function (evt) {
        if (evt.keyCode === 27) {
            stop_video();
        }
    });
}