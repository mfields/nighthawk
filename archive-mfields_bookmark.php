<?php
/**
 * Bookmark Archive.
 *
 * @todo         Dynamic post_type name.
 *
 * @package      Nighthawk
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        1.0
 */

$nighthawk_post_type = 'mfields_bookmark';

if ( ! have_posts() ) {
	get_template_part( '404', $nighthawk_post_type );
}

get_header( $nighthawk_post_type );

?>

<div id="intro">

	<h1>Bookmarks</h1>

	<div id="intro-meta"><?php

		global $wp_query;
		$total = 0;
		if ( isset( $wp_query->found_posts ) ) {
			$total = (int) $wp_query->found_posts;
		}

		printf( _n( 'There is %1$s bookmark in this section.', 'There are %1$s bookmarks in this section.', $total, 'nighthawk' ), number_format_i18n( $total ) );

		print ' <span class="subscribe"><a href="' . esc_url( get_post_type_archive_feed_link( $nighthawk_post_type ) ) . '" title="' . esc_attr( __( 'Get updated whenever a new bookmark is published.', 'nighthawk' ) ) . '">' . esc_html__( 'Subscribe', 'nighthawk' ) . '</a></span>';

	?></div>

</div>

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
			'label'    => __( 'Comment Link', 'nighthawk' ),
			'class'    => 'comment-respond',
			'callback' => 'nighthawk_td_comment_icon',
		),
	) );

	query_posts( wp_parse_args( $query_string, array( 'posts_per_page' => 30 ) ) );
	get_template_part( 'loop-bookmark-table' )
?>

</div><!--content-->

<div id="page-footer"><?php do_action( 'nighthawk_paged_navigation' ); ?></div>

<?php get_footer( 'archive-' . $nighthawk_post_type ); ?>