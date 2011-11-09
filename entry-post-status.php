<div id="<?php nighthawk_entry_id(); ?>" <?php post_class( 'contain' ); ?>>

<?php
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

	echo '<div class="entry-taxonomy">';
	nighthawk_entry_meta_taxonomy();
	echo '</div><!--meta-->';

	/*echo '<a href="' . esc_url( get_permalink() . '#respond' ) . '" class="comment-icon" title="' . esc_attr__( 'Add a comment', 'nighthawk' ) . '"><img src="' . get_template_directory_uri() . '/images/comment.png" alt="" /></a>';*/
?>

</div>