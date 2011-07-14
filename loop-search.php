<?php
/**
 * Displays a Post in Search.
 *
 * @package      Nighthawk
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        1.0
 */

remove_filter( 'the_excerpt', 'wpautop' );

if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();

		print '<div id="'; nighthawk_entry_id(); print '" '; post_class(); print '>';

		do_action( 'nighthawk_entry_start' );

		the_title( "\t" . '<h2 class="entry-title heading">', '</h2>' );

		print "\n\t" . '<p class="content">';

		print "\n\t\t" . '<time class="date" pubdate="pubdate" datetime="' . esc_attr( get_post_time( 'Y-m-d\TH:i:s\Z', true ) ) . '">' . esc_html( get_the_time( 'M j, Y' ) ) . '</time> &#8211; ';

		print "\n\t\t" . '<span class="post-excerpt">';
		the_excerpt();
		print '</span>';

		print "\n\t\t" . '<a class="permalink" href="' . esc_url( get_permalink() ) . '">' . sprintf( esc_html__( 'View this %1$s', 'ghostbird' ), nighthawk_post_label_singular() ) . '</a>';

		print "\n\t" .'</p>';

		do_action( 'nighthawk_entry_end' );

		print "\n" . '</div>';
		
		do_action( 'nighthawk_append_to_entry_template' );
	}
}

add_filter( 'the_excerpt', 'wpautop' );
