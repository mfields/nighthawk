<?php
/*
Template Name: Comments
*/

get_header( 'page' );
?>

<?php
while ( have_posts() ) {
	the_post();
?>

	<div id="intro">
		<?php the_title( '<h1>', '</h1>' ); ?>
		<p id="byline"><?php printf( esc_html__( 'By %1$s', 'nighthawk' ), get_the_author() ); ?></p>
		<?php if ( has_excerpt() ) { ?>
			<div id="summary"><?php the_excerpt() ?></div>
		<?php } ?>
		<?php nighthawk_summary_meta( '<div id="intro-meta">', '</div>' ); ?>
	</div>

	<div id="content" class="contain">

<?php
}

	rewind_posts();

	query_posts( array(
		'orderby'   => 'comment_count',
		'order'     => 'DESC',
		'post_type' => 'any'
		) );

	get_template_part( 'loop-post-table', 'most-commented' )
?>

	</div><!--content-->

<?php get_footer( 'page' ); ?>