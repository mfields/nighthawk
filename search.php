<?php
/**
 * Search Template.
 *
 * This file closes all html tags that it opens.
 *
 * @package      Ghostbird
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        1.0
 * @alter        1.1
 */

get_header( 'search' );

?>

<div id="content">

<div id="intro"><?php
	ghostbird_title( '<h1>', '</h1>' );
	ghostbird_byline( '<p id="byline">', '</p>' );
	ghostbird_summary( '<div id="summary">', '</div>' );

	get_search_form();

	$count = 0;
	if ( isset( $wp_query->found_posts ) ) {
		$count = (int) $wp_query->found_posts;
	}

	print "\n" . '<div id="intro-meta">';
	print esc_html( sprintf( _n( '%1$s result was found for "%2$s".', '%1$s results were found for "%2$s".', $count, 'ghostbird' ), number_format_i18n( $count ), get_search_query() ) );
	print "\n" . '</div>';
?>

</div>

<?php get_template_part( 'loop', 'search' ); ?>

</div><!--content-->

<div class="clear"></div>

<?php get_footer( 'search' ); ?>
