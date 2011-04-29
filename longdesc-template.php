<?php
	get_header( 'basic' );

	print '<div id="content">';
	print '<div class="entry">';

	the_content();

	if( isset( $_GET['referrer'] ) && function_exists( 'longdesc_return_anchor' ) ) {
		$uri = get_permalink( (int) $_GET['referrer'] );
		if( !empty( $uri ) ) {
			$uri.= '#' . longdesc_return_anchor( $id );
			print '<p><a class="button" href="' . esc_url( $uri ) . '">' . esc_html__( 'Return to article.', 'nighthawk' ) . '</a></p>';
		}
	}

	print '</div>';
	print '</div>';

	get_footer( 'basic' );
?>