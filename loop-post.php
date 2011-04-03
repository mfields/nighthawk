<?php
/**
 * Post Archive Loop
 *
 * Responsible for generating The Loop
 * for posts having the "post" post_type
 * in archive views.
 *
 * @package      Ghostbird
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        1.1
 */

if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

<?php

	do_action( 'ghostbird_entry_start' );

	switch ( get_post_format() ) {
		case 'aside' :
		case 'link' :
			print "\n" . '<div class="entry-content">';
			the_content();
			print "\n" . '</div><!--entry-content-->';
			break;
		case 'gallery' :
			ghostbird_featured_image( '<div class="featured-image">', '</div>' );
			print "\n" . '<div class="entry-content">';
			the_excerpt();
			print "\n" . '</div><!--entry-content-->';
			print '<div class="' . esc_attr( ghostbird_entry_meta_classes() ) . '">';
			ghostbird_entry_meta_date();
			ghostbird_entry_meta_taxonomy();
			print '</div><!--meta-->';
			break;
		case 'status' :
			ghostbird_featured_image( '<div class="featured-image">', '</div>' );
			print "\n" . '<div class="entry-content">';
			the_content();
			print "\n" . '</div><!--entry-content-->';
			break;
		default :
			ghostbird_featured_image( '<div class="featured-image">', '</div>' );

			the_title( "\n" . '<h2 class="entry-title"><a href="' . get_permalink() . '">', '</a></h2>' );

			print "\n" . '<div class="entry-content">';
			the_content( __( 'Continue Reading', 'ghostbird' ) );
			print "\n" . '</div><!--entry-content-->';

			wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'ghostbird' ), 'after' => '</div>' ) );

			print '<div class="' . esc_attr( ghostbird_entry_meta_classes() ) . '">';
			ghostbird_entry_meta_date();
			ghostbird_entry_meta_taxonomy();
			print '</div><!--meta-->';
			break;
	}

	do_action( 'ghostbird_entry_end' );
?>

</div><!--entry-->

<?php
	}
}
?>