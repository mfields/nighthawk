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
 * @since        1.0
 * @alter        1.1
 */
?>

</div><!-- #page -->

<div id="page-footer"><?php do_action( 'nighthawk_paged_navigation' ); ?></div>

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

<?php
	wp_nav_menu( apply_filters( 'nighthawk_menu_args_secondary', array(
		'container'      => 'div',
		'container_id'   => 'menu-bottom',
		'menu_class'     => 'menu',
		'theme_location' => 'secondary',
		'depth'          => 1,
		'items_wrap'     => '<ul id="%1$s" class="%2$s" role="navigation">%3$s</ul>',
		'fallback_cb'    => '_nighthawk_menu_dialog',
		) ) );
?>

<?php wp_footer(); ?>
<!--[if IE 8]>
<script>
var imgs, i, w;
var imgs = document.getElementsByTagName( 'img' );
for( i = 0; i < imgs.length; i++ ) {
	w = imgs[i].getAttribute( 'width' );
	if ( 615 < w ) {
		imgs[i].removeAttribute( 'width' );
		imgs[i].removeAttribute( 'height' );
	}
}
</script>
<![endif]-->
</body>
</html>
