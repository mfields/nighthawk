<div id="post-<?php the_ID(); ?>" <?php post_class( 'contain' ); ?>>

	<span class="image"><?php echo get_avatar( get_the_author_meta( 'user_email' ), $size = '70' ); ?></span>

	<div class="content">
		<?php the_content(); ?>
	</div><!--content-->

	<div class="entry-terms">
		<?php nighthawk_entry_meta_taxonomy(); ?>
	</div><!--entry-terms-->

</div>