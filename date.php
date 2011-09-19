<?php
/**
 * Default Archive Template
 *
 * @package      Nighthawk
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        1.0
 */

if ( ! have_posts() ) {
	get_template_part( '404', 'archive-date' );
}

get_header( 'archive-date' );

?>

<header id="intro">
	<h1><?php _e( 'Archives', 'nighthawk' ); ?></h1>

	<?php

	$total = Nighthawk::post_total();

	if ( is_year() ) {
		$meta = sprintf( _n( 'One entry was published in %2$s.', '%1$s entries were published in %2$s.', $total, 'nighthawk' ), number_format_i18n( $total ), get_the_date( 'Y' ) );
	}

	else if ( is_day() ) {
		$meta = sprintf( _n( 'One entry was published on %2$s.', '%1$s entries were published on %2$s.', $total, 'nighthawk' ), number_format_i18n( $total ), get_the_date() );
	}

	else if ( is_month() ) {
		$year = get_query_var( 'year' );
		if ( empty( $year ) ) {
			$meta = sprintf( _n( 'One entry was published in the month of %2$s.', '%1$s entries were published in the month of %2$s.', $total, 'nighthawk' ), number_format_i18n( $total ), get_the_date( 'F' ) );
		}
		else {
			$meta = sprintf( _n( 'One entry was published in %2$s of %3$s.', '%1$s entries were published in %2$s of %3$s.', $total, 'nighthawk' ), number_format_i18n( $total ), get_the_date( 'F' ), get_the_date( 'Y' ) );
		}
	}
?>

	<?php print '<div id="intro-meta">' . $meta . '</div>'; ?>
</header>

<div id="content" class="contain">

<?php
	query_posts( wp_parse_args( $query_string, array( 'posts_per_page' => 30 ) ) );
	get_template_part( 'loop-table' );
?>

</div><!--content-->

<?php get_template_part( 'nav-posts', 'date' ); ?>

<?php get_footer( 'archive-date' ); ?>