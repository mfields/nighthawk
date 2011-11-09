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
 * @since        Nighthawk 1.0
 */

if ( have_posts() ) {
	$columns = Nighthawk::columns();

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
		print "\n" . '<tr '; post_class(); print '>';
		foreach ( $columns as $column ) {
			call_user_func( $column['callback'], $column );
		}
		print "\n" . '</tr>';
	}

	print "\n" . '</tbody>';
	print "\n" . '</table>';
}