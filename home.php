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

if ( ! have_posts() )
	get_template_part( '404', 'post' );

get_header( 'post' );

get_template_part( 'sticky-posts' );

?>

<div id="blog" class="contain">

<?php
while ( have_posts() ) {

	the_post();

	if ( is_home() && ! is_paged() && is_sticky() )
		continue;

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