<?php
/**
 * Taxonomy Template
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
	get_template_part( '404', $taxonomy );
}

get_header( $taxonomy );

?>

<header id="intro">
	<?php print apply_filters( 'taxonomy-images-queried-term-image', '', array(
		'size'   => 'post-thumbnail',
		'before' => '<div id="featured-image">',
		'after'  => '</div>',
		) ); ?>
	<h1><?php single_term_title() ?></h1>
	<div id="summary"><?php print term_description(); ?></div>
	<?php nighthawk_summary_meta( '<div id="intro-meta">', '</div>' ) ?>
</header>

<div id="content" class="contain">

<?php
	query_posts( wp_parse_args( $query_string, array( 'posts_per_page' => 30 ) ) );
	get_template_part( 'loop-table' );
?>

</div><!--content-->

<?php get_template_part( 'nav-posts', 'taxonomy' ); ?>

<?php get_footer( $taxonomy ); ?>





<?php
