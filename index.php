<?php
/**
 * Default Template.
 *
 * When all else fails, this template is loaded.
 * 
 * @package      Nighthawk
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        1.0
 */

if ( ! have_posts() ) {
	get_template_part( '404' );
}

get_header();

?>

<header id="intro">
	<?php nighthawk_summary_meta( '<div id="intro-meta">', '</div>' ); ?>
</header>

<div id="content" class="contain">

<?php
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		get_template_part( 'entry', get_post_type() );
	}
}
?>

<?php comments_template( '', true ); ?>

</div><!--content-->

<div id="page-footer"><?php do_action( 'nighthawk_paged_navigation' ); ?></div>

<?php get_footer(); ?>