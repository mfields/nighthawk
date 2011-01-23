<?php
/**
 * Loop template
 *
 * This file is responsible for generating all code in 
 * the WordPress loop. Please note that, while it is 
 * very similar to the loop found in TwentyTen, there
 * is a major difference that cannot be ignored. This
 * template does not actually call the loop. It must
 * be included inside a loop in another file. This is
 * by design and will allow for this file to be used
 * anywhere a formatted post object is needed. For 
 * example, this file could be used as-is inside an 
 * Ajax callback function which uses a custom. It can
 * also be included inside custom loops created with 
 * get_posts(), get_pages() or get_children().
 *
 * @package      Ghostbird
 * @subpackage   Templates
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        1.0
 */
?>


<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<?php

do_action( 'ghostbird_hentry_start' );

/*
 * Featured image.
 */
$featured_image = get_the_post_thumbnail();
if ( ! empty( $featured_image ) ) {
	if ( ! is_singular() ) {
		$featured_image = '<a href="' . get_permalink() . '">' . $featured_image . '</a>';
	}
	print "\n" . '<div class="featured-image">' . $featured_image . '</div>';
}

/*
 * Title only for multiple views.
 * Will be displayed in single views via ghostbird_title() in an H1 element.
 */
if ( ! is_singular() ) {
	the_title( "\n" . '<h2 class="entry-title"><a href="' . get_permalink() . '">', '</a></h2>' );
}

print "\n" . '<div class="entry-content">';
if ( ( is_archive() || is_home() ) && ( 'page' == get_post_type() || 'gallery' == get_post_format() ) ) {
	the_excerpt();
}
else {
	the_content( 'more' );
}
print "\n" . '</div><!--post-content-->';

wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'ghostbird' ), 'after' => '</div>' ) );	
?>

<div class="entry-meta">
<?php ghostbird_entry_meta_date(); ?>
<?php ghostbird_entry_meta_taxonomy(); ?>
</div><!--meta-->

<?php do_action( 'ghostbird_hentry_end' ); ?>

</div><!--hentry-->
