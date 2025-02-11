<?php
/**
 * @package VertyCal
 * @subpackage vertycal/inc/vertycal-functions
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Template for front end form plugin actions.
 *
 * @param  string $page_template GLOBAL
 * @return string
 */
function vertycal_wp_page_template( $page_template )
{

	global $post;

    if ( is_page( 'Scheduler' ) ) {
        $page_template = VERTYCAL_PLUGIN_PATH . 'templates/scheduler.php';
	}

    return $page_template;
}

/**
 * Template for archive plugin actions.
 *
 * @param  string $page_template GLOBAL
 * @return string
 */
function vertycal_wp_tax_template( $page_template )
{

	global $post;

    if ( is_tax( 'vertycal_category' ) ) {
        $page_template = VERTYCAL_PLUGIN_PATH . 'templates/category-vertycal_category.php';
	}

    return $page_template;
}

/**
 * Template for single post type "vertycal."
 *
 * @param  string $page_template GLOBAL
 * @return string
 */
function vertycal_custom_single_template( $single_template )
{

	global $post;

    /* Checks for single template by post type */
	if ( $post->post_type == 'vertycal' ) 
	{
		if ( file_exists( VERTYCAL_PLUGIN_PATH . 'templates/single-vertycal.php')) 
		{
			
			return VERTYCAL_PLUGIN_PATH . 'templates/single-vertycal.php';
        }
    }
    return $single_template;
}

/**
 * Add body class name to scheduler page.
 *
 * @param  string $classes wp_ GLOBAL
 * @return string
 */
function vertycal_prefix_scheduler_body_class($classes) 
{

	if(function_exists('body_class')) { 

		$page = 'scheduler';
		if( is_page( $page ) ) {
		
			$classes[] = sanitize_text_field( 'scheduler' ); 
		}
	}	
	return $classes;
}

/**
 * Displays simple login link
 *
 * @since 1.0.0
 *
 * TODO option to redirect, maybe
 */
function vertycal_user_login_link()
{

	ob_start();
	echo
	'<h4>' . esc_html__( 'Please LogIn', 'vertycal' ) . '</h4>
	<p><a href="' . esc_url( wp_login_url( get_permalink() ) ) . '"
		  title="' . esc_attr__( 'Please LogIn', 'vertycal' ) . '"
		  class="vrtcl-btn btn btn-primary button button-primary">'
		  . esc_html__( 'LogIn', 'vertycal' ) . '</a></p>';

	    return ob_get_clean();
}
/**
 * Filter toggles wpautop per page
 *
 * @since 1.0.1
 * @uses  pre_get_posts In vertycal.php
 * TODO get option page-name
 */
function vertycal_maybe_remove_wpautop()
{
	
	if( is_page( 'Scheduler' ) ) 
	{

        remove_filter( 'the_excerpt', 'wpautop' );
        //return $content;
    	} else {

		add_filter( 'the_excerpt', 'wpautop' );
        //return $content;
    }
}
/**
 * Render Option to display text in thead
 *
 * @since 1.0.0
 *
 * @param string $form_title option
 * @return string
 */
function vertycal_display_thead()
{

	$form_title = ( empty( get_option('vertycal_options')['vertycal_text_field_0'] ) )
			? 'Schedule' : get_option('vertycal_options')['vertycal_text_field_0'];

	return $form_title;
}
/**
 * get slug function
 *
 * @since 1.1.1 Deprecated!
 *
 * @param string $current_url Requested url.
 * @return string
 */ /*
function vertycal_func_the_slug() 
{
	if ( is_null( filter_input( INPUT_SERVER, 'REQUEST_URI' ) ) || ! filter_input( INPUT_SERVER, 'HTTP_HOST' ) ) {
		return;
	}
	$shsh        = wp_unslash( $_SERVER['HTTP_HOST'] );
	$srsr        = wp_unslash( $_SERVER['REQUEST_URI'] );
	$current_url = esc_url( "//". sanitize_text_field( $shsh ). sanitize_text_field( $srsr ) ); 
	return $current_url; 
} */
/**
 * Save front-side form post
 *
 * @since 1.0.0
 * @param $vertycal_date_time string Custom metadata
 * @param $vertycal_just_time string Custom metadata
 * @param $title uses the isset() language construct to check if 3rd array key is set
 * @validation | Only Title and Date are required to not throw an error
 */
function vertycal_save_front_form_post()
{   
	if (!isset( $_REQUEST['vertycal_new_post_nonce'] ) ) return;
	$verify = wp_verify_nonce( wp_unslash( sanitize_text_field( 
			  $_REQUEST['vertycal_new_post_nonce'] ) ), 'vertycal_new_post_nonce' ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash
    if ( !$verify ) { exit("No funny business please"); }

	global $wpdb, $post;
	$post_page        = get_the_ID();
	$vrtcl_success    = false;
	//$current_page     = vertycal_func_the_slug();
	$vertycal_excerpt = $current_users_id = $errors = $vertycal_cat ='';

    // Check if user has permissions to save post
    if ( ! current_user_can( 'edit_page', $post_page ) ) return;

		$default_category   = __( 'General', 'vertycal' ); //TODO make option
		//title Sanitized in $new_post array()
		$title = ( empty( $_POST['title'] ) ) 
					? 'title_none' : 
					sanitize_text_field( wp_unslash( $_POST['title'] ) );
		//custom meta input
		$vertycal_date_time = ( empty( $_POST['vertycal_date_time_meta'] ) )
		          	? 'date_none' : wp_date( get_option( 'date_format' ), 
					sanitize_text_field( wp_unslash( $_POST['vertycal_date_time_meta'] ) ));
		//custom meta input
		$vertycal_just_time = ( empty( $_POST['vertycal_just_time_meta'] ) )
					? '' : wp_date( get_option( 'time_format' ), 
					sanitize_text_field( wp_unslash( $_POST['vertycal_just_time_meta'] ) ) );
		//custom meta input
		$vertycal_location = ( empty( $_POST['vertycal_location_meta'] ) )
					? '' : 
					sanitize_textarea_field( wp_unslash( $_POST['vertycal_location_meta'] ) );
					//error_log("After sanitization: " . var_export($vertycal_location, true));
		//custom meta input
		$vertycal_telephone = ( empty( $_POST['vertycal_telephone_meta'] ) )
					? '' : 
					sanitize_text_field( wp_unslash(  $_POST['vertycal_telephone_meta'] ) );

		if( !empty( $_POST['vertycal_excerpt'] ) ) 
		{

		$vertycal_excerpt = trim( wp_strip_all_tags( wp_unslash( $_POST['vertycal_excerpt'] ) ) );
		}
		//category
		if( !empty( $_POST['vertycal_category'] ) ) 
		{

			$vertycal_cat  = sanitize_text_field( wp_unslash( $_POST['vertycal_category'] ) );
			$vertycal_cats = get_term_by( 'id', $vertycal_cat, 'vertycal_category' );
			$vertycal_cat  = sanitize_key( $vertycal_cats->slug );
			} else {

		        $vertycal_cat = sanitize_text_field( $default_category );
		}
		$current_users_id = ( empty( $_POST['current_user_id'] ) ) ? '0' : absint( $_POST['current_user_id'] );

	if( $errors == '' ) : 

		// ADD THE FORM INPUT TO $new_post ARRAY
		$new_post = array(
		'post_status'  => 'publish', //ToDo get_option('vertycal_default_post_status'),
		'post_type'    => 'vertycal',
        'post_title'   => sanitize_title( $title ),
		'post_excerpt' => wp_kses_post($vertycal_excerpt),
		'author'       => absint( $current_users_id ),
		'meta_input'   => array(
			'vertycal_date_time_meta' => sanitize_text_field( $vertycal_date_time ),
			'vertycal_just_time_meta' => sanitize_text_field( $vertycal_just_time ),
			'vertycal_location_meta'  => wp_kses_post( $vertycal_location ),
			'vertycal_telephone_meta' => sanitize_text_field( $vertycal_telephone ),
			),
			'tax_input' => array(
				'vertycal_category' => sanitize_text_field( $vertycal_cat ),
			),
        );
		//error_log("Just before database query: " . var_export($vertycal_location, true)); 
		//SAVE THE POST
		$post_id = wp_insert_post( $new_post );
		//$object_id, $terms, $taxonomy, $append
		wp_set_object_terms( $post_id,
							 sanitize_key( $vertycal_cat ),
							 'vertycal_category',
							 true
							);
		if( $post_id != false )
		{

			//$vrtcl_success = true;
			//add filter?
			echo '<div id="vrtclSuccess">' . 
			vertycal_saved_front_post_success($vrtcl_success = true)      // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			. '</div><script id="afterSuc" type="text/javascript">
			if ( window.history.replaceState ) {
				window.history.replaceState( null, null, window.location.href );
			}</script>';

			$vertycal_cat = $vertycal_excerpt = $title = $vertycal_date_time = $vertycal_just_time = 
			                $new_post = $vertycal_location = $vertycal_telephone = '';
			$post_id      = null;

			//wp_safe_redirect( 'http:'. $current_page .'#tab2' );
			//wp_redirect($_SERVER['HTTP_REFERER']);
			} else {

				echo wp_kses_post( $post_id->get_error_message() );
		}

	else:
			//there was an error in the post insertion,
			echo wp_kses_post( vertycal_sort_error_messages( $errors ) );
			$errors = '';
	endif;
}

/**
 * Clean and render Success message after saved
 *
 * @since 1.0.0
 *
 * @param  $vrtcl_success string
 * @return string
 */
function vertycal_saved_front_post_success( $vrtcl_success = null )
{
	$html ='';
	if( !empty( $vrtcl_success ) && true === $vrtcl_success ) : 

		ob_start();
		echo '<div class="vrtcl-visible">
			<div class="vrtcl-success">
        <p>'. esc_html__( 'Schedule Updated Successfully', 'vertycal' ) . '</p>
			</div>
		</div>';

		$html = ob_get_clean();

			return $html;
		endif;
}

/**
 * Sorting errors messages for save post
 *
 * @since 1.0.0
 *
 * @param  string $error
 * @return string
 */
function vertycal_sort_error_messages( $errors )
{
	$messg = '';
	switch($errors) {
		case 'title_none':
		$messg = __( 'Please include a title of at least 2 characters', 'vertycal' );

		case 'date_none':
		$messg = __( 'Please include a Date', 'vertycal' );
	}
	return printf( '<div class="wp-error danger"><p>%s</p><p>%s</p></div>',
					esc_html__( 'Looks like there may have been an error', 'vertycal' ),
					esc_html( $messg )
				);


		
}

/**
 * retrieve author initials
 * must be used in the loop or ID required
 */
function vertycal_return_author_initials()
{
    //get author name and strip all but first letters
    //$options = get_option( 'vertycal_options' );
    $def    = get_the_author_meta( 'ID' );
    $str    = get_the_author_meta( 'display_name' );
    $words  = preg_split("/[\s,_-]+/", $str );
    $abbrev = '';
        foreach ( $words as $letter) {
            $abbrev .= $letter[0];
        }
    if( $abbrev == '' ) $abbrev = $def;

    	return sanitize_text_field( $abbrev );
}

/**
 * Dashboard widget shortcode
 * @uses shortcode [vertycal_userip]
 */ 
function vertycal_display_user_ip() 
{
    $ip = '';
    $ip .= esc_html__( 'Your current IP Address is: ', 'vertycal' );
    $ip .= ( isset( $_SERVER['REMOTE_ADDR'] ) ) ? esc_attr( sanitize_text_field( 
			wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) ) : '';
        return $ip;
}

/**
 * Retrieve the current time based on specified type.
 * @uses shortcode [vertycal_dateinadmin]
 */
function vertycal_display_dateinadmin_shortcode() 
{ 
	if( !is_admin() ) return;
	$format = get_option('date_format');
	$tock   = date_i18n($format, current_time('timestamp') );        // phpcs:ignore WordPress.DateTime.CurrentTimeTimestamp.Requested
	
	ob_start();
	echo '<p class="vrtcl-clock">' . esc_attr( $tock ) . ' <span id="WNclock"> </span></p>';
	?><script>var d = new Date(<?php echo time() * 1000; ?>);</script> <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	
	return ob_get_clean();
} 
