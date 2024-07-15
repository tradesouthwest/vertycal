<?php 
/**
 * Adding the language markup for the datepicker.
 * 
 * @since 1.0.0
 * @see notation in readme.txt file "Installation (5.)"
 */
//remove_action('wp_enqueue_scripts', 'wp_localize_jquery_ui_datepicker', 1000);

function vertycal_localize_jquery_ui_datepciker() {
    global $wp_locale;

    if ( !wp_script_is( 'jquery-ui-datepicker', 'enqueued' ) ) {
        return;
    }

    // Convert the PHP date format into jQuery UI's format.
    $datepicker_date_format = str_replace(
        array(
            'd', 'j', 'l', 'z', // Day.
            'F', 'M', 'n', 'm', // Month.
            'Y', 'y'            // Year.
        ),
        array(
            'dd', 'd', 'DD', 'o',
            'MM', 'M', 'm', 'mm',
            'yy', 'y'
        ),
        get_option( 'date_format' )
    );
    /**
     * Build switch/case
     * Or use option(s)
     * Maybe use $.datepicker.regional[''])); // Default to English again
     */
    $locale = get_locale();
    $lang   = vertycal_datepicker_locale_fr_FR();
    
    // Got this string by switching to fr_FR.
    $datepicker_defaults = '{"'. $lang .'"}';
    //maybe use add_filter( 'wp_footer' )
    wp_add_inline_script( 'jquery-ui-datepicker', "jQuery(document).ready(function(jQuery){jQuery.datepicker.setDefaults({$datepicker_defaults});});" );
}
//add_action('wp_enqueue_scripts', 'vertycal_localize_jquery_ui_datepciker', 10, 0);

function vertycal_datepicker_locale_fr_FR()
{
    return 'closeText":"Fermer","currentText":"Aujourd\u2019hui","monthNames":["janvier","f\u00e9vrier","mars","avril","mai","juin","juillet","ao\u00fbt","septembre","octobre","novembre","d\u00e9cembre"],"monthNamesShort":["Jan","F\u00e9v","Mar","Avr","Mai","Juin","Juil","Ao\u00fbt","Sep","Oct","Nov","D\u00e9c"],"nextText":"Suivant","prevText":"Pr\u00e9c\u00e9dent","dayNames":["dimanche","lundi","mardi","mercredi","jeudi","vendredi","samedi"],"dayNamesShort":["dim","lun","mar","mer","jeu","ven","sam"],"dayNamesMin":["D","L","M","M","J","V","S"],"dateFormat":"MM d, yy","firstDay":1,"isRTL":false';
}
function vertycal_datepicker_locale_de()
{
    return 'closeText: "Done", 
    prevText: "Prev", 
    nextText: "Next", 
    currentText: "Today", 
    monthNames: ["Januar","Februar","März","April","Mai","Juni",
    "Juli","August","September","Oktober","November","Dezember"], 
    monthNamesShort: ["Jan", "Feb", "Mär", "Apr", "Mai", "Jun", "Jul", "Aug", "Sep", "Okt", "Nov", "Dez"], 
    dayNames: ["Sonntag", "Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag"], 
    dayNamesShort: ["Son", "Mon", "Die", "Mit", "Don", "Fre", "Sam"], 
    dayNamesMin: ["So","Mo","Di","Mi","Do","Fr","Sa"], 
    weekHeader: "Wk", 
    dateFormat: "mm/dd/yy", 
    firstDay: 0, 
    isRTL: false, 
    showMonthAfterYear: false, 
    yearSuffix: ""';
}
function vertycal_datepicker_locale_nl()
{
    return 'closeText: "Done", 
    prevText: "Prev", 
    nextText: "Next", 
    currentText: "Today", 
    monthNames: ["Januari","Februari","Maart","April","Mei","Juni",
    "Juli","Augustus","September","Oktober","November","December"], 
    monthNamesShort: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Aug", "Sep", "Okt", "Nov", "Dec"], 
    dayNames: ["Zondag", "Maandag", "Dinsdag", "Woensdag", "Donderdag", "Vrijdag", "Zaterdag"], 
    dayNamesShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"], 
    dayNamesMin: ["Zo","Ma","Di","Wo","Do","Vr","Za"], 
    weekHeader: "Wk", 
    dateFormat: "mm/dd/yy", 
    firstDay: 0, 
    isRTL: false, 
    showMonthAfterYear: false, 
    yearSuffix: ""';
} 