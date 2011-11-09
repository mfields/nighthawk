<?php
/**
 * Search Form.
 *
 * @package      Nighthawk
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        Nighthawk 1.0
 */

$id_attr = nighthawk_search_id();
?>

<form class="bullet" role="search" method="get" action="<?php echo get_option( 'siteurl' ); ?>">
	<label class="bullet-label" for="<?php echo esc_attr( $id_attr ); ?>"><?php _e( 'Search', 'nighthawk' ); ?></label>
	<input class="bullet-term" id="<?php echo esc_attr( $id_attr ); ?>" type="text" value="<?php echo esc_attr( get_search_query( false ) ); ?>" name="s" />
	<input class="bullet-button" type="submit" value="Search" />
</form>
