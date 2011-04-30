<?php
/**
 * Search Template.
 *
 * This file closes all html tags that it opens.
 *
 * @package      Nighthawk
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        1.0
 * @alter        1.1
 */
?>

<?php get_header( 'search' ); ?>

<div id="content" class="contain">

<div id="intro">
	<h1><?php _e( 'Search Results', 'nighthawk' ) ?></h1>

	<?php get_search_form(); ?>

	<div id="intro-meta">
	<?php esc_html_e( sprintf( _n( '%1$s result was found for "%2$s".', '%1$s results were found for "%2$s".', (int) $wp_query->found_posts, 'nighthawk' ), number_format_i18n( (int) $wp_query->found_posts ), get_search_query() ) ); ?>
	</div>
</div>

<?php get_template_part( 'loop', 'search' ); ?>

</div><!--content-->

<div id="page-footer"><?php do_action( 'nighthawk_paged_navigation' ); ?></div>

<?php get_footer( 'search' ); ?>
