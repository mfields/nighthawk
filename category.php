<?php
/**
 * Category Template
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
	get_template_part( '404', 'category' );
}

get_header( 'category' );

?>

<header id="intro">
	<?php echo apply_filters( 'taxonomy-images-queried-term-image', '', array(
		'size'   => 'post-thumbnail',
		'before' => '<div id="featured-image">',
		'after'  => '</div>',
		) ); ?>
	<h1 id="document-title"><?php single_cat_title(); ?></h1>
	<div id="summary"><?php echo category_description(); ?></div>
	<div id="intro-meta">
		<?php printf( _n( 'There is %1$s entry in this category.', 'There are %1$s entries in this category.', (int) $wp_query->found_posts, 'nighthawk' ), number_format_i18n( (int) $wp_query->found_posts ) ); ?>
		<span class="subscribe"> <a href="<?php echo esc_url( get_category_feed_link( $wp_query->get_queried_object_id() ) ); ?>" title="<?php printf( esc_attr__( 'Get updated whenever a new entry is added to the %1$s category.', 'nighthawk' ), single_cat_title( '', false ) ); ?>"><?php esc_html_e( 'Subscribe', 'nighthawk' ); ?></a></span>
	</div>
</header>

<div id="content" class="contain">

<?php
	query_posts( wp_parse_args( $query_string, array( 'posts_per_page' => 30 ) ) );
	get_template_part( 'loop-table' );
?>

</div><!--content-->

<?php get_template_part( 'nav-posts', 'category' ); ?>

<?php get_footer( 'category' ); ?>