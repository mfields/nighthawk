<?php
/**
 * Post Format Template
 *
 * Displays all post format archive pages.
 *
 * This file closes all html tags that it opens.
 *
 * @package      Nighthawk
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        1.0
 */

if ( ! have_posts() ) {
	get_template_part( '404', $term );
}

get_header( $term );

?>

<div id="intro">
	<h1><?php print ucfirst( nighthawk_post_label_plural() ); ?></h1>
	<?php nighthawk_summary_meta( '<div id="intro-meta">', '</div>' ) ?>
</div>

<div id="content" class="contain">

<?php get_template_part( 'loop', $term ); ?>

</div><!--content-->

<div id="page-footer"><?php do_action( 'nighthawk_paged_navigation' ); ?></div>

<?php get_footer( $term ); ?>