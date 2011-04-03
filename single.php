<?php
/**
 * Default Single Post Template.
 *
 * This template is loaded whenever a single
 * post is being viewed. This template has been
 * coded to handle any public post_type.
 *
 * @package      Ghostbird
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        1.0
 * @alter        1.1
 */

if ( ! have_posts() ) {
	get_template_part( '404' );
}

$ghostbird_post_type = get_post_type();

get_header( $ghostbird_post_type );

?>

<div id="content">

<?php
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
?>

<div id="intro">
<?php
	/* Title. */
	if ( post_type_supports( $ghostbird_post_type, 'title' ) ) {
		the_title( "\n" . '<h1>', '</h1>' );
	}

	/* Byline. */
	if ( post_type_supports( $ghostbird_post_type, 'author' ) ) {
		print "\n" . '<p id="byline">' . sprintf( esc_html__( 'By %1$s', 'ghostbird' ), get_the_author() ) . '</p>';
	}
?>
</div>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php do_action( 'ghostbird_entry_start' ); ?>

	<?php ghostbird_featured_image( '<div class="featured-image">', '</div>' ); ?>

	<div class="entry-content">
	<?php the_content(); ?>
	</div><!--entry-content-->

	<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'ghostbird' ), 'after' => '</div>' ) ); ?>

	<div class="<?php print esc_attr( ghostbird_entry_meta_classes() ); ?>">
		<?php ghostbird_entry_meta_date(); ?>
		<?php ghostbird_entry_meta_taxonomy(); ?>
	</div><!--meta-->

	<?php do_action( 'ghostbird_entry_end' ); ?>

</div><!--entry-->

<?php
	}
}
?>

<?php get_template_part( 'biography', $ghostbird_post_type ); ?>

<?php comments_template( '', true ); ?>

</div><!--content-->

<div class="clear"></div>

<?php get_footer( $ghostbird_post_type ); ?>