<?php
/**
 * Blog timeline template
 *
 * This file is responsible for creating the blog view.
 * In a default installation of WordPress, this will be
 * the home page. In instances where users have designated
 * a page to be used as their "Blog Page", this template
 * will be used instead of page.php.
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

/*
 * Do we have sticky posts?
 * If so, we will store them in an array.
 */
$stickies = array();
while ( have_posts() ) {
	the_post();
	if ( ! is_sticky() ) {
		continue;
	}
	$stickies[] = $post;
}

/*
 * Display first sticky.
 *
 * The first sticky post will be displayed
 * in the intro div. We will pop it of the
 * sticky array before it is displayed.
 */
if ( ! empty( $stickies ) ) :
	$post = array_pop( $stickies );
	setup_postdata( $post );
	$image = get_the_post_thumbnail();
?>

	<header id="intro"<?php echo ( ! empty( $image ) ) ? ' class="has-image"' : ''; ?>>

		<?php the_title( '<h1 id="document-title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h1>' ); ?>

		<?php if ( ! empty( $image ) ) : ?>
			<div id="image"><a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo $image; ?></a></div>
		<?php endif; ?>

		<div id="summary">
			<?php the_content( __( 'Continue Reading', 'nighthawk' ) ); ?>
		</div>

		<?php wp_link_pages( array(
			'before' => '<div class="page-link contain">',
			'after'  => '</div>'
		) ); ?>

	</header>

<?php endif; ?>

<?php
/*
 * All other stickies.
 *
 * The remaining stickies, if any, will be
 * displayed in a custom list under the intro.
 */
?>

<?php if ( ! empty( $stickies ) ) : ?>
	<div id="featured-posts">
		<h2>Featured</h2>
		<ul id="intro-list"><?php
			foreach ( $stickies as $i => $post ) {
				setup_postdata( $post );
				the_title( "\n\t\t" . '<li><a href="' . esc_url( get_permalink() ) . '">', '</a></li>' );
			}
		?></ul>
	</div>
<?php endif; ?>

<?php wp_reset_postdata(); ?>

<div id="blog" class="contain">

<?php
while ( have_posts() ) {

	the_post();

	if ( is_sticky() ) {
		continue;
	}

	$context = get_post_type();

	$format = get_post_format();
	if ( ! empty ( $format ) )
		$context .= '-' . $format;

	get_template_part( 'entry', $context );
}
?>

</div><!--content-->

<?php get_template_part( 'nav-posts', 'front-page' ); ?>

<?php get_footer( 'post' ); ?>