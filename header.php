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
 * @package      Ghostbird
 * @subpackage   Templates
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        1.0
 */
?><!DOCTYPE HTML>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<title>Michael Fields<?php wp_title(); ?></title>
<link rel="stylesheet" media="all" href="<?php print get_stylesheet_uri(); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<link rel="profile" href="http://gmpg.org/xfn/11">
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<div id="wrap">
	
	<div id="header">
		<?php do_action( 'ghostbird_logo',       '<div id="logo">',       '</div>' ); ?>
		<?php do_action( 'ghostbird_site_title', '<div id="site-title">', '</div>' ); ?>
		<?php do_action( 'ghostbird_tagline',    '<div id="tagline">',    '</div>' ); ?>
	</div>
	
	<?php wp_nav_menu( array(
		'container'      => 'div',
		'container_id'   => 'menu-top',
		'theme_location' => 'primary',
		'depth'          => 1
		) ); ?>

	<div id="page" role="document">