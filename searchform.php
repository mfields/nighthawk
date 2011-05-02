<?php
/**
 * Search Form.
 *
 * @package      Nighthawk
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        1.0
 */

$id_attr = nighthawk_search_id();
?>

<form class="bullet" role="search" method="get" action="<?php print get_option( 'siteurl' ); ?>">
	<label class="bullet-label" for="<?php print esc_attr( $id_attr ); ?>"><?php _e( 'Search', 'nighthawk' ); ?></label>
	<input class="bullet-term" id="<?php print esc_attr( $id_attr ); ?>" type="text" value="<?php print esc_attr( get_search_query( false ) ); ?>" name="s" />
	<input class="bullet-button" type="submit" value="Search" />
</form>
