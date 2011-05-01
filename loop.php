<?php
/**
 * The Default Loop for Archives.
 *
 * Generate The Loop for any post_type.
 * This file is basically a fallback for
 * custom post_types that do not have a
 * dedicated loop file. This file will
 * attempt to display as much info as
 * possible for the post_type.
 *
 * @package      Nighthawk
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        1.0
 */

if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		$nighthawk_post_type = get_post_type();
?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

<?php do_action( 'nighthawk_entry_start' ); ?>

<?php

	/* Title. */
	if ( post_type_supports( $nighthawk_post_type, 'title' ) ) {
		the_title( "\n" . '<h2 class="entry-title">', '</h2>' );
	}

	/* Content. */
	if ( post_type_supports( $nighthawk_post_type, 'editor' ) ) {
		print "\n" . '<div class="entry-content">';
		the_content( __( 'Continue Reading', 'nighthawk' ) );
		print "\n" . '</div><!--entry-content-->';
		wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'nighthawk' ), 'after' => '</div>' ) );
	}
	/* Use excerpt if post_type supports it instead of content. */
	else if ( post_type_supports( $nighthawk_post_type, 'excerpt' ) ) {
		print "\n" . '<div class="entry-content">';
		the_excerpt();
		print "\n" . '</div><!--entry-content-->';
	}

	do_action( 'nighthawk_entry_end' );
?>

</div><!--entry-->

<?php
	}
}