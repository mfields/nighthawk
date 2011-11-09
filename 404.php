<?php
/**
 * 404 Template.
 *
 * This file will be loaded by WordPress for all 404
 * views. It will also be displayed in instances where
 * a query produces no results.
 *
 * This file terminates script execution.
 *
 * @todo         Delete extra args from the_widget() once WP3.3 hits the shelf.
 *
 * @package      Nighthawk
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        Nighthawk 1.0
 */
?>

<?php get_header(); ?>

<header id="intro">
	<h1 id="document-title"><?php esc_html_e( 'Error 404', 'nighthawk' ); ?></h1>
	<div id="summary"><p><?php esc_html_e( 'Sorry, but the page you are looking for could not be found. It may have been moved or even deleted. Please try a search or use one of the links below.', 'nighthawk' ); ?></p></div>
	<?php get_search_form(); ?>
</header>

<div id="content" class="error404">
	<div class="entry 404">
		<?php the_widget( 'WP_Widget_Tag_Cloud' ); ?>
		<?php the_widget( 'WP_Widget_Recent_Posts', array( 'number' => 10 ), array( 'widget_id' => null ) ); ?>
	</div>
</div>

<?php get_footer(); ?>
<?php exit; ?>