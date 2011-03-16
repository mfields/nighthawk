<?php get_header(); ?>

<div id="content">

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