

jQuery(function($){

    $('div[data-name="som_filter_field_type"] select').change(function() {
        var fieldtype   = $(this).val();
        if(fieldtype == 'range') { $(this).parent().parent().parent().find('div[data-name="som_filter_data_source"] select').val('specification').trigger('change'); }
    });



    // INITIALIZE SELECT2 FIELD
	// multiple select with AJAX search
    
});



jQuery(document).ready(function($) {




});