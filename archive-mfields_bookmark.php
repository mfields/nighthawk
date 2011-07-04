<?php
/**
 * Bookmark Archive.
 *
 * @todo         Dynamic post_type name.
 *
 * @package      Nighthawk
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        1.0
 */

if ( ! have_posts() ) {
	get_template_part( '404', 'archive-mfields_bookmark' );
}

get_header( 'archive-mfields_bookmark' );

?>

<div id="intro">
	<h1>Bookmarks</h1>
	<?php nighthawk_summary_meta( '<div id="intro-meta">', '</div>' ) ?>
</div>

<div id="content" class="contain">

<?php
	query_posts( wp_parse_args( $query_string, array( 'posts_per_page' => 30 ) ) );
	get_template_part( 'loop-bookmark-table' )
?>

</div><!--content-->

<div id="page-footer"><?php do_action( 'nighthawk_paged_navigation' ); ?></div>

<?php get_footer( 'archive-mfields_bookmark' ); ?>