<?php
/**
 * Page Template.
 *
 * This template is loaded whenever a single page is being viewed.
 *
 * @package      Ghostbird
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        1.1
 */

if ( ! have_posts() ) {
	get_template_part( '404', 'page' );
}

get_header( 'page' );

?>

<div id="content">

<?php get_template_part( 'loop', 'page' ); ?>

<?php ghostbird_author_bio(); ?>

<?php comments_template( '', true ); ?>

</div><!--content-->

<div class="clear"></div>

<?php get_footer( 'page' ); ?>