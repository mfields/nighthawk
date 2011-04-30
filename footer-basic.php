<?php
/**
 * Alternative Footer Template.
 *
 * Just like footer.php with the following things omitted:
 * 
 * Paged navigation.
 * Widgetized areas.
 * Secondary theme location for nav menu.
 *
 * This file should close any html tags opened in header-no-nav.php.
 * 
 * @package      Nighthawk
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        1.1
 */
?>

</div><!-- #page -->

</div><!-- wrap -->

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
