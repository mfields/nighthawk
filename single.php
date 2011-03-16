<?php
/**
 * Post Template.
 *
 * This template is loaded whenever a single post is being viewed.
 *
 * @package      Ghostbird
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        1.0
 */

get_header();

?>

<div id="content">

<div id="intro">
	<?php ghostbird_title( '<h1>', '</h1>' ); ?>
	<?php ghostbird_byline( '<p id="byline">', '</p>' ); ?>
	<?php ghostbird_summary( '<div id="summary">', '</div>' ); ?>
	<?php ghostbird_summary_meta( '<div id="intro-meta">', '</div>' ); ?>
</div>

<?php
while ( have_posts() ) {
	the_post();
?>
	<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php do_action( 'ghostbird_entry_start' ); ?>

	<?php ghostbird_featured_image( '<div class="featured-image">', '</div>' ); ?>

	<div class="entry-content">
	<?php the_content( __( 'more', 'ghostbird' ) ); ?>
	</div><!--entry-content-->

	<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'ghostbird' ), 'after' => '</div>' ) ); ?>

	<div class="entry-meta">
	<?php ghostbird_entry_meta_date(); ?>
	<?php ghostbird_entry_meta_taxonomy(); ?>
	</div><!--meta-->

	<?php do_action( 'ghostbird_entry_end' ); ?>

	</div><!--entry-->

<?php
}
?>

<?php ghostbird_author_bio(); ?>

<?php comments_template( '', true ); ?>

</div><!--content-->

<div class="clear"></div>

<?php get_footer(); ?>