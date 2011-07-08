<?php
/**
 * Bookmark Table Loop.
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
 *
 * @todo         Move all functions to the bookmark plugin.
 * @todo         Maybe add permalink.
 */

function nighthawk_td_title( $column = array() ) {
	$title = the_title( '', '', false );
	if ( empty( $title ) ) {
		$title = sprintf( 'untitled %1$s', nighthawk_post_label_singular() );
	}

	$url = get_post_meta( get_the_ID(), '_mfields_bookmark_url', true );
	if ( ! empty( $url ) ) {
		$title_attr = 'Visit this document';
		$action = get_post_meta( get_the_ID(), '_mfields_bookmark_link_text', true );
		if ( ! empty( $action ) ) {
			$title_attr = ' title="' . esc_attr( $action ) . '"';
		}
		$title  = '<a href="' . esc_url( $url ) . '" rel="external"' . $title_attr . '>' . $title . '</a>';
	}

	print "\n\t" . '<td class="' . esc_attr( $column['class'] ) . '"><a href="' . esc_url( get_permalink() ) . '">' . $title . '</a></td>';
}


function nighthawk_td_comment_icon( $column = array() ) {
	print "\n\t" . '<td class="' . esc_attr( $column['class'] ) . '"><a href="' . esc_url( get_permalink() . '#respond' ) . '" class="comment-icon" title="' . esc_attr__( 'Add a comment', 'nighthawk' ) . '"><img src="' . get_template_directory_uri() . '/images/comment.png" alt="" /></a></td>';
}


function nighthawk_td_bookmark_source( $column = array() ) {
	$taxonomy = 'mfields_bookmark_source';
	$sources = get_the_terms( get_the_ID(), $taxonomy );

	if ( is_wp_error( $sources ) ) {
		return;
	}

	$link = '';
	if ( ! empty( $sources ) && is_array( $sources ) ) {
		$source = current( $sources );
		if ( isset( $source->name ) ) {
			$link = '<a href="' . esc_url( get_term_link( $source, $taxonomy ) ) . '">' . esc_html( $source->name ) . '</a>';
		}
	}

	print "\n\t" . '<td class="' . esc_attr( $column['class'] ) . '">' . $link . '</td>';
}

if ( have_posts() ) {

	$columns = array(
		array(
			'label'    => __( 'Post Title', 'nighthawk' ),
			'class'    => 'post-title',
			'callback' => 'nighthawk_td_title',
		),
		array(
			'label'    => __( 'Source', 'nighthawk' ),
			'class'    => 'bookmark-source',
			'callback' => 'nighthawk_td_bookmark_source',
		),
		array(
			'label'    => __( 'Comment Link', 'nighthawk' ),
			'class'    => 'comment-respond',
			'callback' => 'nighthawk_td_comment_icon',
		),
	);

	print "\n\n" . '<table class="post-archive">' . "\n";

	foreach ( $columns as $column ) {
		print "\n" . '<col class="' . esc_attr( $column['class'] ) . '"></col>';
	}

	print "\n\n" . '<thead>';
	print "\n" . '<tr>';

	foreach ( $columns as $column ) {
		print "\n\t" . '<th scope="col">' . esc_attr( $column['label'] ) . '</th>';
	}

	print "\n" . '</tr>';
	print "\n" . '</thead>';

	print "\n\n" . '<tbody>';
	while ( have_posts() ) {
		the_post();
		print "\n" . '<tr>';
		foreach ( $columns as $column ) {
			call_user_func( $column['callback'], $column );
		}
		print "\n" . '</tr>';
	}

	print "\n" . '</tbody>';
	print "\n" . '</table>';
}