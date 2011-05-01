<?php
/**
 * Alternative Header Template.
 *
 * Just like header.php only without the nav menu.
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

<div id="wrap">
	<div id="header">
		<?php do_action( 'nighthawk_logo',       '<div id="logo">',       '</div>' ); ?>
		<?php do_action( 'nighthawk_site_title', '<div id="site-title">', '</div>' ); ?>
		<?php do_action( 'nighthawk_tagline',    '<div id="tagline">',    '</div>' ); ?>
	</div>
	
	<div id="menu-top"></div>
	
	<div id="page" role="document">
