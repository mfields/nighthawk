<?php
/**
 * Displays a Post.
 *
 * @package      Nighthawk
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        1.0
 */
?>

<div id="<?php nighthawk_entry_id(); ?>" <?php post_class(); ?>>

<?php
	do_action( 'nighthawk_entry_start' );

	switch ( get_post_format() ) {
		case 'aside' :
		case 'link' :
			print "\n" . '<div class="content">';
			the_content();
			print "\n" . '</div><!--content-->';
			/*
			print '<div class="meta">';
			nighthawk_entry_meta_date();
			print '</div><!--meta-->';
			*/
			break;
		case 'gallery' :
			nighthawk_featured_image( '<div class="featured-image">', '</div>' );
			print "\n" . '<div class="entry-content">';
			if ( is_single ) {
				the_content();
			}
			else {
				the_excerpt();
			}
			print "\n" . '</div><!--entry-content-->';
			print '<div class="' . esc_attr( nighthawk_entry_meta_classes() ) . '">';
			nighthawk_entry_meta_date();
			nighthawk_entry_meta_taxonomy();
			print '</div><!--meta-->';
			break;
		case 'status' :
			$avatar = get_avatar( get_the_author_meta( 'user_email' ), $size = '100' );
			if ( ! is_single() ) {
				$avatar = '<a href="' . esc_url( get_permalink() ) . '" class="image">' . $avatar . '</a>';
			}
			else {
				$avatar = '<span class="image">' . $avatar . '</span>';
			}
			print $avatar;
			print "\n" . '<div class="content">';
			the_content();
			print "\n" . '</div><!--content-->';
			print '<a href="' . esc_url( get_permalink() . '#respond' ) . '" class="comment-icon" title="' . esc_attr__( 'Add a comment', 'nighthawk' ) . '"><img src="' . get_template_directory_uri() . '/images/comment.png" alt="" /></a>';
			break;
		default :
			if ( ! is_singular() ) {
				the_title( "\n" . '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' );
			}

			nighthawk_featured_image( '<div class="featured-image">', '</div>' );

			print "\n" . '<div class="entry-content">';
			the_content( __( 'Continue Reading', 'nighthawk' ) );
			print "\n" . '</div><!--entry-content-->';

			wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'nighthawk' ), 'after' => '</div>' ) );

			print '<div class="' . esc_attr( nighthawk_entry_meta_classes() ) . '">';
			nighthawk_entry_meta_taxonomy();
			print '</div><!--meta-->';
			break;
	}

	do_action( 'nighthawk_entry_end' );
?>

</div><!--entry-->

<?php do_action( 'nighthawk_append_to_entry_template' ); ?>