<?php
/**
 * Author Biography
 *
 * Template part that displays a short bio
 * of the post's author. This template will
 * only display a bio if one has been added
 * via the "Users" administration panel.
 *
 * This template will only work for post_types
 * that support authors. 
 *
 * @package      Nighthawk
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        1.0
 */

$description = get_the_author_meta( 'description' );
if ( empty( $description ) ) {
	return;
}

if ( ! post_type_supports( get_post_type(), 'author' ) ) {
	return;
}
?>

<div id="author-box" class="contain has-avatar">
	<div class="author-avatar"><?php print get_avatar( get_the_author_meta( 'user_email' ), 60 ); ?></div>
	<h2 class="author-name"><?php printf( esc_attr__( 'About %s', 'nighthawk' ), get_the_author() ); ?></h2>
	<div class="author-bio"><?php print $description; ?></div>
</div><!--author-box-->