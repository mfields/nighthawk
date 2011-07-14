<?php
/**
 * Header Template
 *
 * This file is responsible for generating the 
 * top-most html for all public-facing views.
 * It's content is generated via core WordPress
 * functions as well as custom actions defined
 * in functions.php.
 * 
 * Child themes are encouraged to work with the 
 * actions defined herein to add or remove data
 * to/from the top of the template. In the event
 * that the html needs to be modified, this 
 * template may be duplicated inside a child theme
 * and edited there. Please note the this file
 * leaves 2 html div tags open. Both of these tags
 * are properly closed in footer.php by default.
 * 
 * @package      Nighthawk
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        1.0
 */
?><!DOCTYPE HTML>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<title><?php bloginfo( 'blogname' ); ?><?php wp_title(); ?></title>
<link rel="stylesheet" media="all" href="<?php print get_stylesheet_uri(); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<link rel="profile" href="http://gmpg.org/xfn/11">
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php do_action( 'nighthawk_start' ); ?>

<div id="wrap">

	<?php
		$header_image = get_header_image();
		if ( ! empty( $header_image ) ) {
			get_template_part( 'top', 'with-image' );
		}
	?>

	<div id="page" class="contain" role="document">

	<div id="header">
		<?php
			if ( empty( $header_image ) ) {
				get_template_part( 'top', 'no-image' );
			}
			else {
				print "\n" . '<div id="header-image">';
				printf(
					'<img src="%1$s" width="%2$s" height="%3$s" alt="%4$s">',
					esc_url( $header_image ),
					esc_attr( HEADER_IMAGE_WIDTH ),
					esc_attr( HEADER_IMAGE_HEIGHT ),
					esc_attr( get_bloginfo( 'blogname' ) )
				);
				print '</div>';
			}
		?>
	</div>
