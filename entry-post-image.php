<div id="post-<?php the_ID(); ?>" <?php post_class( 'contain' ); ?>>

	<?php the_content(); ?>

	<div class="entry-terms">
		<?php nighthawk_entry_meta_taxonomy(); ?>
	</div><!--entry-terms-->

</div>