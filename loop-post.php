<?php
/**
 * Posts Loop
 *
 * Responisible for generating The Loop
 * for posts having the "post" post_type.
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

<div id="intro">
	<?php the_title( '<h1>', '</h1>' ); ?>
	<p id="byline"><?php printf( esc_html__( 'By %1$s', 'ghostbird' ), get_the_author() ); ?></p>
	<?php ghostbird_summary( '<div id="summary">', '</div>' ); ?>
	<?php ghostbird_summary_meta( '<div id="intro-meta">', '</div>' ); ?>
</div>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

<?php

	do_action( 'ghostbird_entry_start' );

	switch ( get_post_format() ) {
		case 'aside' :
		case 'link' :
			print "\n" . '<div class="entry-content">';
			the_content( __( 'Continue Reading', 'ghostbird' ) );
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
			the_content( __( 'Continue Reading', 'ghostbird' ) );
			print "\n" . '</div><!--entry-content-->';
			break;
		default :
			ghostbird_featured_image( '<div class="featured-image">', '</div>' );
			/*
			 * Title only for multiple views.
			 * Will be displayed in single views via ghostbird_title() in an H1 element.
			 */
			if ( ! is_singular() ) {
				the_title( "\n" . '<h2 class="entry-title"><a href="' . get_permalink() . '">', '</a></h2>' );
			}

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