<?php
if ( ! is_home() || is_paged() )
	return;

/*
 * Do we have sticky posts?
 * If so, we will store them in an array.
 */
$stickies = array();
while ( have_posts() ) {
	the_post();
	if ( is_sticky() )
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
 * Any remaining stickies will be displayed
 * in a custom list under the intro.
 */
?>

<?php if ( ! empty( $stickies ) ) : ?>
	<div id="featured-posts">
		<ul id="intro-list"><?php
			foreach ( $stickies as $i => $post ) {
				setup_postdata( $post );
				the_title( "\n\t\t" . '<li><a href="' . esc_url( get_permalink() ) . '">', '</a></li>' );
			}
		?></ul>
	</div>
<?php endif; ?>

<?php wp_reset_postdata(); ?>
