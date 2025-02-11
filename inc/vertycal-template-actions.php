<?php
/**
 * VertyCal scheduled content hooks
 * @package VertyCal
 * @subpackage vertycal/inc/vertycal-template-actions
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Add Category list as archival links
 * 
 * @return string | echos $output
 */
function vertycal_before_scheduler_list_render( $output='' )
{

    ob_start(); 
    ?>
    <header class="entry-header">
        <h4 class="entry-title"><?php the_title(); ?></h4>
          
            <div class="container-cats">

<p><a class="toggle cats-toggle-btn" href="#CatsMenu" rel="nofollow" title="toggle">
                <?php esc_html_e( 'Toggle Category List', 'vertycal' ); ?></a></p>
                <nav id="CatsMenu" class="toggle-content">
                    <ul id="menucat" class="vrtcl-inline-ul vertycal-categories">
                        <?php wp_list_categories( array(
                            'taxonomy'   => 'vertycal_category',
                            'show_count' => true,
                            'hide_empty' => true,
                            'sort_column' => 'name',
                            'sort_order' => 'asc',
                            'hierarchical' => true,
                            'title_li' => 0,
                            'style' => 'list',
                            'children' => true,
                        ) ); ?>
                    </ul>
                </nav>
            
            </div>
    </header>
<?php 
    $output = ob_get_clean();
        echo wp_kses_post( $output ); 
} 
 
/**
 * Add Tabs into page
 * 
 * @since 1.0.0
 * @return string | echo $output
 * Omitting the URL from either use results in the current URL being used 
 * (the value of $_SERVER['REQUEST_URI']).
 */
function vertycal_before_scheduler_tabs_render( $output='' )
{
ob_start();
?>

<input type="radio" name="tabs" id="toggle-tab1" checked="checked" />
<label for="toggle-tab1"><?php esc_html_e( 'Schedule', 'vertycal' ); ?></label>

<input type="radio" name="tabs" id="toggle-tab2" />
<label for="toggle-tab2"><?php esc_html_e( 'Add New', 'vertycal' ); ?></label>

<input type="radio" name="tabs" id="toggle-tab3" />
<label for="toggle-tab3"><?php esc_html_e( 'Options', 'vertycal' ); ?></label>

<?php 

    $output = ob_get_clean();
    
        echo $output;         // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Get Number of post per page view
 * @since 1.0.1 Deprecated
 */
function vertycal_tmplt_postperpage()
{
    $ppp = ( empty( get_option('vertycal_options')['vertycal_postsperpg'] )) 
           ? '12' : get_option('vertycal_options')['vertycal_postsperpg'];
    return $ppp;
}
//add_action( 'vertycal_postsperpg', 'vertycal_tmplt_get_postperpage', 10, 1 );

/**
 * Pagination for cpt
 * 
 * @since 1.1.1 Added new query string.
 */
function vertycal_pagination_schedule()
{
    global $paged;
    //build arguments for query
    $argz   = array(
        'post_type'     => 'vertycal',
        'post_status'  => 'publish',
        'paged'       => $paged,
        'orderby'    => 'meta_value',
        'meta_key'  => 'vertycal_date_time_meta',  // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
        'order'    => 'ASC',
    ); 
    $vt_query = new WP_Query($argz);
	 
    ob_start();
	?>
	<div class="vrtcl-pagination">
	<?php 
	$pager = 999999; // need an unlikely integer
	   echo paginate_links( array(               // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			'base' => str_replace( $pager, '%#%', esc_url( get_pagenum_link( $pager ) ) ),
			'format' => '?paged=%#%',
			'current' => max( 1, get_query_var('paged') ),
			'total' => $vt_query->max_num_pages
	   ) );
    ?>
    </div> 
	
<?php 
    $output = ob_get_clean();
        
        return $output;
} 

/**
 * Cats and Tags
 * 
 * @return echos HTML on single tmplt
 */
function vertycal_tmplt_single_taxonomy()
{

    global $post;
    $terms = wp_get_post_terms($post->ID, 'vertycal_category');

    if ($terms) 
    {
        
        ob_start();
        $output = array();
            foreach ($terms as $term) {
                $output[] = 
'<a href="' . get_term_link( $term->slug, 'vertycal_category') . '">' .$term->name . '</a>';
            }
        echo wp_kses_post( join( ' ', $output ) );
    
    }
        $htm = ob_get_clean();
    
            return $htm;
}
/**
 * Use WP Post Type `post` to allow for custom page content.
 * 
 * @uses get_post( int|WP_Post| $post, $output = OBJECT, $filter )
 */
function vertycal_get_options_page_post()
{

    $output     = null;
    $post_toget = ( empty( get_option('vertycal_options')['vertycal_public_page_post'] 
             ) ) ? false : get_option('vertycal_options')['vertycal_public_page_post'];

    $post   = get_post( absint( $post_toget ) );
    if($post) 
    
        $output =  apply_filters( 'the_content', $post->post_content );
    
            return $output;
}

/**
 * Exclude one or more single posts from the posts page.
 * 
 * @uses `pre_get_post` Before the loop
 */
add_action( 'pre_get_posts', 'vertycal_remove_posts_from_blog_page' );
function vertycal_remove_posts_from_blog_page( $query ) 
{

    $post_toget = ( empty( get_option('vertycal_options')['vertycal_public_page_post'] 
                ) ) ? '' : get_option('vertycal_options')['vertycal_public_page_post'];

    if( $query->is_main_query() && $query->is_home() ) 
    {
        
        $query->set( 'post__not_in', array( $post_toget ) );
    }
}

/**
 * Redirect single vertycal page if not logged in.
 * 
 * @param option 'single' Only protect single post.
 * @param option 'home'   Only protect the sheduler page.
 * @param option 'all'    Protect both but not home page.
 * @return wp_redirect
 */
add_action( 'template_redirect', 'vertycal_login_required_redirect_post' );
function vertycal_login_required_redirect_post() 
{
 
    $vredir = $vpage ='';
    $queried_post_type = get_query_var( 'post_type', 'vertycal' );
    $vprivate = ( empty( get_option('vertycal_options')['vertycal_whosees_what'] ) ) 
                  ? '' : get_option('vertycal_options')['vertycal_whosees_what'];
    $vpage    = ( empty( get_option('vertycal_options')['vertycal_text_field_1'] ) ) 
         ? 'Scheduler' : get_option('vertycal_options')['vertycal_text_field_1'];

    // Determine option chosen.       
    switch ( $vprivate ) {
        case 'single': //Logged in users will see only Scheduler page.
            $vredir = vertycal_single_required_redirect();
        break;
        case 'home': 
            $vredir = vertycal_none_required_redirect();
        break;
        case 'all': //Only logged in users will see pages
            $vredir = vertycal_both_required_redirect();
        break;
        case 'public': //Only logged in users will see pages
            $vredir = vertycal_public_required_redirect();
        break;
        case 'not-all': //Only logged in users will see pages
            $vredir = null;
        break;
        default:
            $vredir = false;
            break;
    } 

    return sanitize_key( $vredir ); 
}

/**
 * Determine redirects
 * Logged in users will see only Scheduler page.
 * 
 */
function vertycal_single_required_redirect()
{

    $class = false;
    $classes = get_body_class();
    if ( in_array( 'single-vertycal', $classes) ) $class = true;  
    
    if( is_singular() && true === $class ) 
    {
        wp_safe_redirect( home_url( '/' ) );
        exit;
    }
}

/**
 * Determine redirects
 * All users will see only Scheduler page.
 * 
 */
function vertycal_public_required_redirect()
{

    $class = false;
    $classes = get_body_class();
    if ( in_array( 'single-vertycal', $classes) ) $class = true;  
    
    if( is_singular() && true === $class && !is_user_logged_in() ) 
    {
        wp_safe_redirect( home_url( '/' ) );
        exit;
    }
}

/**
 * Determine redirects
 * Only logged in users will see both Scheduler and single Scheduled pages.
 */
function vertycal_none_required_redirect()
{

    $class = false;
    $classes = get_body_class();
    if ( in_array( array( 'scheduler', 'single-vertycal' ), $classes) ) 
        $class = true;

    if ( !is_user_logged_in() && true === $class ) {
        wp_safe_redirect( home_url( '/' ) );
        exit;
    }
}

/**
 * Determine redirects
 * Only logged in users will see pages.
 * 
 */
function vertycal_both_required_redirect()
{

    $class = false;
    $classes = get_body_class();
    if ( in_array('vertycal', $classes) ) $class = true;

    if( is_page( 'scheduler' ) || is_singular() && true === $class ) 
    {
        wp_safe_redirect( home_url( '/' ) );
        exit;
    }
} 


/**
 * Footer scripts for vertycal options page clock.
 * 
 * @param string $trans_month Replace with your language months 
 * @param string $trans_days Replace with your language days 
 * @since 1.0.1
 * @see http://vertycal.net/documentation/
 */  
function vertycal_options_tab_footer_scripts()
{  
    /*
     * Localization may be done manaually here 
     * Only change words between "double-quotes" and leave all other syntax */
    $trans_months = '["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"]';
    $trans_days = '["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"]';
    // es_MX
    $trans_months_esMX = '["enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre"]';
    $trans_days_esMX = '["domingo", "lunes", "martes", "miércoles", "jueves", "viernes", "sábado"]';
    
    ob_start();
    ?>

<script id="vertycalOptionsClock">
var dOut = document.getElementById("date");
var hOut = document.getElementById("hours");
var mOut = document.getElementById("minutes");
var sOut = document.getElementById("seconds");
var ampmOut = document.getElementById("ampm");
var months = <?php echo $trans_months;        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>;
var days = <?php echo $trans_days;            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>;

function update() {
  var e = new Date();
  var t = e.getHours() < 12 ? "AM" : "PM";
  var u = 0 === e.getHours() ? 12 : e.getHours() > 12 ? e.getHours() - 12 : e.getHours();
  var a = e.getMinutes() < 10 ? "0" + e.getMinutes() : e.getMinutes();
  var r = e.getSeconds() < 10 ? "0" + e.getSeconds() : e.getSeconds();
  var s = days[e.getDay()] + ", " + months[e.getMonth()] + " " + e.getDate() + ", " + e.getFullYear();

  dOut.textContent = s;
  hOut.textContent = u;
  mOut.textContent = a;
  sOut.textContent = r;
  ampmOut.textContent = t;
}

update();
window.setInterval(update, 1000);
</script>

<?php  
    // output clean html
    echo ob_get_clean();     // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

} 
