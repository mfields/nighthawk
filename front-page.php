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
 * @since        1.0
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

	print "\n" . '<div id="intro">';

	the_title( "\n" . '<h1>', '</h1>' );

	print "\n" . '<div id="summary">';
	the_content( __( 'Continue Reading', 'nighthawk' ) );
	print "\n" . '</div>';

	wp_link_pages( array(
		'before' => '<div class="page-link contain">',
		'after'  => '</div>'
	) );

	print "\n" . '</div>';
}

/*
 * All other stickies.
 *
 * The remaining stickies, if any, will be
 * displayed in a custom list under the intro.
 */
if ( ! empty( $stickies ) ) {
	print "\n\n" . '<ul id="intro-list">';
	foreach ( $stickies as $i => $post ) {
		setup_postdata( $post );
		the_title( '<li><a href="' . esc_url( get_permalink() ) . '">', '</a></li>' );
	}
	print "\n" . '</ul>';
}

wp_reset_postdata();
?>

<div id="content" class="contain">

<?php
while ( have_posts() ) {
	the_post();
	if ( is_sticky() ) {
		continue;
	}
	get_template_part( 'entry', get_post_type() );
}
?>

</div><!--content-->

<div id="page-footer"><?php do_action( 'nighthawk_paged_navigation' ); ?></div>

<?php get_footer( 'post' ); ?>