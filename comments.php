<?php
/**
 * Comments Template
 *
 * This file is responsible for displaying all 
 * comments for a post object in single views.
 *
 * No html tags are left open.
 *
 * If you find that modifications need to be made
 * to this file, it is suggested that you create a 
 * child theme, copy this file into the child theme's
 * directory and make your changes there.
 *
 * @package      Ghostbird
 * @subpackage   Templates
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        1.0
 * 
 * @todo Fully test comment widget javascript.
 * @todo Conditionally add javascipt.
 * @todo Move javascipt into it's own file and enqueue via private function.
 */

if ( ! comments_open() || post_password_required() ) {
	return;
}

if ( have_comments() ) {
	
	/* Comment Heading. */
	$heading = sprintf( _n( '%1$s Comment', '%1$s Comments', get_comments_number(), 'ghostbird' ), number_format_i18n( get_comments_number() ) );
	$link    = '<a class="heading-action" href="#respond">' . __( 'Leave a comment', 'ghostbird' ) . '</a>';
	print '<h2 id="comments">' . $heading . ' ' . $link . '</h2>';
	
	/* List the Comments. */
	print '<ol class="comment-list">';
	wp_list_comments( array(
		'callback'     => '_ghostbird_comment_start',
		'end-callback' => '_ghostbird_comment_end'
		) );
	print '</ol>';
	
	/* Paged Navigation. */
	if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) {
		print '<div class="navigation">';
		print '<div class="nav-previous">'; previous_comments_link( __( '&laquo; Older Comments', 'ghostbird' ) ); print '</div>';
		print '<div class="nav-next">'; next_comments_link( __( 'Newer Comments &raquo;', 'ghostbird' ) ); print '</div>';
		print '</div>';
	}
}


/* Maybe prepend discussion guidelines widget to comment textarea. */
$comment_field = '<label for="comment">' . _x( 'Comment', 'noun' ) . '</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea>';
$guidlines = _ghostbird_discussion_guidelines();
if ( ! empty( $guidlines ) ) {
	$comment_field = $guidlines . $comment_field;
}
$comment_field = '<div class="comment-form-comment">' . $comment_field . '</div>';


/* Display Comment Form. */
comment_form( array(
	'title_reply'          =>  __( 'Share your thoughts', 'ghostbird' ),
	'comment_field'        => $comment_field,
	'comment_notes_before' => '',
	'comment_notes_after'  => '',
	) );
?>

<script>
var toggle  = document.getElementById( 'discussion-guidelines-toggle' );
var widget  = document.getElementById( 'discussion-guidelines-widgets' );
var comment = document.getElementById( 'comment' );
if ( 'object' == typeof toggle && 'object' == typeof widget ) {
	widget.style.display = 'none';
	toggle.onmouseover = function() {
		toggle.style.cursor = 'pointer';
	};
	toggle.onclick = function() {
		widget.style.display = ( 'none' == widget.style.display ) ? 'block' : 'none';
	};
	comment.onfocus = function() {
		widget.style.display = 'block';
	};
}
</script>