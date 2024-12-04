<?php // @package VertyCal
if ( is_user_logged_in() && current_user_can( 'edit_posts' ) ) :  

	$dateFormati18n = 'yy-mm-dd';
	$labelexcerpt  = esc_html__( 'Note/Address/Contact', 'vertycal' );
	$labeltitle   = esc_html__( 'Title/Event',  'vertycal' );
	$labeldate   = esc_html__( 'Date', 'vertycal' );
	$labeltime  = esc_html__( 'Time', 'vertycal' );
	$labelcat  = esc_html__( 'Categorize as: ', 'vertycal' ); 
	$defcat = ''; 
	?>

<div class="vrtcl-form-wrapper">

	<form id="vnew_post" name="vnew_post" method="post" action="" 
		enctype="multipart/form-data">

		<fieldset class="field-content">
			<label for="title"><?php esc_html_e( $labeltitle ); ?></label>
			<input id="title" class="text_field" type="text" 
				   value="" tabindex="2" name="title" required />
		</fieldset>

		<fieldset class="field-content">
			<label for="vertycal_date_time_meta"><?php esc_html_e( $labeldate ); ?></label>
			<input id="VrtclDate" class="text_field" type="date" placeholder=""
				   value="" tabindex="3" name="vertycal_date_time_meta" />
		</fieldset>

		<fieldset class="field-content">
			<label for="vertycal_just_time_meta"><?php esc_html_e( $labeltime ); ?></label>
			<input id="vertycal_just_time_meta" class="text_field" type="time" 
			       value="" tabindex="4" name="vertycal_just_time_meta" />
		</fieldset>
	
		<fieldset class="field-content">
			<label for="vertycal_excerpt"><?php esc_html_e( $labelexcerpt ); ?></label>			
<textarea id="vertycal_excerpt" tabindex="5" name="vertycal_excerpt" rows="2" cols="20"></textarea>
		</fieldset>

		<fieldset class="field-content category-dropdown">
			<label for="vertycal_category"><?php esc_html_e( $labelcat ); ?></label>
			
<?php 
wp_dropdown_categories( 
'show_option_none='.$defcat.'&tab_index=6&taxonomy=vertycal_category&hide_empty=0&name=vertycal_category&id=vertycal_category' ); 
?>
	
		</fieldset>	
	
		<fieldset class="submit">
			<input type="submit" value="<?php esc_html_e( 'Save Entry', 'vertycal' ); ?>" 
				   tabindex="7" id="vrtcl_submit" name="vrctl_submit" class="vrtclsubmit" />
			<input id="vertycal_new_post_action" type="hidden" name="action" 
		    	   value="vertycal_new_post_action" />
		    <input type="hidden" id="current_user_id" name="current_user_id"
		           value="<?php echo esc_attr( get_current_user_id() ); ?>" />
		
			<input type="hidden" value="<?php echo esc_attr( wp_create_nonce( 'vertycal_new_post_nonce' )); ?>" 
				   name="vertycal_new_post_nonce" />

		</fieldset>
	</form>
	
</div>	
	<?php 
	else: 
       	echo vertycal_user_login_link(); ?>
	<?php 
endif; ?>
