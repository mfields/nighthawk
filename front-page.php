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

?>



<?php
if ( ! have_posts() ) {
	/**
	 * @todo Some kinda 404 stuff here...
	 */
}

/*
 * Loop for Sticky Posts.
 */
$stickies = array();
while ( have_posts() ) {
	the_post();
	if ( ! is_sticky() ) {
		continue;
	}
	$stickies[] = $post;
}

if ( ! empty( $stickies ) ) {
	$post = array_pop( $stickies );
	setup_postdata( $post );

	print "\n" . '<div id="intro">';

	the_title( "\n" . '<h1>', '</h1>' );

	print "\n" . '<div id="summary">';
	the_content();
	print "\n" . '</div>';

	wp_link_pages( array(
		'before' => '<div class="page-link contain">',
		'after'  => '</div>'
	) );

	print "\n" . '</div>';
}

if ( ! empty( $stickies ) ) {
	foreach ( $stickies as $i => $stickies ) {
		the_title( '<h2>', '</h2>' );
	}
}

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