<?php
/**
 * Default Archive Template
 *
 * @package      Nighthawk
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        Nighthawk 1.0
 */

if ( ! have_posts() ) {
	get_template_part( '404', 'archive' );
}

get_header( 'archive' );

?>

<header id="intro">
	<h1 id="document-title"><?php
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
			echo '<a href="' . esc_url( $url ) . '">' . esc_html( $title ) . '</a>';
		}
		else {
			echo esc_html( $title );
		}

	?></h1>
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

</div><!--content-->

<?php get_template_part( 'nav-posts', 'archive' ); ?>

<?php get_footer( 'archive' ); ?>