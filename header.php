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
 * @alter        1.1
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

<div id="wrap">

	<div id="page" class="contain" role="document">

	<div id="header">
		<?php #do_action( 'nighthawk_logo',       '<div id="logo">',       '</div>' ); ?>
		<?php do_action( 'nighthawk_site_title', '<div id="site-title">', '</div>' ); ?>
		<?php do_action( 'nighthawk_tagline',    '<div id="tagline">',    '</div>' ); ?>
	</div>

	<?php
		wp_nav_menu( apply_filters( 'nighthawk_menu_args_primary', array(
			'container'      => 'div',
			'container_id'   => 'menu-top',
			'menu_class'     => 'menu',
			'theme_location' => 'primary',
			'depth'          => 1,
			'items_wrap'     => '<ul id="%1$s" class="%2$s" role="navigation">%3$s</ul>',
			'fallback_cb'    => '_nighthawk_menu_dialog',
			) ) );
	?>