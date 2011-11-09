<?php
/**
 * Tag Template
 *
 * This file closes all html tags that it opens.
 *
 * @package      Nighthawk
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        Nighthawk 1.0
 */

if ( ! have_posts() ) {
	get_template_part( '404', 'tag' );
}

get_header( 'tag' );

?>

<header id="intro">
	<?php print apply_filters( 'taxonomy-images-queried-term-image', '', array(
		'size'   => 'post-thumbnail',
		'before' => '<div id="featured-image">',
		'after'  => '</div>',
		) ); ?>
	<h1 id="document-title"><?php single_tag_title(); ?></h1>
	<div id="summary"><?php print tag_description(); ?></div>
	<div id="intro-meta">
		<?php printf( _n( '%1$s entry has been tagged with the term &#8220;%2$s&#8221.', '%1$s entries have been tagged with the term &#8220;%2$s&#8221.', (int) $wp_query->found_posts, 'nighthawk' ), number_format_i18n( (int) $wp_query->found_posts ), single_tag_title( '', false ) ); ?>
		<span class="subscribe"> <a href="<?php print esc_url( get_tag_feed_link( $wp_query->get_queried_object_id() ) ) ?>" title="<?php printf( esc_attr__( 'Get updated whenever a new entry is tagged with the term &#8220;%1$s&#8221;.', 'nighthawk' ), single_tag_title( '', false ) ); ?>"><?php esc_html_e( 'Subscribe', 'nighthawk' ) ?></a></span>
	</div>
</header>

<div id="content" class="contain">

<?php
	query_posts( wp_parse_args( $query_string, array( 'posts_per_page' => 30 ) ) );
	get_template_part( 'loop-table' );
?>

</div><!--content-->

<?php get_template_part( 'nav-posts', 'tag' ); ?>

<?php get_footer( 'archive-tag' ); ?>