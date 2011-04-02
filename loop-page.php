<?php
/**
 * Page Loop
 *
 * Responisible for generating The Loop
 * for pages in single views.
 *
 * @package      Ghostbird
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        1.1
 */

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

	<?php do_action( 'ghostbird_entry_start' ); ?>

	<div class="entry-content">
	<?php the_content(); ?>
	</div><!--entry-content-->

	<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'ghostbird' ), 'after' => '</div>' ) ); ?>

	<?php do_action( 'ghostbird_entry_end' ); ?>

	</div><!--entry-->

<?php
}