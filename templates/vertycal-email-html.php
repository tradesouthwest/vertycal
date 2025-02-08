<?php 
/** 
 * @package VertyCal Plugin 
 * @subpackage templates/vertycal-email-html
 * @since 1.0.1
 */
if ( ! defined( 'ABSPATH' ) ) {	exit; } // exit if file is called directly

$vertycal_postid = '';

// set defaults as empty
$destination = $subject = $message = $vertycal_email = $vertycal_from = $user_dname = ''; 

/**
 * Validate page requested
 * @param string $vertycal_postid Uses esc_attr not absint due to string could be empty  
 */
if( ! isset( $_POST['vertycal-markdone-nonce'] ) || 
    ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['vertycal-markdone-nonce'] ) ), 'vertycal_markdone_nonce'))  
{ 
    printf( '<div class="alert alert-danger" id="newsletterError">
    <p>%s</p>
    </div>',
        esc_html__( 'Not Delivered Could not verify', 'vertycal' )
    );
} 
    else 
    { 
    //get the info from the from the form

    $vertycal_from   = isset( $_POST['vertycal_from'] ) ? sanitize_email( wp_unslash( $_POST['vertycal_from'])) : '';
    $vertycal_postid = isset( $_POST['vertycal_postid']) ? sanitize_text_field( wp_unslash( $_POST['vertycal_postid'] )) : '';
    $user_dname      = isset( $_POST['user_dname'] ) ? sanitize_text_field( wp_unslash( $_POST['user_dname'] ) ) : '';
	    
ob_start();
echo 
    '<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <body>
    <div style="margin: 0; padding: 0;">
    <div style="width:570px; padding:0 0 0 20px; margin:50px auto 12px auto" id="email_header">
    <table align="center" border="1" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse;border-color:#646464;">
    <tbody>
    <tr><td bgcolor="#ffffff" height="10">&nbsp;</td></tr>
    <tr>
    <td style="color: #333333; font-family: Arial, sans-serif; font-size: 14px;">
        <p>' . esc_html__( 'Marking Updated Status: ', 'vertycal' ) . ' 
        ID# ' . absint( $vertycal_postid ) . '</p>
    </td>
    </tr>
    <tr>
    <td style="color: #333333; font-family: Arial, sans-serif; font-size: 14px;">
    <p>' . esc_html__( 'Received From: ', 'vertycal' ) . ' ' . esc_html( $vertycal_from ) . '</p>
    </td>
    </tr>
    <tr>
    <td style="color: #333333; font-family: Arial, sans-serif; font-size: 14px;">
        <div><p>' . esc_html__( 'Status.', 'vertycal' ) . '</p>
        <p><em><small>' . esc_html__( 'Status should be marked by an administrator or proper person to update status accordingly. This is only a message that the person sending this report is validating the status.', 
        'vertycal' ) . '</small></em></p>
    </td>
    </tr>
    <tr><td bgcolor="#ffffff" height="10">&nbsp;</td></tr>
    </tbody>
    </table>
    </div></div>
    </body>
    </html>';

    $message = ob_get_contents();
    ob_end_clean();

    /**
     * Set the form headers
     * @param $send_to string Fallback for empty Option
     * TODO                   Could add Cc: headers 
     */
    $send_to        = get_option('admin_email');
    
    // Sanitized as string later
    $vertycal_email = get_option('vertycal_options')['vertycal_email_field_1'];

    /* @uses esc_attr since we dont know what ISP does to headers. */
    $theadname      = esc_attr( vertycal_display_thead() );
    $topic          = esc_attr__( 'Notification from Scheduler', 'vertycal' );
    /* @maybe-use $attachments = array( WP_CONTENT_DIR . '/uploads/file_to_attach.pdf' ); */
    $subject        = $theadname . ' / ' . $topic;
    $headers[]      = "MIME-Version: 1.0\r\n";
    $headers[]      = 'Content-type:text/html';
    $headers[]      = 'From: ' . sanitize_email( $vertycal_from );
    $destination    = ( empty( $vertycal_email ) ) ? sanitize_email( $send_to ) 
                      : sanitize_email( $vertycal_email );
    /*
     * Get content type text/html or text
     */
	//add_filter( 'wp_mail_content_type', 'Content-type: text/html' );

	/**
     * Send Mail
     * 
     * @uses wp_mail($to, $subject, $message, $headers, $attachment)
     * @param $sending string Use string to verify wp_mail 
     */
	$sending = wp_mail( $vertycal_from, $subject, $message, $headers );

    // Reset content-type to avoid conflicts https://core.trac.wordpress.org/ticket/23578
    // remove_filter( 'wp_mail_content_type', 'Content-type: text/html' );

    // Clean strings
    $destination = $subject = $message = $vertycal_from = $user_dname = null; 
    //$attachments = null;
    
    if( $sending ) : 
        echo '<div class="alert alert-success" id="newsletterSuccess">
        <p>' . esc_html__( 'Delivered', 'vertycal' ) . ' ' . esc_attr( $vertycal_postid ) . '</p>
        </div><br>';
    else: // Unhook
        echo '<div class="alert alert-danger" id="newsletterError">
        <p>' . esc_html__( 'Not Delivered', 'vertycal' ) . ' ' . esc_attr( $vertycal_postid ) . '</p>
        </div><br>';
    endif;
    }
