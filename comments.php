<?php
/**
 * Comments Template
 *
 * This file is responsible for displaying all 
 * comments for the global post object in single views.
 *
 * This template should leave no html tags open.
 *
 * If you find that modifications need to be made
 * to this file, it is suggested that you create a 
 * child theme, copy this file into the child theme's
 * directory and make your changes there.
 *
 * @package      Ghostbird
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        1.0
 */

if ( ! comments_open() || post_password_required() ) {
	return;
}

if ( have_comments() ) {
	
	/* Comment Heading. */
	$heading = sprintf( _n( '%1$s Comment', '%1$s Comments', get_comments_number(), 'ghostbird' ), number_format_i18n( get_comments_number() ) );
	$link    = '<a class="addendum" href="#respond">' . __( 'Leave a comment', 'ghostbird' ) . '</a>';
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

/* Display Comment Form. */
comment_form( array(
	'title_reply'          =>  __( 'Share your thoughts', 'ghostbird' ),
	'comment_notes_before' => '',
	'comment_notes_after'  => '',
	) );