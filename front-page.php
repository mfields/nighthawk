<?php
/**
 * Blog timeline template
 *
 * This file is responsible for creating the blog view.
 * In a default installation of WordPress, this will be
 * the home page. In instances where users have designated
 * a page to be used as their "Blog Page", this template
 * will be used instead of page.php.
 *
 * @package      Nighthawk
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        Nighthawk 1.0
 */

if ( ! have_posts() ) {
	get_template_part( '404', 'post' );
}

get_header( 'post' );

/*
 * Do we have sticky posts?
 * If so, we will store them in an array.
 */
$stickies = array();
while ( have_posts() ) {
	the_post();
	if ( ! is_sticky() ) {
		continue;
	}
	$stickies[] = $post;
}

/*
 * Display first sticky.
 *
 * The first sticky post will be displayed
 * in the intro div. We will pop it of the
 * sticky array before it is displayed.
 */
if ( ! empty( $stickies ) ) {
	$post = array_pop( $stickies );
	setup_postdata( $post );

	$featured_image = get_the_post_thumbnail();

	$class = '';
	if ( ! empty( $featured_image ) ) {
		$class = ' class="has-featured-image"';
	}

	echo "\n" . '<header id="intro"' . $class . '>';

	$title = __( 'Featured', 'nighthawk' );

	$post_title = the_title( '', '', false );
	if ( ! empty( $post_title ) ) {
		$title = $post_title;
	}

	echo "\n" . '<h1 id="document-title"><a href="' . esc_url( get_permalink() ) . '">' . $title . '</a></h1>';

	if ( ! empty( $featured_image ) ) {
		echo "\n" . '<div id="featured-image">';
		echo '<a href="' . esc_url( get_permalink() ) . '">' . $featured_image . '</a>';
		echo '</div>';
	}

	echo "\n" . '<div id="summary">';
	the_content( __( 'Continue Reading', 'nighthawk' ) );
	echo "\n" . '</div>';

	wp_link_pages( array(
		'before' => '<div class="page-link contain">',
		'after'  => '</div>'
	) );

	echo "\n" . '</header>';
}

/*
 * All other stickies.
 *
 * The remaining stickies, if any, will be
 * displayed in a custom list under the intro.
 */
if ( ! empty( $stickies ) ) {
	echo "\n\n" . '<ul id="intro-list">';
	foreach ( $stickies as $i => $post ) {
		setup_postdata( $post );
		the_title( '<li><a href="' . esc_url( get_permalink() ) . '">', '</a></li>' );
	}
	echo "\n" . '</ul>';
}

wp_reset_postdata();
?>

<div id="blog" class="contain">

<?php
while ( have_posts() ) {
	the_post();
	if ( is_sticky() ) {
		continue;
	}

	$context = get_post_type();
	$format = get_post_format();
	if ( ! empty ( $format ) )
		$context .= '-' . $format;

	get_template_part( 'entry', $context );
}
?>

</div><!--content-->

<?php get_template_part( 'nav-posts', 'front-page' ); ?>

<?php get_footer( 'post' ); ?>