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
?>

<?php $columns = Nighthawk::get_table_columns(); ?>

<?php if ( have_posts() ) : ?>

	<table class="post-archive">
	<thead>
	<tr>

	<?php foreach ( $columns as $column ) : ?>
		<th scope="col"><?php echo esc_html( $column['label'] ); ?></th>
	<?php endforeach; ?>

	</tr>
	</thead>

	<tbody>
	<?php while ( have_posts() ) : the_post(); ?>
		<tr><?php
			foreach ( $columns as $column ) {
				call_user_func( $column['callback'], $column );
			}
		?></tr>
	<?php endwhile; ?>

	</tbody>
	</table>

<?php endif ?>