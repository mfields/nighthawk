<?php
/**
 * Default Archive Template
 *
 * @package      Nighthawk
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        1.0
 */

if ( ! have_posts() ) {
	get_template_part( '404', $taxonomy );
}

get_header( $taxonomy );

?>

<div id="intro">
	<h1><?php 
		$url = '';
		$title = __( 'Archives', 'nighthawk' );
		if ( is_post_type_archive() ) {
			$title = post_type_archive_title( '', false );
			global $wp_query;
			$post_type = $wp_query->get_queried_object();
			if ( isset( $post_type->name ) && is_paged() ) {
				$url = get_post_type_archive_link( $post_type->name );
			}
		}
		
		if ( ! empty( $url ) ) {
			print '<a href="' . esc_url( $url ) . '">' . esc_html( $title ) . '</a>';
		}
		else {
			print esc_html( $title );
		}
		
	?></h1>
	<?php nighthawk_summary_meta( '<div id="intro-meta">', '</div>' ) ?>
</div>

<div id="content" class="contain">

<?php
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		get_template_part( 'entry', get_post_type() );
	}
}
?>

</div><!--content-->

<div id="page-footer"><?php do_action( 'nighthawk_paged_navigation' ); ?></div>

<?php get_footer( $taxonomy ); ?>