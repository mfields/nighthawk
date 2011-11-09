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
 * @since        Nighthawk 1.0
 */

if ( ! have_posts() ) {
	get_template_part( '404', 'post' );
}

get_header( 'post' );

?>

<header id="intro">
	<h1 id="document-title"><?php _e( 'Status Updates', 'nighthawk' ); ?></h1>
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

<?php get_template_part( 'nav-posts' ); ?>

<?php get_footer( 'post' ); ?>