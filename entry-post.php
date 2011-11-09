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
	do_action( 'nighthawk_entry_start' );

	switch ( get_post_format() ) {
		case 'status' :
			$avatar = get_avatar( get_the_author_meta( 'user_email' ), $size = '70' );
			if ( ! is_single() ) {
				$avatar = '<a href="' . esc_url( get_permalink() ) . '" class="image">' . $avatar . '</a>';
			}
			else {
				$avatar = '<span class="image">' . $avatar . '</span>';
			}
			echo $avatar;
			echo "\n" . '<div class="content">';
			the_content();
			echo "\n" . '</div><!--content-->';

			echo '<div class="entry-meta">';
			nighthawk_entry_meta_taxonomy();
			echo '</div><!--meta-->';

			/*echo '<a href="' . esc_url( get_permalink() . '#respond' ) . '" class="comment-icon" title="' . esc_attr__( 'Add a comment', 'nighthawk' ) . '"><img src="' . get_template_directory_uri() . '/images/comment.png" alt="" /></a>';*/
			break;
		case 'image' :
			the_content();
			the_title( '<h2 style="text-align:center">', '</h2>' );
			echo '<div class="entry-meta">';
			echo esc_html( get_the_date() );
			echo '</div><!--meta-->';
			break;
		default :
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

			echo '<div class="entry-meta">';
			nighthawk_entry_meta_taxonomy();
			echo '</div><!--meta-->';
			break;
	}

	do_action( 'nighthawk_entry_end' );
?>

</div><!--entry-->

<?php do_action( 'nighthawk_append_to_entry_template' ); ?>
