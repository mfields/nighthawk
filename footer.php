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
 * This file should close any html tags opened in header.php.
 *
 * @package      Nighthawk
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        Nighthawk 1.0
 */
?>

	<?php do_action( 'nighthawk_append_to_page_div' ); ?>

</div><!-- #page -->

<div id="dropdown-widgets">
	<?php dynamic_sidebar( 'dropdowns' ); ?>
</div>

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
	echo '<div id="footer-widgets" class="footer-widgets footer-widgets-count-' . $count . '" role="complementary">';
	foreach ( $active as $order => $area ) {
		echo '<div id="' . esc_attr( $area ) . '" class="area area-' . ( 1 + $order ) . '">' . "\n";
		dynamic_sidebar( $area . '-footer-widget-area' );
		echo '</div>' . "\n";
	}
	echo '<div class="clear"></div>';
	echo '</div><!-- #footer-widget-area -->';
}
?>

</div><!-- wrap -->

<?php
	wp_nav_menu( apply_filters( 'nighthawk_menu_args_secondary', array(
		'container'      => 'div',
		'container_id'   => 'menu-bottom',
		'menu_class'     => 'menu',
		'theme_location' => 'secondary',
		'depth'          => 1,
		'items_wrap'     => '<ul id="%1$s" class="%2$s" role="navigation">%3$s</ul>',
		'fallback_cb'    => '__return_false',
	) ) );
?>

<?php wp_footer(); ?>

</body>
</html>