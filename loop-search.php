<?php
/**
 * Search Loop
 *
 * Displays post of all post_types and formats
 * in the search template (search.php).
 *
 * @package      Nighthawk
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        1.1
 */
?>

<?php do_action( 'nighthawk_loop_search_start' ); ?>

<?php if ( have_posts() ) { ?>

	<div id="search-results">
	
	<?php while ( have_posts() ) { the_post(); ?>
		
		<div id="<?php print esc_attr( nighthawk_post_label_singular() . '-' . get_the_ID() ); ?>" <?php post_class(); ?>>
		
		<?php do_action( 'nighthawk_entry_start' ); ?>
		
		<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' ); ?>
		
		<div class="entry-content">
		
		<p><span class="entry-date"><?php print esc_html( get_the_time( 'M j, Y' ) ); ?></span> &#8211; <?php the_excerpt(); ?>
		 <a tabindex="-1" class="permalink" href="<?php print esc_url( get_permalink() ); ?>"> <?php printf( esc_html__( 'View this %1$s', 'nighthawk' ), nighthawk_post_label_singular() ); ?></a></p>
		
		</div><!--entry-content-->
		
		<?php do_action( 'nighthawk_entry_end' ); ?>
		
		</div><!--entry-->
	
	<?php } ?>
	
	</div><!-- search-results -->

<?php } ?>

<?php do_action( 'nighthawk_loop_search_end' ); ?>