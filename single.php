<?php
/**
 * Post Template.
 *
 * This template is loaded whenever a single post is being viewed.
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

$ghostbird_post_type = get_post_type();

get_header( $ghostbird_post_type );

?>

<div id="content">

<?php get_template_part( 'loop', $ghostbird_post_type ) ?>

<?php ghostbird_author_bio(); ?>

<?php comments_template( '', true ); ?>

</div><!--content-->

<div class="clear"></div>

<?php get_footer( $ghostbird_post_type ); ?>