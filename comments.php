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

/*
 * Return early if ...
 * Post is protected by a password.
 */
if ( post_password_required() ) {
	if ( have_comments() )
	print '<p class="nopassword">' . sprintf( __( 'This %1$s is password protected. Enter the password to view any comments.', 'ghostbird' ), ghostbird_post_label_singular() ) . '</p>';
	return;
}

/*
 * Return early if ...
 * there are no comments and comments are not allowed.
 */
if ( ! have_comments() && ! comments_open() ) {
	return;
}

if ( have_comments() ) {
	
	/* Comment heading. */
	$heading = sprintf( _n( '%1$s Comment', '%1$s Comments', get_comments_number(), 'ghostbird' ), number_format_i18n( get_comments_number() ) );
	
	/* Addendum */
	$addendum = '<a class="addendum" href="#respond">' . __( 'Leave a comment', 'ghostbird' ) . '</a>';
	if ( ! comments_open() ) {
		$addendum = '<span class="addendum">' . __( 'Comments are closed', 'ghostbird' ) . '</span>';
	}

	/* Print heading. */
	print '<h2 id="comments">' . $heading . ' ' . $addendum . '</h2>';

	/* List the comments. */
	print '<ol class="comment-list">';
	wp_list_comments( array(
		'callback'     => '_ghostbird_comment_start',
		'end-callback' => '_ghostbird_comment_end'
		) );
	print '</ol>';

	/* Paged navigation. */
	if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) {
		print '<div class="navigation">';
		print '<div class="nav-previous">'; previous_comments_link( __( '&laquo; Older Comments', 'ghostbird' ) ); print '</div>';
		print '<div class="nav-next">'; next_comments_link( __( 'Newer Comments &raquo;', 'ghostbird' ) ); print '</div>';
		print '</div>';
	}
}

/* Display comment form. */
comment_form( array(
	'title_reply'          =>  __( 'Share your thoughts', 'ghostbird' ),
	'comment_notes_before' => '',
	'comment_notes_after'  => ghostbird_subscribe_to_comments_checkbox(),
	) );

ghostbird_subscribe_to_comments_manual_form( '<div class="content">', '</div>' );