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
 * This file closes all html tags that it opens.
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

get_header();

?>

<div id="content">

<div id="intro">
	<h1><?php _e( 'Timeline', 'ghostbird' ) ?></h1>
</div>

<?php get_template_part( 'loop' ); ?>

<?php comments_template( '', true ); ?>

</div><!--content-->

<div class="clear"></div>

<?php get_footer(); ?>