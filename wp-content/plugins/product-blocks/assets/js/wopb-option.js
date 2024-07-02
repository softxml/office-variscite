(function($) {
    'use strict';

    // Product Tab Blocks support Showing in Backend Editor
    $(document).on( 'click', '.block-editor-page .wc-tabs li', function(e) {
        e.preventDefault();
        $('.wc-tabs li').removeClass('active');
        $(this).addClass('active');
        const selectId = $(this).attr('aria-controls');
        $('.woocommerce-Tabs-panel').hide();
        $('.woocommerce-Tabs-panel#'+selectId).show();
    });

    // Color Picker Support for Products Attributes
    if ( $.isFunction( $.fn.wpColorPicker ) ) {
        $( '.wopb-color-picker' ).wpColorPicker();
    }

    // Block Editor Page
    if ( $('body').hasClass('block-editor-page') ) {
        $('body').addClass( 'wopb-editor-'+wopb_option.width );
    }

    // Saved Template Action Button
    const savedBtn = $('.wopb-saved-templates-action');
    if ( savedBtn.length > 0 ) {
        $('.page-title-action').addClass( 'wopb-save-templates-pro' ).text( savedBtn.data('text') ).attr( 'target', '_blank' ).attr( 'href', savedBtn.data('link') );
    }
     // Saved Template Back Button URL Change
     $(document).ready( function() {
        if ( $('.block-editor-page').length > 0 && wopb_option.post_type == 'wopb_templates' ) {
            setTimeout( function() {
                if ( $('.edit-post-fullscreen-mode-close').length > 0 ) {
                    $('.edit-post-fullscreen-mode-close')[0].href = wopb_option.saved_template_url
                }
            }, 100);
        }
    });

    // Popup Flip Feature Image
	$('#flipimage-feature-image').on( 'click', '#upload_feature_image_button', function(e) {
        e.preventDefault();
        const button_id = $(this).attr('id');
		const field_id = button_id.replace('_button', '');
        let mdeia = wp.media({
            title: $(this).data( 'uploader_title' ),
            button: { text: $(this).data( 'uploader_button_text' ) },
            multiple: false
        }).open().on('select', function (e) {
            const attachment = mdeia.state().get('selection').first().toJSON();
            $('#'+field_id).val(attachment.id);
            $('#flipimage-feature-image img').attr('src',attachment.url);
            $('#flipimage-feature-image img').show();
            $('#' + button_id).attr('id', 'remove_feature_image_button');
            $('#remove_feature_image_button').text('Remove Flip Image');
        });
	});
    // Remove Flip Feature Image
	$('#flipimage-feature-image').on( 'click', '#remove_feature_image_button', function(e) {
		e.preventDefault();
		$( '#upload_feature_image' ).val( '' );
		$( '#flipimage-feature-image img' ).attr( 'src', '' );
		$( '#flipimage-feature-image img' ).hide();
		$( this ).attr( 'id', 'upload_feature_image_button' );
		$( '#upload_feature_image_button' ).text( 'Set Flip Image' );
    });


    // Open Media Library in Product Image Attributes (Variation Swatches)
    $('#wopb-term-upload-img-btn').on( 'click', function (e) {
        e.preventDefault();
        let object = $(this);
        let mdeia = wp.media({
            title: 'Attribute Term Image',
            multiple: false
        }).open().on('select', function (e) {
            let selectedImage = mdeia.state().get('selection').first().toJSON();
            object.parent().prev("#wopb-term-img-thumbnail").find("img").attr("src", selectedImage.sizes.thumbnail.url);
            object.parent().find("#wopb-term-img-remove-btn").removeClass('wopb-d-none');
            object.parent().find('#wopb-term-img-input').val(selectedImage.id);
        });
    });
    // Remove Image from Product Image Attributes (Variation Swatches)
    $('#wopb-term-img-remove-btn').click(function (e) {
        $(this).parent().prev("#wopb-term-img-thumbnail").find("img").attr("src", wopb_option.url + 'assets/img/wopb-placeholder.jpg');
        $(this).parent().find('#wopb-term-img-input').val('');
    });

    // Dashboard Submenu Support Active & Inactive Class
    $(document).on('click', '#wopb-dashboard-wopb-settings-tab li a, #toplevel_page_wopb-settings ul li a', function(e) {
        let value = $(this).attr('href')
        if (value) {
            value = value.split('#');
            if (typeof value[1] != 'undefined' && value[1].indexOf('demoid') < 0 && value[1]) {
                $('#toplevel_page_wopb-settings ul li a').closest('ul').find('li').removeClass('current');
                $(this).closest('li').addClass('current'); // Submenu click
                $('#toplevel_page_wopb-settings ul li a[href$='+value[1]+']').closest('li').addClass('current'); // Dash Nav Menu click
                if (value[1] == 'home') {
                    $('#toplevel_page_wopb-settings ul li.wp-first-item').addClass('current');
                }
            }
        }
    });
    // Dashboard Submenu Support Active & Inactive Class
    $('#toplevel_page_wopb-settings ul > li').removeClass('current');
    $('#toplevel_page_wopb-settings ul > li > a').each(function (e) {
        const selector = $(this).attr('href');
        if ( selector && selector.indexOf("?page=wopb-settings") > 0 ) {
            if ( $(this).hasClass('wp-first-item') != false ) {
                $(this).attr('href' , selector+'#home' )
            } else if ( wopb_option.settings ) {
                if ( (selector.indexOf('#builder') > 0 && wopb_option.settings?.wopb_builder != 'true') ||
                    (selector.indexOf('#custom-font') > 0 && wopb_option.settings?.wopb_custom_font != 'true') ||
                    (selector.indexOf('#saved-templates') > 0 && wopb_option.settings?.wopb_templates != 'true') ) {
                    $(this).hide();
                }
            }
        } else if ( selector.indexOf("?page=go_productx_pro") > 0 ) {
            $(this).attr('target', '_blank');
        }
        const pram = (selector.indexOf('#') > 0 ? selector.split('#')[1] : 'home')
        if ( window.location.hash == '#' + pram ) {
            $(this).parent('li').addClass('current');
        }
        $(this).attr( 'id', 'productx-submenu-' + pram )
    });


    // Custom Font Support Add
    $(".wopb-font-variation-action").on('click', function(e) {
        const content = $('.wopb-custom-font-copy')[0].outerHTML;;
        $(this).before( content.replace("wopb-custom-font-copy", "wopb-custom-font wopb-font-open") );
    });
    $(document).on('click', ".wopb-custom-font-close", function(e) {
        $(this).closest('.wopb-custom-font-container').removeClass('wopb-font-open');
    });
    $(document).on('click', ".wopb-custom-font-edit", function(e) {
        $(this).closest('.wopb-custom-font-container').addClass('wopb-font-open');
    });
    $(document).on('click', ".wopb-custom-font-delete", function(e) {
        $(this).closest('.wopb-custom-font').remove();
    });
    $(document).on('click', '.wopb-font-upload', function(e) {
        const that = $(this);
        $(this).addClass('rty')
        const wopbCustomFont = wp.media({
            title: 'Add Font',
            button: { text: 'Add New Font' },
            library: {
                type: that.attr('type')
            },
            multiple: false,
        });
        wopbCustomFont.on(
            'select',
            function () {
                const attachment = wopbCustomFont.state().get( 'selection' ).first().toJSON();
                const allowedExtensions = that.attr('extension');
                const fileExtension = attachment.url.split('.').pop().toLowerCase();
                if (fileExtension !== allowedExtensions) {
                    if (confirm(`Invalid file type. Please upload ${allowedExtensions.toUpperCase()} file`)) {
                        wopbCustomFont.open()
                    } else {
                        return;
                    }
                }else {
                    that.closest('.wopb-font-file-list').find('input').val(attachment.url)
                }
            }
        );
        wopbCustomFont.open();
    });
    $(document).on('change', '.wopb-font-file-list input', function(e) {
        const that = $(this);
        if( that.val() ) {
            const allowedExtension = that.parents('.wopb-font-file-list:first').find('.wopb-font-upload').attr('extension');
            const fileUrl = that.val().trim();
            if (!fileUrl.toLowerCase().endsWith('.' + allowedExtension)) {
                alert(`Please enter a valid URL ending with .${allowedExtension} extension`);
            }
        }
    });

    // Move notice into after heading
    $(document).ready( function() {
        const noticeWrapper = $('.wopb-notice-wrapper');
        if ( noticeWrapper.length > 0  ) {
            setTimeout( function() {
                noticeWrapper.each(function(e){
                    const notice = $(this);
                    if($('#wpwrap .wrap .wp-header-end').length>0) {
                        $('#wpwrap .wrap .wp-header-end').after(notice);
                    } else {
                        $('#wpwrap .wrap h1').after(notice);
                    }
                });
            }, 100);
        }
    });

})( jQuery );