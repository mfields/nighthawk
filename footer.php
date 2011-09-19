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
 */
?>

	<?php do_action( 'nighthawk_append_to_page_div' ) ?>

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

<script>
/**
 * Special padding for introduction header.
 *
 * Add whitespace to the left of most elements in the
 * the introduction when the title starts with a letter
 * like T or Y. This only really works for one-liners,
 * but is a nice touch.
 */
( function() {
	var title, chars, first, summary, meta, className;

	title = document.getElementById( 'document-title' );

	first = '';
	if ( null != title ) {
		first = title.toString().charAt( 0 );
	}

	chars = [ 't', 'T', 'y', 'Y', 'i', '1' ];
	if ( chars.indexOf( first ) ) {
		elements = [
			'summary',
			'intro-meta',
			'byline'
		];
		for ( i = 0; i < elements.length; i++ ) {
			el = document.getElementById( elements[i] );
			if ( null == el )
				continue;
			el.className = ' nudge';
		}
	}

} )();
</script>

<?php wp_footer(); ?>

</body>
</html>