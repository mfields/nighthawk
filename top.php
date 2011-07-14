<?php

	if ( 0 != (int) get_theme_mod( 'nighthawk_display_site_title', 1 ) ) {
		$text = get_bloginfo( 'blogname' );
		if ( ! empty( $text ) ) {
			if ( ! is_front_page() || is_paged() ) {
				$text = '<a href="' . esc_url( home_url() ) . '">' . esc_html( $text ) . '</a>';
			}
			print "\n" . '<div id="site-title">' . $text . '</div>';
		}
	}

	if ( 0 != (int) get_theme_mod( 'nighthawk_display_tagline', 1 ) ) {
		$text = get_bloginfo( 'description' );
		if ( ! empty( $text ) ) {
			print "\n" . '<div id="tagline">' . esc_html( $text ) . '</div>';
		}
	}

	wp_nav_menu( apply_filters( 'nighthawk_menu_args_primary', array(
		'container'      => 'div',
		'container_id'   => 'menu-top',
		'menu_class'     => 'menu',
		'theme_location' => 'primary',
		'depth'          => 1,
		'items_wrap'     => '<ul id="%1$s" class="%2$s" role="navigation">%3$s</ul>',
		'fallback_cb'    => '_nighthawk_menu_dialog',
	) ) );
