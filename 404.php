<?php
/**
 * 404 Template.
 *
 * This file will be loaded by WordPress for all 404
 * views. It will also be displayed in instances where
 * a query produces no results.
 *
 * This file closes all html tags that it opens.
 *
 * This file terminates script execution.
 *
 * @package      Ghostbird
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        1.0
 * @alter        1.1
 */
?>

<?php get_header(); ?>

<div id="content" class="error404">

	<div id="intro">
		<h1><?php esc_html_e( 'Error 404', 'ghostbird' ); ?></h1>
		<div id="summary"><p><?php esc_html_e( 'Sorry, but the page you are looking for could not be found. It may have been moved or even deleted. Please try a search or use one of the links below.', 'ghostbird' ); ?></p></div>
		<?php get_search_form(); ?>
	</div>

	<div class="entry 404">
	<?php the_widget( 'WP_Widget_Tag_Cloud' ); ?>
	<?php the_widget( 'WP_Widget_Recent_Posts', array( 'number' => 10 ), array( 'widget_id' => null ) ); ?>
	</div>

</div>

<?php get_footer(); ?>
<?php exit; ?>