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
	$columns = Nighthawk::get_table_columns();

	echo "\n\n" . '<table class="post-archive">' . "\n";

	foreach ( $columns as $column ) {
		echo "\n" . '<col class="' . esc_attr( $column['class'] ) . '"></col>';
	}

	echo "\n\n" . '<thead>';
	echo "\n" . '<tr>';

	foreach ( $columns as $column ) {
		echo "\n\t" . '<th scope="col">' . esc_attr( $column['label'] ) . '</th>';
	}

	echo "\n" . '</tr>';
	echo "\n" . '</thead>';

	echo "\n\n" . '<tbody>';
	while ( have_posts() ) {
		the_post();
		echo "\n" . '<tr '; post_class(); echo '>';
		foreach ( $columns as $column ) {
			call_user_func( $column['callback'], $column );
		}
		echo "\n" . '</tr>';
	}

	echo "\n" . '</tbody>';
	echo "\n" . '</table>';
}