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
 * @package      Nighthawk
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        Nighthawk 1.0
 */

/*
 * Return early if ...
 * Post is protected by a password.
 */
if ( post_password_required() ) {
	if ( have_comments() )
	echo '<p class="nopassword">' . sprintf( __( 'This %1$s is password protected. Enter the password to view any comments.', 'nighthawk' ), Nighthawk::post_label() ) . '</p>';
	return;
}

/*
 * Return early if ...
 * there are no comments and comments are not allowed.
 */
if ( ! have_comments() && ! comments_open() ) {
	return;
}

/*
 * Return early if ...
 * post_type does not support comments.
 */
$post_type = get_post_type();
if ( ! post_type_supports( $post_type, 'comments' ) ) {
	return;
}

if ( have_comments() ) {

	/* Comment heading. */
	$heading = sprintf( _n( '%1$s Comment', '%1$s Comments', get_comments_number(), 'nighthawk' ), number_format_i18n( get_comments_number() ) );

	/* Addendum */
	$addendum = '<a class="addendum" href="#respond">' . __( 'Leave a comment', 'nighthawk' ) . '</a>';
	if ( ! comments_open() ) {
		$addendum = '<span class="addendum">' . __( 'Comments are closed', 'nighthawk' ) . '</span>';
	}

	/* Print heading. */
	echo '<h2 id="comments">' . $heading . ' ' . $addendum . '</h2>';

	/* List the comments. */
	echo '<ol class="comment-list">';
	wp_list_comments( apply_filters( 'nighthawk_list_comments_args', array(
		'callback'     => '_nighthawk_comment_start',
		'end-callback' => '_nighthawk_comment_end',
		'max-depth'    => 1
		) ) );
	echo '</ol>';

	/* Paged navigation. */
	if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) {
		echo '<div class="navigation">';
		echo '<div class="nav-previous">'; previous_comments_link( __( '&laquo; Older Comments', 'nighthawk' ) ); echo '</div>';
		echo '<div class="nav-next">'; next_comments_link( __( 'Newer Comments &raquo;', 'nighthawk' ) ); echo '</div>';
		echo '</div>';
	}
}

/* Display comment form. */
comment_form( apply_filters( 'nighthawk_comment_form_args', array(
	'title_reply'          =>  __( 'Share your thoughts', 'nighthawk' ),
	'comment_notes_before' => '',
	'comment_notes_after'  => nighthawk_subscribe_to_comments_checkbox(),
	) ) );

nighthawk_subscribe_to_comments_manual_form( '<div class="content">', '</div>' );