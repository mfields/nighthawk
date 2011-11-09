<?php

$featured_image = get_the_post_thumbnail();
if ( ! empty( $featured_image ) ) {
	echo "\n" . '<div class="featured-image">';
	echo '<a href="' . esc_url( get_permalink() ) . '">' . $featured_image . '</a>';
	echo '</div>';
}

if ( ! is_singular() ) {
	the_title( "\n" . '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' );
}

echo "\n" . '<div class="entry-content">';
the_content( __( 'Continue Reading', 'nighthawk' ) );
echo "\n" . '</div><!--entry-content-->';

wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'nighthawk' ), 'after' => '</div>' ) );

echo '<div class="entry-taxonomy">';
nighthawk_entry_meta_taxonomy();
echo '</div><!--meta-->';