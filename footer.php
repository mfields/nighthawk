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

<script>
( function( window, document, undefined ) {

	var maybeAddClass = function( e, cn ) {
		var c = String( e.getAttribute( 'class' ) );
		if ( 0 == c.length ) {
			e.className += cn;
		}
		else if ( -1 == c.indexOf( cn ) ) {
			e.className += ' ' + cn;
		}
		return e;
	}

	var imgs, i, w;
	imgs = document.getElementById( 'content' ).getElementsByTagName( 'img' );

	for ( i = 0; i < imgs.length; i++ ) {
		if ( 860 <= imgs[i].width ) {
			imgs[i] = maybeAddClass( imgs[i], 'larger-than-860' );
		}
		else if ( 607 <= imgs[i].width ) {
			imgs[i] = maybeAddClass( imgs[i], 'larger-than-607' );
		}
		else if ( 300 <= imgs[i].width ) {
			imgs[i] = maybeAddClass( imgs[i], 'larger-than-300' );
		}

		/* Remove inline styles from image's caption wrapper element. */
		if ( 'A' == imgs[i].parentNode.tagName ) {
			var classAttr = String( imgs[i].parentNode.parentNode.getAttribute( 'class' ) );
			if ( -1 != classAttr.indexOf( 'wp-caption' ) ) {
				imgs[i].parentNode.parentNode.removeAttribute( 'style' );
			}
		}

		/* Add a class to images that have been resized in the editor. */
		if ( 'naturalWidth' in imgs[i] && imgs[i].naturalWidth != imgs[i].getAttribute( 'width' ) ) {
			imgs[i] = maybeAddClass( imgs[i], 'forced-dimensions' );
		}
		if ( 'naturalHeight' in imgs[i] && imgs[i].naturalHeight != imgs[i].getAttribute( 'height' ) ) {
			imgs[i] = maybeAddClass( imgs[i], 'forced-dimensions' );
		}
	}
} ) ( this, document );
</script>

</body>
</html>