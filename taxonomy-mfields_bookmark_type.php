<?php
/**
 * Bookmark Tyes Taxonomy Template
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

	<?php

	$sentence = sprintf( _n( 'This site contains one %2$s labeled as %4$s.', 'This site contains %1$s %3$s labeled as %4$s.', Nighthawk::post_total(), 'nighthawk' ),
		number_format_i18n( Nighthawk::post_total() ),
		'<a href="' . esc_url( get_post_type_archive_link( 'mfields_bookmark' ) ) . '">' . nighthawk_post_label_singular() . '</a>',
		'<a href="' . esc_url( get_post_type_archive_link( 'mfields_bookmark' ) ) . '">' . nighthawk_post_label_plural() . '</a>',
		single_term_title( '', false )
	);

	$feed_title = sprintf( esc_attr__( 'Get updated when a new %1$s by %2$s is published.', 'nighthawk' ), nighthawk_post_label_singular(), single_term_title( '', false ) );

	$feed_url = get_term_feed_link( get_queried_object_id(), get_query_var( 'taxonomy' ) );

	$sentence.= ' <span class="subscribe"><a href="' . esc_url( $feed_url ) . '" title="' . esc_attr( $feed_title ) . '">' . esc_html__( 'Subscribe', 'nighthawk' ) . '</a></span>';

	print '<div id="intro-meta">' . $sentence . '</div>';

	?>

</header>

<div id="content" class="contain">

<?php
	Nighthawk::set_columns( array(
		array(
			'label'    => __( 'Post Title', 'nighthawk' ),
			'class'    => 'post-title',
			'callback' => 'nighthawk_td_title',
		),
		array(
			'label'    => __( 'Source', 'nighthawk' ),
			'class'    => 'bookmark-source',
			'callback' => 'nighthawk_td_bookmark_source',
		),
		array(
			'label'    => __( 'Permalink', 'nighthawk' ),
			'class'    => 'permalink',
			'callback' => 'nighthawk_td_permalink_icon',
		),
	) );

	query_posts( wp_parse_args( $query_string, array( 'posts_per_page' => 30 ) ) );
	get_template_part( 'loop-table' );
?>

</div><!--content-->

<?php get_template_part( 'nav-posts', $taxonomy ); ?>

<?php get_footer( $taxonomy ); ?>
