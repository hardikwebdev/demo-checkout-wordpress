
jQuery( document ).ready(function() {
    
    var buttonPlacement = jQuery('select#demo_checkout_button_placement_option').val();
    if(buttonPlacement == 'other'){
        jQuery('.demo-other-button').removeClass('hide');
    }else{
        jQuery('.demo-other-button').addClass('hide');
    }
    jQuery(document).on('change', 'select#demo_checkout_button_placement_option', function() {
        var buttonPlacementVal = jQuery(this).val();
        if(buttonPlacementVal == 'other'){
            jQuery('.demo-other-button').removeClass('hide');
        }else{
            jQuery('.demo-other-button').addClass('hide');
        }
    });

    jQuery(function() {
        var currentDate = new Date();
        if(jQuery('#start_date').length){
            jQuery("#start_date").datepicker({
                dateFormat: 'dd-mm-yy',
                maxDate: 0,
                changeYear: true 
            }).attr('readonly', 'readonly');
            jQuery("#end_date").datepicker({
                dateFormat: 'dd-mm-yy',
                maxDate: 0,
                changeYear: true 
            }).attr('readonly', 'readonly');
        }
    });
});
