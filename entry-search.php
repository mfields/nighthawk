<?php
/**
 * Displays a Post in Search.
 *
 * @package      Nighthawk
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        1.0
 */
?>

<article id="<?php nighthawk_entry_id(); ?>" <?php post_class(); ?>>

<?php
	do_action( 'nighthawk_entry_start' );

	the_title( '<h2 class="entry-title heading">', '</h2>' );

	print "\n" . '<p class="content">';

	print '<time class="date" pubdate="pubdate" datetime="' . esc_attr( get_post_time( 'Y-m-d\TH:i:s\Z', true ) ) . '">' . esc_html( get_the_time( 'M j, Y' ) ) . '</time> &#8211; ';

	the_excerpt();

	print ' <a class="permalink" href="' . esc_url( get_permalink() ) . '">' . sprintf( esc_html__( 'View this %1$s', 'ghostbird' ), nighthawk_post_label_singular() ) . '</a>';

	print "\n" . '</p><!--content-->';

	do_action( 'nighthawk_entry_end' );
?>

</article><!--entry-->

<?php do_action( 'nighthawk_append_to_entry_template' ); ?>