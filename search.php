<?php
/**
 * Search Template.
 *
 * Removes the wpautop function from the "the_excerpt" filter
 * for the duration of the loop.
 *
 * @package      Nighthawk
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        Nighthawk 1.0
 */
?>

<?php get_header( 'search' ); ?>

<header id="intro">
	<h1 id="document-title"><?php _e( 'Search Results', 'nighthawk' ) ?></h1>

	<?php get_search_form(); ?>

	<div id="intro-meta">
	<?php esc_html_e( sprintf( _n( '%1$s result was found for "%2$s".', '%1$s results were found for "%2$s".', (int) $wp_query->found_posts, 'nighthawk' ), number_format_i18n( (int) $wp_query->found_posts ), get_search_query() ) ); ?>
	</div>
</header>

<div id="content" class="contain">

<?php get_template_part( 'loop-search', get_post_type() ); ?>

</div><!--content-->

<?php get_template_part( 'nav-posts', 'search' ); ?>

<?php get_footer( 'search' ); ?>
