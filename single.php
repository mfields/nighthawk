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
 * @since        1.0
 */

if ( ! have_posts() ) {
	get_template_part( '404' );
}

$nighthawk_post_type = get_post_type();

get_header( $nighthawk_post_type );

?>

<?php
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
?>

<div id="intro">
<?php
	/* Title. */
	if ( post_type_supports( $nighthawk_post_type, 'title' ) ) {
		the_title( "\n" . '<h1>', '</h1>' );
	}

	/* Date Information. */
	if ( apply_filters( 'nighthawk-show-date-for-single-' . $nighthawk_post_type, true ) ) {
		print "\n" . '<div id="dateline">';
		printf( __( 'Posted on %1$s', 'nighthawk' ), '<time class="date" pubdate="pubdate" datetime="' . esc_attr( get_post_time( 'Y-m-d\TH:i:s\Z', true ) ) . '">' . esc_html( get_the_time( sprintf( __( '%1$s \a\t %2$s', 'nighthawk' ), get_option( 'date_format' ), get_option( 'time_format' ) ) ) ) . '</time>' );
		print "\n" .'</div>';
	}

	/* Byline. */
	if ( post_type_supports( $nighthawk_post_type, 'author' ) ) {
		print "\n" . '<p id="byline">' . sprintf( esc_html__( 'By %1$s', 'nighthawk' ), get_the_author() ) . '</p>';
	}
?>
</div>

<div id="content" class="contain">

<?php get_template_part( 'entry', get_post_type() ); ?>

<?php
	}
}
?>

<?php get_template_part( 'biography', $nighthawk_post_type ); ?>

<?php comments_template( '', true ); ?>

</div><!--content-->

<div id="page-footer">
	<?php previous_post_link( '<div class="nav-paged timeline-regress">%link</div>', __( 'Next', 'nighthawk' ) ); ?>
	<?php next_post_link( '<div class="nav-paged timeline-progress">%link</div>', __( 'Back', 'nighthawk' ) ); ?>
</div>

<?php get_footer( $nighthawk_post_type ); ?>