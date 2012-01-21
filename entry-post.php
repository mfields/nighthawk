<?php
/**
 * Template for displaying post entries.
 *
 * @package      Nighthawk
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        Nighthawk 1.0
 */
?>

<div id="post-<?php the_ID(); ?>" <?php post_class( 'contain' ); ?>>

	<?php $featured_image = get_the_post_thumbnail(); ?>

	<?php if ( ! empty( $featured_image ) ) : ?>
		<div class="featured-image"><a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo $featured_image; ?></a></div>
	<?php endif; ?>

	<?php if ( ! is_singular() ) : ?>
		<?php the_title( "\n" . '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' ); ?>
	<?php endif; ?>

	<div class="entry-content">
		<?php the_content( __( 'Continue Reading', 'nighthawk' ) ); ?>
	</div><!--entry-content-->

	<?php wp_link_pages( array(
		'before' => '<div class="page-link">' . __( 'Pages:', 'nighthawk' ),
		'after'  => '</div>'
	) ); ?>

	<div class="entry-terms">
		<?php nighthawk_entry_meta_taxonomy(); ?>
	</div><!--meta-->

</div><!--entry-->

<?php do_action( 'nighthawk_append_to_entry_template' ); ?>
