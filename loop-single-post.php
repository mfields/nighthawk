<?php
/**
 * Single Post Loop
 *
 * Responsible for generating The Loop
 * for posts having the "post" post_type
 * in single views.
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
	<?php ghostbird_featured_image( '<div id="featured-image">', '</div>' ); ?>
	<?php the_title( '<h1>', '</h1>' ); ?>
	<p id="byline"><?php printf( esc_html__( 'By %1$s', 'ghostbird' ), get_the_author() ); ?></p>
	<?php ghostbird_summary_meta( '<div id="intro-meta">', '</div>' ); ?>
</div>

<div id="post-<?php the_ID(); ?>" <?php post_class( 'clear' ); ?>>

<?php

	do_action( 'ghostbird_entry_start' );
	
	print "\n" . '<div class="entry-content">';
	the_content();
	print "\n" . '</div><!--entry-content-->';

	wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'ghostbird' ), 'after' => '</div>' ) );

	print '<div class="' . esc_attr( ghostbird_entry_meta_classes() ) . '">';
	ghostbird_entry_meta_date();
	ghostbird_entry_meta_taxonomy();
	print '</div><!--meta-->';

	do_action( 'ghostbird_entry_end' );
?>

</div><!--entry-->

<?php
	}
}
?>