<?php
/**
 * Post Table Loop.
 *
 * This template represents a full loop which is used in many
 * "archive" sections of this theme. This loop is currently
 * used in category.php, date.php, tag.php and taxonomy.php.
 *
 * It will display 30 posts per page in a table. Only titles and
 * a comments link will be shown. Navigation should be handled by
 * the template that calls this file.
 *
 * @package      Nighthawk
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        1.0
 */

if ( have_posts() ) {
	print '<table class="post-archive">';
	print '<col class="post-title"></col>';
	print '<col class="comment-count"></col>';
	print '<col class="comment-respond"></col>';
	
	print '<thead>';
	print '<tr>';
	print '<th scope="col">' . __( 'Post Title', 'nighthawk' ) . '</th>';
	print '<th scope="col">' . __( 'Comment Count', 'nighthawk' ) . '</th>';
	print '<th scope="col">' . __( 'Comment Link', 'nighthawk' ) . '</th>';
	print '</tr>';
	print '</thead>';
	
	print '<tbody>';
	while ( have_posts() ) {
		the_post();
		print '<tr>';

		$title = the_title( '', '', false );
		if ( empty( $title ) ) {
			$title = sprintf( esc_html__( 'untitled %1$s', 'nighthawk' ), nighthawk_post_label_singular() );
		}

		print '<td><a href="' . esc_url( get_permalink() ) . '">' . $title . '</a></td>';

		print '<td>';
		comments_popup_link( '0', '1', '%', 'comments-link', '%' );
		print '</td>';

		print '<td><a href="' . esc_url( get_permalink() . '#respond' ) . '" class="comment-icon" title="' . esc_attr__( 'Add a comment', 'nighthawk' ) . '"><img src="' . get_template_directory_uri() . '/images/comment.png" alt="" /></a></td>';

		print '</tr>';
	}
	print '</tbody>';
	print '</table>';
}