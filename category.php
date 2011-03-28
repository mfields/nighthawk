<?php
/**
 * Category Template
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
	get_template_part( '404', 'category' );
}

get_header( 'category' );

?>

<div id="content">

	<div id="intro">
		<h1><?php single_cat_title(); ?></h1>
		<div id="summary"><?php category_description(); ?></div>
		<div id="intro-meta">
			<?php printf( _n( 'There is %1$s entry in this category.', 'There are %1$s entries in this category.', (int) $wp_query->found_posts, 'ghostbird' ), number_format_i18n( (int) $wp_query->found_posts ) ); ?>
			<span class="subscribe"> <a href="<?php print esc_url( get_category_feed_link( $wp_query->get_queried_object_id() ) ) ?>" title="<?php printf( esc_attr__( 'Get updated whenever a new entry is added to the %1$s category.', 'ghostbird' ), single_cat_title( '', false ) ); ?>"><?php esc_html_e( 'Subscribe', 'ghostbird' ) ?></a></span>
		</div>
	</div>

<?php get_template_part( 'loop', 'category' ); ?>

</div><!--content-->

<div class="clear"></div>

<?php get_footer( 'category' ); ?>