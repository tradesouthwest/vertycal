<?php // vertycal admin
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @package vertycal/admin/
 * 
 * @param array  string $vertycal_date_time_meta, $vertycal_just_time_meta,
 * $vertycal_mark_done_meta
 * @since 1.0.1
 */
function vertycal_edit_scheduled_columns( $columns ) 
{

	$columns = array(
		'cb'    => '<input type="checkbox" />',
		'title' => __( 'Scheduled', 'vertycal' ),
		'vertycal_date_time_meta' => __( 'Date', 'vertycal' ),
		'vertycal_just_time_meta' => __( 'Time', 'vertycal' ),
        'vertycal_mark_done_meta' => __( 'Status', 'vertycal' ),
        'author'                  => __( 'Person', 'vertycal' ),
        'date' => __( 'Published On' )
	);

	return $columns;
}

/**
 * Manage sortable columns
 * 
 * @param vertycal_date_time_meta Date will be the only custom sorting column.
 * @since 1.0.1
 */
function vertycal_vertycal_sortable_columns( $columns ) 
{

	$columns['vertycal_date_time_meta'] = 'vertycal_date_time_meta';
    
        return $columns;
}

/**
 * Add headings and args to admin columns
 * 
 * @param switch vertycal_date_time_meta, vertycal_just_time_meta,
 *               vertycal_mark_done_meta and category columns
 * @since 1.0.1
 */
function vertycal_manage_scheduled_columns( $column, $post_id )
{
    global $post;

	switch( $column ) {

		/* If displaying the 'vertycal_date_time_meta' column. */
        case 'vertycal_date_time_meta' : 
            $date_time_meta = get_post_meta( $post_id, 'vertycal_date_time_meta', true );
            if ( empty( $date_time_meta ) )
                    echo esc_html__( 'Not Set', 'vertycal' );
                    else 
                    echo esc_attr( $date_time_meta );
        break;
        /* If displaying the 'vertycal_just_time_meta' column. */
        case 'vertycal_just_time_meta' : 
            $just_time_meta = get_post_meta( $post_id, 'vertycal_just_time_meta', true );
            if ( empty( $just_time_meta ) )
                    echo esc_html__( 'Not Set', 'vertycal' );
                    else 
                    echo esc_attr( $just_time_meta );
        break;
        /* If displaying the 'vertycal_mark_done_meta' column. */
        case 'vertycal_mark_done_meta' : 
            $mark_done_meta = get_post_meta( $post_id, 'vertycal_mark_done_meta', true );
            if ( empty( $mark_done_meta ) )
                    echo esc_html__( 'Not Set', 'vertycal' );
                    else 
                    echo esc_attr( $mark_done_meta );
        break;
        case 'vertycal_category' :

			/* Get the genres for the post. */
			$terms = get_the_terms( $post_id, 'vertycal_category' );

			/* If terms were found. */
			if ( !empty( $terms ) ) {

				$out = array();

                /* Loop through each term, linking to the 'edit posts' page 
                for the specific term. */
				foreach ( $terms as $term ) {
					$out[] = sprintf( '<a href="%s">%s</a>',
                        esc_url( add_query_arg( array( 'post_type' => $post->post_type, 
                                                       'vertycal_category' => $term->slug ), 
                                                       'edit.php' ) ),
                        esc_html( sanitize_term_field( 'name', $term->name, 
                                                        $term->term_id, 
                                                        'vertycal_category', 
                                                        'display' ) )
					    );
				}
				/* Join the terms, separating them with a comma. */
				echo esc_attr( join( ', ', $out ) );
            }
            else {
				esc_attr_e( 'Open', 'vertycal' );
            }
            break;

            /* Just break out of the switch statement for everything else. */
            default :
                break;
        }
} 

/**
 * adding styles to users admin panel
 * 
 * @since 1.1.0
 */
add_action('admin_head', 'vertycal_adminpage_custom_colors');
function vertycal_adminpage_custom_colors() 
{ 
    $dashoptions = get_option('vertycal_options')['vertycal_dashnews_widgetcheck'];
    $vertycal_dashboard = ( empty( $dashoptions )) ? '0' : $dashoptions;
	if ( '1' == $vertycal_dashboard ) : 

        $options  = get_option( 'vertycal_options' ); 
        $color_h3 = ( empty ( $options['vertycal_color_field_1'] ) ) ? 
                    '#155d93' : $options['vertycal_color_field_1'];
        $color_h4 = '#ffffff';
        
        if ( is_user_logged_in() ) {
            echo '<style type="text/css">
            #adminmenuback li a, #adminmenu .wp-submenu a{color: ' . esc_attr($color_h4) . ';}
            .wp-admin #wpwrap{background:rgb(225,214,153);}
            #adminmenu, #adminmenu .wp-submenu, #adminmenuback, #adminmenuwrap,
            #adminmenu li{background-color: ' . esc_attr($color_h3) . '}
            span.ab-icon {display:none;}
            .form-table th{font-weight: initial;color:#111111;text-decoration:underline;}
            #footer-upgrade{display:none;}
            .inside a:first-child{font-size:19px}</style>';
        }
    endif;
} 

/**
 * Create content header for Dashboard Widget.
 * $widget_id, $widget_name, $callback, $control_callback, $callback_args
 * see: https://developer.wordpress.org/reference/functions/wp_add_dashboard_widget/
 */
function vertycal_add_custom_dashboard_widgets() 
{

    $wnshort_ip = '<small> ' . do_shortcode( "[vertycal_userip]" ) . '</small>';

    wp_add_dashboard_widget(
        'vertycal_dashboard_widget',                            
	    __( ' Personal Dashboard', 'vertycal' ) . $wnshort_ip,
	    'vertycal_admin_dashboard_widget_content' // Display function.
	);
}
add_action( 'wp_dashboard_setup', 'vertycal_remove_dashboard_meta' );

// Remove dashboard widgets 
function vertycal_remove_dashboard_meta() 
{
    $dashoptions = get_option('vertycal_options')['vertycal_dashnews_widgetcheck'];
    $vertycal_dashboard = ( empty( $dashoptions )) ? '0' : $dashoptions;
	if ( '1' == $vertycal_dashboard ) 
	{
        global $wp_meta_boxes;
    do_action( 'vertycal_add_custom_dashboard_widgets' );
    do_action( 'vertycal_adminpage_custom_colors');
	// wp..
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);
	// bbpress
	unset($wp_meta_boxes['dashboard']['normal']['core']['bbp-dashboard-right-now']);
	// yoast seo
	unset($wp_meta_boxes['dashboard']['normal']['core']['yoast_db_widget']);
	// gravity forms
	unset($wp_meta_boxes['dashboard']['normal']['core']['rg_forms_dashboard']);

    
    } else {
        remove_action( 'wp_dashboard_setup', 'vertycal_add_custom_dashboard_widgets' );
        remove_action( 'admin_head', 'vertycal_adminpage_custom_colors');
        return false;       	
    }  
} 

/**
 * Output the contents of dashboard Widget.
 * 
 * @since 1.1.0
 */
function vertycal_admin_dashboard_widget_content() 
{   
    
    $html = $logofound = '';
    $current_user = wp_get_current_user();

    if( has_custom_logo() ) : 
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    $logofound = wp_get_attachment_image_src( $custom_logo_id , 'small' );
    endif;  
    
    $defimg =  plugin_dir_url( dirname(__FILE__) ) . 'prop/vdasha.png';
    $defvimg = '<img class="avatar" src="' . esc_url( $defimg ) . '" alt="logo" height="52" />';
    $logoexists = '<img class="avatar" src="' . esc_url( $logofound ) . '" alt="logo" height="52" />';
    $logovimg = ( empty( $logofound ) ) ? $defvimg : $logovimg = $logoexists;

    ob_start();

echo '<h4>' . esc_html( get_bloginfo( 'name' ) ) . '</h4>
    <p>' . do_shortcode( "[vertycal_dateinadmin]" ) . '</p>
    <div class="vrtcl-dashbrd">
    
    <figure class="vrtcl-dashlogo">
        <p>' . esc_attr( $logovimg ) . '</p>
    </figure>
    <strong>' . esc_html__( 'Current Items: ', 'vertycal' ) . '<span>' 
    . esc_attr( vertycal_count_open_items($current_user->ID) ) . '</span></strong><br>'
    . esc_html__( 'User first name: ', 'vertycal' )   . esc_attr( $current_user->user_firstname ) . '<br>'
    . esc_html__( 'User last name: ',   'vertycal' )  . esc_attr( $current_user->user_lastname ) . '<br>'
    . esc_html__( 'User display name: ', 'vertycal' ) . esc_attr( $current_user->display_name ) . '<br>'
    . esc_html__( 'Username:  ',         'vertycal' ) . esc_attr( $current_user->user_login  ) . '<br>'
    . esc_html__( 'User email: ',        'vertycal' ) . esc_attr( $current_user->user_email ) . '<br>
    </div>
    <p><b>' . esc_html__( 'Visit your Profile page to update any changes.', 'vertycal' ) 
    . '</b> <a href="' . esc_url( get_edit_user_link() ) . '" class="button">' 
    . esc_html__( 'My Profile', 'vertycal' ) . '</a></p>';
    
    $html = ob_get_clean();
        
        echo wp_kses_post( $html );
} 

/**
 * Branding for admin footer
 * @since 1.1.0
 */
function vertycal_change_admin_footer(){
    echo '<span id="footer-note">From your friends at <a href="https://tradesouthwest.com/" target="_blank">TradeSouthWest</a>.</span>';
}

/**
 * Add user capabilities functions | All hooks from admin/_-manager file
 * Both files _-manager and _-admin required to be in the 'admin' folder
 * 
 * @since 1.0.1
 */
function vertycal_run_manager_caps()
{

$vrtcl_initopt = ( empty( get_option('vertycal_options')['vertycal_users_caps'] ) ) 
            ? 0 : get_option('vertycal_options')['vertycal_users_caps'];
    if( '1' == $vrtcl_initopt ) :             
        require_once( 'vertycal-manager.php' );
    endif;

        return false;
} 

/**
 * Check if option set - currently not an option. Just a placeholder.
 * Reverse `return` booleans to get sidebar. 
 * 
 * @since 1.1.1
 * @return Boolean
 */
function vertycal_check_for_sidebar()
{

$vrtclopt = ( empty( get_option('vertycal_options')['vertycal_checkbox_sidebar'] ) ) 
            ? 0 : get_option('vertycal_options')['vertycal_checkbox_sidebar'];
    if( '1' == $vrtclopt ) {             
        return true;
    } else {
        return false;
    }
} 

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
			return esc_html__( 'Sheduler notes', 'vertycal' );
		} else {
			$pos = strpos( $original,
						   'Excerpts are optional hand-crafted summaries of your' 
                        );
			if ( $pos !== false) {
				return  esc_html__('Notes show on calendar and are truncated to fit the agenda window.', 'vertycal');
			}
		}
	}
    return $translation;
}

/**
 * is_edit_page
 * function to check if the current page is a post edit page
 *
 * @param  string  $new_edit what page to check for accepts new
 * 	- new post page, edit - edit post page, null for either
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