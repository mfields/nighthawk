<?php
/**
 * Default Single Post Template.
 *
 * This template is loaded whenever a single
 * post is being viewed. This template has been
 * coded to handle any public post_type.
 *
 * @package      Nighthawk
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        Nighthawk 1.0
 */

get_header();

if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
?>

<header id="intro" <?php post_class( 'contain' ); ?>>
	<?php the_title( "\n" . '<h1 id="document-title">', '</h1>' ); ?>
	<div id="dateline"><?php
		printf( __( 'Uploaded on %1$s', 'nighthawk' ), '<time class="date" pubdate="pubdate" datetime="' . esc_attr( get_post_time( 'Y-m-d\TH:i:s\Z', true ) ) . '">' . esc_html( get_post_time( get_option( 'date_format' ) ) ) . '</time>' );
	?></div>
</header>

<div id="content" <?php post_class( 'image contain' ); ?>>

	<?php do_action( 'nighthawk_entry_start' ); ?>

	<div id="image"><?php
		echo wp_get_attachment_image( get_the_ID(), array( $content_width, $content_width ) );
	?></div>

	<?php if ( '' != get_post_field( 'post_excerpt', get_the_ID() ) ) : ?>
		<div class="wp-caption-text">
			<?php the_excerpt(); ?>
		</div><!--entry-content-->
	<?php endif; ?>

	<?php if ( '' != get_post_field( 'post_content', get_the_ID() ) ) : ?>
		<div class="description">
			<?php the_content(); ?>
		</div><!--entry-content-->
	<?php endif; ?>

	<nav id="image-navigation" class="paged-navigation">

		<?php $parent_ID = wp_get_post_parent_id( 0 ); ?>
		<?php if ( ! empty( $parent_ID ) ) : ?>
			<?php $link_text = ( 'gallery' == get_post_format( $parent_ID ) ) ? get_post_format_string( 'gallery' ) : get_the_title( $parent_ID ); ?>
			<p class="return-to-parent"><a href="<?php echo esc_attr( get_permalink( $parent_ID ) ); ?>" title="<?php echo esc_attr( sprintf( __( 'Return to %s', 'shaan' ), strip_tags( $link_text ) ) ); ?>"><?php printf( __( 'Return to: %s', 'shaan' ), '<em>' . $link_text . '</em>' ); ?></a></p>
		<?php endif; ?>
	</nav>

	<?php do_action( 'nighthawk_entry_end' ); ?>

<?php
	}
}
?>

<?php comments_template( '', true ); ?>

</div><!--content-->

<div id="page-footer">
	<h1 class="assistive-text"><?php _e( 'Image navigation', 'shaan' ); ?></h1>
	<div class="nav-paged timeline-regress"><?php previous_image_link( false, __( 'Previous', 'nighthawk' ) ); ?></div>
	<div class="nav-paged timeline-progress"><?php next_image_link( false, __( 'Next', 'nighthawk' ) ); ?></div>
</div>

<?php get_footer( $nighthawk_post_type ); ?>
