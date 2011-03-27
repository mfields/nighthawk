<?php
/**
 * Post Format Template
 *
 * Displays all post format archive pages.
 *
 * This file closes all html tags that it opens.
 *
 * @package      Ghostbird
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        1.1
 */

if ( ! have_posts() ) {
	get_template_part( '404' );
}

get_header();

?>

<div id="content">

	<div id="intro">
		<h1><?php print ucfirst( ghostbird_post_label_plural() ); ?></h1>
		<?php ghostbird_summary_meta( '<div id="intro-meta">', '</div>' ) ?>
	</div>

<?php get_template_part( 'loop', 'taxonomy' ); ?>

</div><!--content-->

<div class="clear"></div>

<?php get_footer(); ?>