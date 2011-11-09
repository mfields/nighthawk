
<p><?php esc_html_e( 'This post is password protected. To view it please enter your password below:', 'nighthawk' ); ?></p>
<form class="bullet" action="<?php echo esc_url( get_option( 'siteurl' ) . '/wp-pass.php' ); ?>" method="post">

	<label class="bullet-label" for="<?php echo esc_attr( 'password-form-' . get_the_id() ); ?>"><?php _e( 'Enter Password', 'nighthawk' ); ?></label>

	<input class="bullet-term" name="post_password" id="<?php echo esc_attr( 'password-form-' . get_the_id() ); ?>" type="password" size="20" />

	<input class="bullet-button" type="submit" name="Submit" value="<?php esc_attr_e( 'Unlock', 'nighthawk' ); ?>" />

</form>
