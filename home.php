<?php
/**
 * Blog and Default Timeline.
 *
 * @package      Nighthawk
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        Nighthawk 1.0
 */

if ( ! have_posts() ) {
	get_template_part( '404', 'home' );
}

get_header( 'home' );

?>

<div id="content" class="contain">

<?php
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		get_template_part( 'entry', get_post_type() );
	}
}
?>

<?php comments_template( '', true ); ?>

</div><!--content-->

<?php get_template_part( 'nav-posts', 'home' ); ?>

<?php get_footer( 'home' ); ?>