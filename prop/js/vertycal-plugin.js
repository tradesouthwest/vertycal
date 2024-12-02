/* VertyCal Note that #VrtclDate is not same ID as Admin #VrtclDateTime */

jQuery(document).ready(function() {
    if(jQuery("#VrtclDateTime").length) {
        jQuery('#VrtclDateTime').datepicker({
            dateFormat: 'yy-mm-dd'
        });   
    }
}); 

jQuery(document).ready(function($) {
        
    /**
     * Show and hide categories on Scheduler page
     */
            
        if ($('ul.vertycal-categories').length > 0) {
            
            // Set variables
            // pC = Parent Category 
            // fpC = First Parent Category
            // cC = Current Category
            // cCp = Currents Category's Parent
    
            var 
            pC = $('.vertycal-categories li.cat-parent'),
            fpC = $('.vertycal-categories li.cat-parent:first-child'), // Start this one open
            cC = $('.vertycal-categories li.current-cat'),
            cCp = $('.vertycal-categories li.current-cat-parent');
    
            pC.prepend('<span class="toggle"><i class="far fa-minus-square fa-plus-square"></i></span>');
            pC.parent('ul').addClass('has-toggle'); pC.children('ul').hide(); 
    
            if (pC.hasClass("current-cat-parent")) {
                cCp.addClass('open'); cCp.children('ul').show(); cCp.children().children('i.far').removeClass('fa-plus-square');
            } 
            else if (pC.hasClass("current-cat")) {
                cC.addClass('open'); cC.children('ul').show(); cC.children().children('i.far').removeClass('fa-plus-square');
            } 
            else {
                fpC.addClass('open'); fpC.children('ul').show(); fpC.children().children('i.far').removeClass('fa-plus-square');
            }
            $('.has-toggle span.toggle').on('click', function() {
                $t = $(this);
                if ($t.parent().hasClass("open")){
                    $t.parent().removeClass('open'); $t.parent().children('ul').slideUp(); $t.children('i.far').addClass('fa-plus-square');
                } 
                else {
                    $t.parent().parent().find('ul.children').slideUp();
                    $t.parent().parent().find('li.cat-parent').removeClass('open');
                    $t.parent().parent().find('li.cat-parent').children().children('i.far').addClass('fa-plus-square');
                    
                    $t.parent().addClass('open');
                    $t.parent().children('ul').slideDown();
                    $t.children('i.far').removeClass('fa-plus-square');
                } 
                
            });
            // Link the count number
            $('.count').css('cursor', 'pointer');
            $('.count').on('click', function(event) {
                $(this).prev('a')[0].click();
            });
    
        }
    });