<?php
/**
 * Template for displaying post entries.
 *
 * @package      Nighthawk
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        Nighthawk 1.0
 */
?>

<div id="<?php nighthawk_entry_id(); ?>" <?php post_class( 'contain' ); ?>>

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

?>

</div><!--entry-->

<?php do_action( 'nighthawk_append_to_entry_template' ); ?>
