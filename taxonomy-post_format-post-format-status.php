<?php
/**
 * Status Update Template.
 *
 * Used when status updates are queried:
 * example.com/type/status or example.com/?post_format=status
 *
 * @package      Nighthawk
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        1.0
 */

if ( ! have_posts() ) {
	get_template_part( '404', 'post' );
}

get_header( 'post' );

?>

<header id="intro">
	<h1><?php _e( 'Status Updates', 'nighthawk' ); ?></h1>
	<?php nighthawk_summary_meta( '<div id="intro-meta">', '</div>' ) ?>
</header>

<div id="blog" class="contain">

<?php
while ( have_posts() ) {
	the_post();
	get_template_part( 'entry', 'post' );
}
?>

</div><!--content-->

<div id="page-footer"><?php do_action( 'nighthawk_paged_navigation' ); ?></div>

<?php get_footer( 'post' ); ?>