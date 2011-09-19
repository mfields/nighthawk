<?php
/**
 * Taxonomy Template
 *
 * This file closes all html tags that it opens.
 *
 * @package      Nighthawk
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        1.0
 *
 * @todo         Add edit link.
 * @todo         Add website link.
 */

if ( ! have_posts() ) {
	get_template_part( '404', 'author' );
}

get_header( 'author' );

the_post();

print '<header id="intro" class="vcard">';

print '<h1 id="document-title" class="url fn n">' . esc_html( get_the_author() ) . '</h1>';

$bio = get_the_author_meta( 'description' );
if ( ! empty( $bio ) ) {
	print '<div id="summary">' . $bio . '</div>';
}

print '</header>';

rewind_posts();

?>

<div id="content" class="contain">

<?php
	query_posts( wp_parse_args( $query_string, array( 'posts_per_page' => 30 ) ) );
	get_template_part( 'loop-table', 'author' );
?>

</div><!--content-->

<?php get_template_part( 'nav-posts', 'author' ); ?>

<?php get_footer( 'author' ); ?>
