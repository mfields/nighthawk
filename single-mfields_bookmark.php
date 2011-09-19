<?php
/**
 * Single Bookmark Template.
 *
 * This template is loaded whenever a single
 * bookmark is being viewed. It is only used
 * if you have the Mfields Bookmarks plugin
 * installed.
 *
 * @package      Nighthawk
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        1.0
 */

if ( ! have_posts() ) {
	get_template_part( '404' );
}

$nighthawk_post_type = get_post_type();

get_header( $nighthawk_post_type );

?>

<?php
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
?>

<header id="intro">
<?php

	the_title( "\n" . '<h1>', '</h1>' );

	print "\n" . '<div id="dateline">';
	printf( __( 'Bookmarked on %1$s', 'nighthawk' ), '<time class="date" pubdate="pubdate" datetime="' . esc_attr( get_post_time( 'Y-m-d\TH:i:s\Z', true ) ) . '">' . esc_html( get_the_time( get_option( 'date_format' ) ) ) . '</time> by <span rel="author">' .  get_the_author() . '</span>' );
	print "\n" .'</div>';

?>
</header>

<div id="content" class="contain">
	<div id="<?php nighthawk_entry_id(); ?>" <?php post_class(); ?>>
<?php

	do_action( 'nighthawk_entry_start' );

	print "\n" . '<div class="entry-content">';
	the_content();
	print "\n" . '</div><!--entry-content-->';

	print '<div class="footer"><a href="' . esc_url( get_post_type_archive_link( $nighthawk_post_type ) ) . '">' . __( 'View all bookmarks', 'nighthawk' ) . '</a></div>';

	do_action( 'nighthawk_entry_end' );

	}
}
?>
	</div><!--entry-->
</div><!--content-->

<div id="page-footer">
	<?php previous_post_link( '<div class="nav-paged timeline-regress">%link</div>', __( 'Older', 'nighthawk' ) ); ?>
	<?php next_post_link( '<div class="nav-paged timeline-progress">%link</div>', __( 'Newer', 'nighthawk' ) ); ?>
</div>

<?php get_footer( $nighthawk_post_type ); ?>
