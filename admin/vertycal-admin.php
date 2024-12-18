<?php 
/**
 * @package vertycal
 * @subpackage admin/vertycal-admin
 * 
 * @since 1.0.0
 */ 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Columns setup for post type= vertycal
 * 
 * @param array $vertycal_date_time_meta, $vertycal_just_time_meta, $vertycal_mark_done_meta 
 *              All vertycal meta boxes.
 *              @see admin/vertycal-metaboxes
 * @since 1.0.0
 */
function vertycal_edit_scheduled_columns( $columns ) 
{

	$columns = array(
		'cb'    => '<input type="checkbox" />',
		'title' => __( 'Scheduled' ),
		'vertycal_date_time_meta' => __( 'Date' ),
		'vertycal_just_time_meta' => __( 'Time' ),
        'vertycal_mark_done_meta' => __( 'Status' ),
        'author'                  => __( 'Person' ),
        'date'  => __( 'Published On' )
	);

	return $columns;
}
/**
 * Manage sortable columns
 * 
 * @param vertycal_date_time_meta Date custom sorting column.
 * @param vertycal_mark_done_meta Status custom sorting column.
 * @since 1.0.0
 */
function vertycal_vertycal_sortable_columns( $columns ) 
{

    $columns['vertycal_date_time_meta'] = 'vertycal_date_time_meta';
    $columns['vertycal_mark_done_meta'] = 'vertycal_mark_done_meta';
    
        return $columns;
}
/**
 * Add headings and args to admin columns
 * 
 * @param switch vertycal_date_time_meta, vertycal_just_time_meta,
 *               vertycal_mark_done_meta and category columns
 * @since 1.0.0
 */
function vertycal_manage_scheduled_columns( $column, $post_id )
{
    global $post;

	switch( $column ) {

		/* If displaying the 'vertycal_date_time_meta' column. */
        case 'vertycal_date_time_meta' : 
            $date_time_meta = get_post_meta( $post_id, 'vertycal_date_time_meta', true );
            if ( empty( $date_time_meta ) )
                    echo __( 'Not Set', 'vertycal' );
                    else 
                    echo esc_attr( $date_time_meta );
        break;
        /* If displaying the 'vertycal_just_time_meta' column. */
        case 'vertycal_just_time_meta' : 
            $just_time_meta = get_post_meta( $post_id, 'vertycal_just_time_meta', true );
            if ( empty( $just_time_meta ) )
                    echo __( 'Not Set', 'vertycal' );
                    else 
                    echo esc_attr( $just_time_meta );
        break;
        /* If displaying the 'vertycal_mark_done_meta' column. */
        case 'vertycal_mark_done_meta' : 
            $mark_done_meta = get_post_meta( $post_id, 'vertycal_mark_done_meta', true );
            if ( empty( $mark_done_meta ) )
                    echo __( 'Not Set', 'vertycal' );
                    else 
                    echo esc_attr( $mark_done_meta );
        break;
        case 'vertycal_category' :

			/* Get the genres for the post. */
			$terms = get_the_terms( $post_id, 'vertycal_category' );

			/* If terms were found. */
            if ( !empty( $terms ) ) 
            {

				$out = array();

                /* Loop through each term, linking to the 'edit posts' page 
                for the specific term. */
				foreach ( $terms as $term ) {
					$out[] = sprintf( '<a href="%s">%s</a>',
                    esc_url( add_query_arg( array( 'post_type' => $post->post_type, 
                                                    'vertycal_category' => $term->slug), 
                                                    'edit.php' ) ),
                    esc_html( sanitize_term_field( 'name', 
                                                    $term->name, 
                                                    $term->term_id, 
                                                    'vertycal_category', 
                                                    'display' ) )
                    );
				}
				/* Join the terms, separating them with a comma. */
				echo join( ', ', $out );
            }
                else {
                    esc_attr_e( 'Open', 'vertycal' );
                }
            break;

            /* Just break out of the switch statement for everything else. */
            default: 
                break; 
        }
}


/**
 * Adding styles to users admin panel -if selected
 * 
 * @since 1.0.0
 * @param var $dashoptions Plugin option
 */
function vertycal_adminpage_custom_colors() 
{ 

    $dashoptions = vertycal_options_getter_checkbox('vertycal_dashnews_widgetcheck', false);
    $vertycal_dashboard = ( empty( $dashoptions )) ? '0' : $dashoptions;

	if ( '1' == $vertycal_dashboard ) : 

        $options   = get_option( 'vertycal_options' ); 
        $color_cf1 = ( empty (   $options['vertycal_color_field_1'] ) ) ? 
                     '#155d93' : $options['vertycal_color_field_1'];
        $color_cf2 = ( empty (   $options['vertycal_color_field_2'] ) ) ? 
                     '#ffffff' : $options['vertycal_color_field_2'];
        $color_cf3 = ( empty (   $options['vertycal_color_field_3'] ) ) ? 
                     '#e1d699' : $options['vertycal_color_field_3']; //e1d699
        
        if ( is_user_logged_in() ) {
            echo '<style type="text/css">
            #adminmenuback li a, #adminmenu .wp-submenu a{color: ' . esc_attr($color_cf2) . ';}
            .wp-admin #wpwrap{background:'. esc_attr($color_cf3) .';}
            #adminmenu, #adminmenu .wp-submenu, #adminmenuback, #adminmenuwrap,
            #adminmenu li{background-color: ' . esc_attr($color_cf1) . '}
            span.ab-icon {display:none;}
            .form-table th{font-weight: initial;color:#111111;}</style>';
        }
    endif;
} 
/**
 * Remove dashboard widgets 
 * 
 * @uses remove_meta_box action hook
 * =====================================
 * Comment out any row that is a meta box you woul like to keep visible.
 * 
 */
function vertycal_remove_dashboard_meta() 
{

    $dashoptions = ( !empty (get_option('vertycal_options')['vertycal_dashnews_widgetcheck']))
                    ? get_option('vertycal_options')['vertycal_dashnews_widgetcheck'] : '';
    $vertycal_dashboard = ( empty( $dashoptions )) ? '0' : $dashoptions;
	if ( '1' == $vertycal_dashboard ) 
	{

	remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal'); // 'incoming links' widget
    remove_meta_box('dashboard_plugins', 'dashboard', 'normal'); // 'plugins' widget
    remove_meta_box('dashboard_primary', 'dashboard', 'normal'); // 'WordPress News' widget
    remove_meta_box('dashboard_primary', 'dashboard', 'side'); // 'WordPress News' widget
    remove_meta_box('dashboard_secondary', 'dashboard', 'normal'); // secondary widget
    remove_meta_box('dashboard_quick_press', 'dashboard', 'side'); // 'Quick Draft' widget
    remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side'); // 'Recent Drafts' widget
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal'); // 'Activity' widget
    remove_meta_box('dashboard_right_now', 'dashboard', 'normal'); // 'At a Glance' widget
    remove_meta_box('dashboard_activity', 'dashboard', 'normal'); // 'Activity' widget (since 3.8)
    remove_meta_box('rg_forms_dashboard', 'dashboard', 'normal'); // 'Activity' widget (since 3.8)
    //remove_action('admin_notices', 'update_nag');
	// bbpress
	//['bbp-dashboard-right-now']);
	// yoast seo
	//['yoast_db_widget']);
	// gravity forms
    //['rg_forms_dashboard']);
    
    //do_action( 'vertycal_add_custom_dashboard_widgets' );
    do_action( 'vertycal_adminpage_custom_colors');
    
    } else {
        //remove_action( 'wp_dashboard_setup', 'vertycal_add_custom_dashboard_widgets' );
        remove_action( 'admin_head', 'vertycal_adminpage_custom_colors');
        return false;       	
    }  
} 
/**
 * Create content header for Dashboard Widget.
 * @since 1.0.0
 * $widget_id, $widget_name, $callback, $control_callback, $callback_args
 * see: https://developer.wordpress.org/reference/functions/wp_add_dashboard_widget/
 */
function vertycal_add_custom_dashboard_widgets() 
{

    $wnshort_ip = '<small> ' . do_shortcode( "[vertycal_userip]" ) . '</small>';

    wp_add_dashboard_widget(
        'vertycal_dashboard_widget',                            
	    __( ' Personal Dashboard' ) . $wnshort_ip,
	    'vertycal_admin_dashboard_widget_content' // Display function.
	);
}
/**
 * Output the contents of dashboard Widget.
 * 
 * @since 1.0.0
 * 
 * @param var $defimg    Default fallback image for dashboard icon 
 * @param var $logofound Logo from Customizer -if set
 * 
 * @return HTML to @see vertycal_add_custom_dashboard_widgets()
 */
function vertycal_admin_dashboard_widget_content() 
{   
    
    $html = $defimg = $logofound = '';
    $current_user = wp_get_current_user();
    if ( get_theme_mods() ) { 
    
        $custom_logo_id = get_theme_mod( 'custom_logo' );
        $logofound = wp_get_attachment_image_src( $custom_logo_id , 'small' );
    }  

    $defimg  =  plugin_dir_url( dirname(__FILE__) ) . 'prop/dashicon-256x256.png';
    $defvimg = '<img class="avatar" src="' . esc_url( $defimg ) . '" alt="logo" height="52" />';
    $logoexists = '<img class="avatar" src="' . esc_url( $logofound ) . '" alt="logo" height="52" />';
    if ( $logofound == '' ) 
         { $logovimg = $defvimg; }
    else { $logovimg = $logoexists; }

    echo '
        <h4>' . get_bloginfo( 'name' ) . '</h4>
        <span>' . force_balance_tags(do_shortcode( "[vertycal_dateinadmin]" )) . '</span>
        <div class="vrtcl-dashbrd">
        
        <figure class="vrtcl-dashlogo">
            <p>' . $logovimg . '</p>
        </figure>
    <strong>' . esc_html__( 'Current Items: ', 'vertycal' ) . '<span>' 
    . vertycal_count_open_items($current_user->ID) . '</span></strong><br>'
    . esc_html__( 'User first name: ', 'vertycal' )   . $current_user->user_firstname . '<br>'
    . esc_html__( 'User last name: ',   'vertycal' )  . $current_user->user_lastname . '<br>'
    . esc_html__( 'User display name: ', 'vertycal' ) . $current_user->display_name . '<br>'
    . esc_html__( 'Username:  ', 'vertycal' ) . $current_user->user_login  . '<br>'
    . esc_html__( 'User email: ', 'vertycal' ) . $current_user->user_email . '<br>

        </div>
        <p><b>' . esc_html__( 'Visit your Profile page to update any changes.', 'vertycal' ) 
        . '</b> <a href="' . get_edit_user_link() . '" class="button">' 
        . esc_html__( 'My Profile', 'vertycal' ) . '</a></p>';

        echo vertycal_find_author_info( $current_user->ID );

} 
/**
 * Get author post info
 * 
 * @since 1.0.0
 * @param array $args {
 *     Uses wp query variables.
 *
 *     @param var $current_user Current logged in user.
 *     @param key post_type     vertycal
 *     @param key orderby       sort by vertycal meta-data _date_time_meta 
 * }
 * @return string $html Content.
 */
function vertycal_find_author_info( $current_user='' )
{   
    
    $the_query = null;
    $args = array(
        'post_author' => $current_user,
        'post_type'   => 'vertycal',
        'post_status' => array( 'publish', 'pending' ),
        'order'       =>  'ASC',
        'orderby'     => 'meta_value',
        'meta_key'    => 'vertycal_date_time_meta',
        );

    $html ='';
    $html .= 
    '<div class="dash-vrtcl-widget">
        <table class="vrtcl-list">
            <thead>
            <tr><th><em>@</em></th><th><em>" "</em></th><th><em>#</em></th></tr>
            </thead>
            <tbody>';

    $the_query = new WP_Query( $args );
    if ( $the_query->have_posts() ) : 
        while ( $the_query->have_posts() ) : 
            $the_query->the_post(); 
    
            $vertycal_date_time = get_post_meta( get_the_ID(),
                                                'vertycal_date_time_meta',true);

        $html .= '<tr><td><b>' . esc_attr( $vertycal_date_time ) . '</b></td>
        <td class="shorterspan">'  . get_the_title() . '</td><td>' . get_the_ID() . '</td></tr>';

        endwhile;

        wp_reset_query();
       
    endif; 
        $html .= '
            </tbody>
        </table>
        </div><div class="clear"></div>';
        
        return $html;
}

/**
 * Add user capabilities functions | All hooks from admin/_-manager file
 * Both files _-manager and _-admin required to be in the 'admin' folder
 * 
 * @since 1.0.0
 * @param string $vrtcl_initopt Plugin option
 * @returns file inclusion or Boolean false
 */
function vertycal_run_manager_caps()
{

$vrtcl_initopt = (empty( get_option('vertycal_options')['vertycal_users_caps'])) 
                   ? 0 : get_option('vertycal_options')['vertycal_users_caps'];

    if( '1' == $vrtcl_initopt ) :    

        require_once( 'vertycal-manager.php' );
    endif;

        return false;
} 
/**
 * Count post belonging to logged in user
 * 
 * @since 1.0.0
 * @param string $total_posts Uses wp_ count_user_posts
 */
function vertycal_count_open_items( $userid )
{
    if( !is_admin() ) return;
   
    $post_type    = 'vertycal';
    $total_posts  = count_user_posts( $userid, $post_type ); 

        return (int)$total_posts;
}

/**
 * Rewrite text on exceprt editor field
 *
 * @since 1.0.0
 *
 * @param  string $translate New text
 * @param  string $original  Original text
 * @param  string $screen    Returns object of the screen’s post type, and taxonomy
 * @return string
 */
function vertycal_editor_gettext( $translation, $original, $typenow )
{

	global $typenow;
	if ( vertycal_is_edit_page( array( 'edit', 'new' ) ) && "vertycal" == $typenow )
	{
		if ( 'Excerpt' == $original ) {
			return esc_html__( 'Sheduler notes' );
		} else {
			$pos = strpos( $original,
						   'Excerpts are optional hand-crafted summaries of your' );
			if ( $pos !== false) {
				return  esc_html__( 'Notes show on calendar and are truncated to fit the agenda window.' );
			}
		}
	}
    return $translation;
}

/**
 * Function to check if the current page is a post edit page.
 * @since 1.0.0
 * 
 * @param string $new_edit What page to check for accepts new= new post page, 
 *                         edit= edit post page, null= for either
 * @return boolean
 */
function vertycal_is_edit_page($new_edit = null)
{

	global $pagenow;
    //make sure we are on the backend
	if (!is_admin()) return false;

		if($new_edit == "edit")
			return in_array( $pagenow, array( 'post.php',  ) );
		elseif($new_edit == "new")
			//check for new post page
			return in_array( $pagenow, array( 'post-new.php' ) );
		else
			//for either new or edit
			return in_array( $pagenow, array( 'post.php', 'post-new.php' ) );
} 

/**
 * Getter checker for checkbox options values default
 * 
 * @since 1.0.22
 * @deprecated 1.1.0
 */
function vertycal_options_getter_checkbox( $opt_name, $default )
{

    if( !isset( $default ) ) $default = false;

    $value = ( empty( get_option('vertycal_options')['"' . $opt_name . '"'] ) ) 
            ? $default : get_option('vertycal_options')['"' . $opt_name . '"'];
        echo $value;
}

