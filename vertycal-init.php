<?php 
/**
 * @package vertycal
 * @subpackage vertycal/vertycal-init
 * 
 */
// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {	exit; }

/**
 * Admin Notice on Activation.
 * Notify admin to add new page
 * 
 * @since 1.0.1
 */
function vertycal_plugin_activation_notices()
{
    if( get_transient( 'vrtcl-admin-notice-startup' ) )
    { 
		$newpage = admin_url( 'post-new.php?post_type=page' );
    ?>
    <div class="updated notice is-dismissible">
        <br>
        <p><?php esc_html_e( 'Thank you for adding this plugin! There is one requirement prior to using VertyCal: Please make a blank PAGE with named ', 
            'vertycal' ); ?> 
        <strong><a href="<?php echo esc_url( $newpage ); ?>" 
                    title="<?php echo esc_html__( 'Sheduler', 'vertycal' ); ?>">
		<?php echo esc_html__( 'Sheduler', 'vertycal' ); ?></a></strong> </p>
        <br>
    </div>
    <?php delete_transient( 'vrtcl-admin-notice-startup' );
    }
} 
