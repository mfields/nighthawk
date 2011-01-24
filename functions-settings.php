<?php
/**
 * Settings
 *
 * This file contains all function and hook definitions
 * responsible for alling a user to interact with Ghostbird
 * via user interface.
 *
 * @package      Ghostbird
 * @subpackage   Functions
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        1.0
 */

/* Hook into WordPress. */
add_action( 'admin_menu', '_ghostbird_settings_page_link' );
add_action( 'admin_init', '_ghostbird_admin_init' );

/**
 * Defaults settings.
 *
 * This is where all settings for the Ghostbird theme are defined.
 *
 * @return    array     A one dimensional array of default settings.
 *
 * @access    public
 * @since     1.0
 */
function ghostbird_settings_default( $keys = false ) {
	return array(
		/* Boolean */
		'css_responsive'          => 1,
		'content_image_format'    => 1,
		'display_site_title'      => 1,
		'display_tagline'         => 1,
		'display_author'          => 1,
		'display_author_link'     => 0,
		'syntaxhighlighter_theme' => 1,
		);
}

/**
 * Clean settings.
 *
 * Takes a one dimensional array and makes sure that
 * each key is a recognized ghostbird setting and that
 * it's value is of the appropriate type.
 *
 * @param     array     Proposed settings.
 * @return    array     Filtered settings.
 *
 * @access    public
 * @since     1.0
 */
function ghostbird_clean_settings( $dirty ) {
	$clean = array();
	$keys = array_keys( ghostbird_settings_default() );
	foreach ( $keys as $key ) {
		$clean[$key] = ( isset( $dirty[$key] ) && ! empty( $dirty[$key] ) ) ? 1 : 0;
	}
	return $clean;
}

/**
 * Get settings.
 *
 * Return an array of all ghostbird settings.
 * This function will return valid results regardless
 * of whether the settings are stored in the WordPress
 * option table. Therefore, there is no need to store 
 * settings upon theme activation. All settings will
 * be cleaned and ready to use.
 *
 * Please see ghostbird_settings_default() fo a list
 * of all available settings.
 *
 * @return    array     User defined settings.
 *
 * @access    public
 * @since     1.0
 */
function ghostbird_get_settings() {
	$defaults = ghostbird_settings_default();
	$settings = (array) get_option( 'ghostbird' );
	$settings = array_merge( $defaults, $settings );
	return ghostbird_clean_settings( $settings );
}

/**
 * Create the admin menu link.
 *
 * @return    void
 *
 * @uses      _ghostbird_settings_page()
 * @access    private
 * @since     1.0
 */
function _ghostbird_settings_page_link() {
	add_theme_page( 'Ghostbird', 'Ghostbird', 'manage_options', 'ghostbird', '_ghostbird_settings_page' );
}

/**
 * Display the admin settings page.
 *
 * @return    void
 *
 * @access    private
 * @since     1.0
 */
function _ghostbird_settings_page() {
	print "\n" . '<div>';
	print "\n" . '<h2>' . __( 'Ghostbird Theme Settings', 'ghostbird' ) . '</h2>';
	print "\n" . '<form action="options.php" method="post">';

	settings_fields( 'ghostbird' );
	do_settings_sections( 'ghostbird' );

	print "\n" . '<input name="Submit" type="submit" value="' . esc_attr__( 'Save Changes', 'ghostbird' ) . '" />';
	print "\n" . '</form></div>';
}

/**
 * Configuration for the admin settings page.
 *
 * @return    void
 *
 * @access    private
 * @since     1.0
 */
function _ghostbird_admin_init() {
	register_setting( 'ghostbird', 'ghostbird', 'ghostbird_clean_settings' );
	add_settings_section( 'ghostbird_main', 'Ghostbird Settings Section', create_function( '', 'return true;' ), 'ghostbird' );
	add_settings_field( 'content',  __( 'Post Content', 'ghostbird' ),   '_ghostbird_control_content',  'ghostbird', 'ghostbird_main' );
	add_settings_field( 'elements', __( 'Elements', 'ghostbird' ),       '_ghostbird_control_elements', 'ghostbird', 'ghostbird_main' );
	add_settings_field( 'plugins',  __( 'Plugin Support', 'ghostbird' ), '_ghostbird_control_plugins',  'ghostbird', 'ghostbird_main' );
	global $ghostbird_settings;
	$ghostbird_settings = ghostbird_get_settings();
}

function _ghostbird_control_plugins() {
	_ghostbird_control_boolean( 'syntaxhighlighter_theme',  __( 'Enable custom theme for the SyntaxHighlighter Evolved plugin.', 'ghostbird' ) );
}

function _ghostbird_control_elements() {
	_ghostbird_control_boolean( 'display_site_title',  __( 'Display site title.', 'ghostbird' ) );
	_ghostbird_control_boolean( 'display_tagline',     __( 'Display tagline.', 'ghostbird' ) );
	_ghostbird_control_boolean( 'display_author',      __( 'Display post author box at the bottom of all posts.', 'ghostbird' ) );
	_ghostbird_control_boolean( 'display_author_link', __( 'Enable link to author archives after description.', 'ghostbird' ) );
}

function _ghostbird_control_boolean( $id, $label ) {
	global $ghostbird_settings;
	if ( isset( $ghostbird_settings[$id] ) ) {
		print "\n\n" . '<input' . ( ! empty( $ghostbird_settings[$id] ) ? ' checked="checked"' : '' ) . ' type="checkbox" id="ghostbird-' . $id . '" name="ghostbird[' . $id . ']" value="1" /> ';
		print "\n" . '<label for="ghostbird-' . $id . '">' . $label . '</label><br />';
	}
}

function _ghostbird_control_content() {
	_ghostbird_control_boolean( 'content_image_format', __( 'Automatically embed images from plain urls.', 'ghostbird' ) );
}

function ghostbird_display_controls() {
	print '<p>Main description of this section here.</p>';
}