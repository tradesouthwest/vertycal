/* VertyCal Note that #VrtclDate is not same ID as Admin #VrtclDateTime */

jQuery(document).ready(function() {  
    if(jQuery("#VrtclDate").length) {
        var dateText;
        jQuery('#VrtclDate').datepicker({
            dateFormat: 'yy-mm-dd',
            onSelect: function (dateText) {
                jQuery('#VrtclDate').val(dateText);
                jQuery('#VrtclDate').slideUp;
            }
        });
    }
}); 