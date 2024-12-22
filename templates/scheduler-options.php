<div class="vrtcl-options-block">

  <p id="date"></p>
  <div id="clock">
    <p class="unit" id="hours"></p>
    <p class="unit" id="minutes"></p>
    <p class="unit" id="seconds"></p>
    <p class="unit" id="ampm"></p>
  </div>

</div><div class="vrtclclearfix"></div>

    <?php // Load scripts to footer if this page is accessed.
    
        add_action( 'wp_footer', 'vertycal_options_tab_footer_scripts' ); 
 
    ?>

  <div class="vrtcl-docs">
  <?php 
  ob_start(); 
  ?>
  <hr> <?php echo wp_kses_post( vertycal_get_options_page_post() ); ?> <hr>

      <?php  
      echo ob_get_clean();                       // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
      ?>

  </div>
