<article id="post-<?php the_ID(); ?>" <?php post_class( 'contain' ); ?>>

	<?php the_content(); ?>

	<footer class="entry-footer">
		<?php nighthawk_entry_meta_taxonomy(); ?>
	</footer>

</article>