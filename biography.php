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

if ( ! post_type_supports( get_post_type(), 'author' ) ) {
	return;
}

$description = get_the_author_meta( 'description' );
if ( empty( $description ) ) {
	return;
}
?>

<h2><?php _e( 'About the Author', 'nighthawk' ) ?></h2>
<div class="biography box has-avatar contain">
	<div class="avatar"><?php print get_avatar( get_the_author_meta( 'user_email' ), 75 ); ?></div>
	<span class="heading fn author"><?php print esc_html( get_the_author() ); ?></span>
	<span class="meta"><a href="<?php print esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php printf( esc_html__( 'View all entries by %1$s', 'nighthawk' ), get_the_author() ); ?></a></span>
	<div class="content"><?php print $description; ?></div>
</div><!--author-box-->
