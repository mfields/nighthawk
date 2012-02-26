<?php
/**
 * Displays a Post in Search.
 *
 * For the duration of this loop the "wpautop" filter will be
 * removed from "the_excerpt". This allows the template to
 * construct it's own paragraph outside of the_excerpt().
 *
 * @package      Nighthawk
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        Nighthawk 1.0
 */
?>

<?php remove_filter( 'the_excerpt', 'wpautop' ); ?>

<?php while ( have_posts() ) : the_post(); ?>

	<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<?php the_title( "\t" . '<h2 class="entry-title heading">', '</h2>' ); ?>

		<p class="content">

			<time class="date" pubdate="pubdate" datetime="<?php echo esc_attr( get_post_time( 'Y-m-d\TH:i:s\Z', true ) ); ?>"><?php
				echo esc_html( get_the_time( 'M j, Y' ) );
			?></time> &#8211;

			<span class="post-excerpt"><?php the_excerpt(); ?></span>

			<a class="permalink" href="<?php echo esc_url( get_permalink() ); ?>"><?php
				printf( esc_html__( 'View this %1$s', 'nighthawk' ), Nighthawk::post_label() );
			?></a>

		</p>

	</div>

<?php endwhile; ?>

<?php add_filter( 'the_excerpt', 'wpautop' ); ?>
