<?php
/**
 * Footer Template
 *
 * This file is responsible for generating the 
 * bottom-most html for all public-facing views.
 * It's content is generated via core WordPress
 * functions as well as custom actions defined
 * in functions.php.
 * 
 * Child themes are encouraged to work with the 
 * actions defined herein to add or remove data
 * to/from the top of the template. In the event
 * that the html needs to be modified, this 
 * template may be duplicated inside a child theme
 * and edited there.
 * 
 * This file should close any html tags opend in header.php.
 * 
 * @package      Ghostbird
 * @subpackage   Templates
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        1.0
 */
?>

</div><!-- #page -->

<div id="page-footer"><?php do_action( 'ghostbird_paged_navigation' ); ?></div>

<?php
$active = array();
$areas  = array( 'first', 'second', 'third' );
foreach ( $areas as $area ) {
	if ( is_active_sidebar( $area . '-footer-widget-area' ) ) {
		$active[] = $area;
	}
}
$count = count( $active );
if ( 0 < $count ) {
	print '<div id="widgets" class="count-' . $count . '" role="complementary">';
	foreach ( $active as $order => $area ) {
		print '<div id="' . esc_attr( $area ) . '" class="area area-' . ( 1 + $order ) . '">' . "\n";
		dynamic_sidebar( $area . '-footer-widget-area' );
		print '</div>' . "\n";
	}
	print '<div class="clear"></div>';
	print '</div><!-- #footer-widget-area -->';
}
?>

</div><!-- wrap -->

<?php wp_nav_menu( array(
	'container'      => 'div',
	'container_id'   => 'menu-bottom',
	'theme_location' => 'secondary',
	'depth'          => 1
	) ); ?>

<?php wp_footer(); ?>

</body>
</html>
