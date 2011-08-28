<?php
/**
 * Default Entry Template.
 *
 * Displays a single post.
 * This file will attempt to display as much info
 * as possible for the post_type.
 *
 * @package      Nighthawk
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        1.0
 */
?>

<div id="<?php nighthawk_entry_id(); ?>" <?php post_class(); ?>>

<?php
	do_action( 'nighthawk_entry_start' );

	$nighthawk_post_type = get_post_type();

	/* Title. */
	if ( post_type_supports( $nighthawk_post_type, 'title' ) ) {
		the_title( "\n" . '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' );
	}

	/* Content. */
#	if ( post_type_supports( $nighthawk_post_type, 'editor' ) ) {
		print "\n" . '<div class="entry-content">';
		the_excerpt();
	#	the_content( __( 'Continue Reading', 'nighthawk' ) );
		print "\n" . '</div><!--entry-content-->';
		wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'nighthawk' ), 'after' => '</div>' ) );
#	}
	/* Excerpt - Attempt to display excerpt only if the post_type does not support content. */
#	else if ( post_type_supports( $nighthawk_post_type, 'excerpt' ) ) {
#		print "\n" . '<div class="entry-content">';
#		the_excerpt();
#		print "\n" . '</div><!--entry-content-->';
#	}

	do_action( 'nighthawk_entry_end' );
?>

</div><!--entry-->

<?php do_action( 'nighthawk_append_to_entry_template' ); ?>