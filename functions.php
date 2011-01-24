<?php
/**
 * Theme Setup
 *
 * @package      Ghostbird
 * @subpackage   Functions
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        1.0
 * 
 * BEFORE 1.0 RELEASE
 * @todo Style global tables.
 * @todo Revisit post formats.
 * @todo Test "big" tag across browsers - not sure about positioning here :)
 * @todo Completely test and rewrite all examples in docs or remove if feeling lazy ;)
 * @todo Excerpt filters for [...]
 * @todo HTML validataion.
 * @todo CSS validataion FWIW.
 * @todo Add meta to search archives intro box.
 * @todo Update and test style-editor.
 * @todo Lighter fonts in Widgets.
 * @todo Add Ghostbird settings page link to menu bar.
 * @todo Style all features of the Syntax Highler plugin.
 * @todo Add credit link in the footer.
 * @todo Add donation link in the settings page.
 * @todo Pretty-up the calendar widget.
 * @todo Alpha for yellow-dots.png. Change name to halftone-small.png.
 * @todo Ensure that all settings actually do something.
 * @todo Less saturation on #author-box bg color.
 * @todo Make dialog colors match new theme colors.
 * 
 * FUTURE RELEASE
 * @todo Add header widget. Intended for search form?
 * @todo Add detail images to gallery posts.
 * @todo "and" for taxonomy lists.
 * @todo Add support for taxonomy images plugin.
 * @todo Add support for wp_pagenavi plugin.
 * @todo Add custom template for Long Description plugin.
 *
 */

/**
 * Setup
 * 
 * If you would like to customize the theme setup you 
 * are encouraged to adopt the following process.
 * 
 * <ol>
 * <li>Create a child theme with a functions.php file.</li>
 * <li>Create a new function named mytheme_ghostbird_setup().</li>
 * <li>Hook this function into the 'after_setup_theme' action at or after 11.</li>
 * <li>call remove_filter(), remove_action() and/or remove_theme_support() as needed.</li>
 * </ol>
 * 
 * @return    void
 *
 * @access    private
 * @since     1.0
 */
function _ghostbird_setup() {
	
	require_once( 'functions-settings.php' );
	require_once( 'functions-private.php' );
	require_once( 'functions-public.php' );
	
	global $content_width;
	if ( ! isset( $content_width ) ) {
		$content_width = 608;
	}
	
	$settings = ghostbird_get_settings();
	
	load_theme_textdomain( 'ghostbird', get_template_directory() . '/languages' );
	
	add_theme_support( 'menus' );
	add_theme_support( 'post-formats', array( 'aside', 'audio', 'chat', 'gallery', 'image', 'status', 'video', 'quote' ) );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_custom_background();
	add_editor_style( 'style-editor.css' );

	/* A few extras for pages. */
	add_post_type_support( 'page', 'excerpt' );
	add_post_type_support( 'page', 'thumbnail' );

	/* Image sizes. */
	set_post_thumbnail_size( 150, 150, false );
	add_image_size( 'ghostbird_detail', 70, 70, true );

	/* Custom navigation menus. */
	register_nav_menus( array( 'primary' => 'Primary', 'secondary' => 'Secondary' ) );

	/* Hooking WordPress into WordPress */
	add_filter( 'get_the_author_description', 'wptexturize' );
	add_filter( 'get_the_author_description', 'convert_chars' );
	add_filter( 'get_the_author_description', 'wpautop' );
	
	/* Ghostbird hooking into WordPress. */
	add_action( 'widgets_init',               '_ghostbird_widgets_init' );
	add_action( 'wp_loaded',                  '_ghostbird_custom_image_header' );
	add_action( 'wp_print_scripts',           '_ghostbird_comment_reply_js' );
	add_action( 'body_class',                 '_ghostbird_body_class' );
	add_filter( 'wp_page_menu',               '_ghostbird_page_menu_wrap', 10, 2 );
	add_filter( 'post_class',                 '_ghostbird_post_class_hentry' );
	add_filter( 'post_class',                 '_ghostbird_post_class_featured' );
	add_filter( 'post_class',                 '_ghostbird_post_class_more' );
	add_filter( 'post_thumbnail_html',        '_ghostbird_featured_image_first_attachment' );
	add_filter( 'post_thumbnail_html',        '_ghostbird_featured_image_avatar' );
	add_filter( 'the_content',                '_ghostbird_filter_content_for_chat_format', 12 );
	add_filter( 'the_content',                '_ghostbird_filter_content_for_image_format', 0 );
	add_action( 'the_content',                '_ghostbird_related_images' );
	add_filter( 'embed_oembed_html',          '_ghostbird_oembed_dataparse', 10, 4 );
	add_filter( 'embed_googlevideo',          '_ghostbird_oembed_dataparse', 10, 2 );
	add_action( 'widget_title',               '_ghostbird_calendar_title', 10, 3 );
	
	/* Custom actions. */
	add_action( 'ghostbird_logo',             'ghostbird_logo', 10, 2 );
	add_action( 'ghostbird_paged_navigation', 'ghostbird_paged_nav', 10, 2 );
	
	/* Hooks controlable by settings panel. */
	if ( ! empty( $settings['display_site_title'] ) ) {
		add_action( 'ghostbird_site_title', 'ghostbird_site_title', 10, 2 );
	}
	if ( ! empty( $settings['display_author'] ) ) {
		add_action( 'ghostbird_author', '_ghostbird_author', 10, 3 );
	}
	if ( ! empty( $settings['display_author_link'] ) ) {
		add_filter( 'get_the_author_description', '_ghostbird_author_link', 9 );
	}
	if ( ! empty( $settings['display_tagline'] ) ) {
		add_action( 'ghostbird_tagline', 'ghostbird_tagline', 10, 2 );
	}
	
	/* Ghostbird hooking into SyntaxHighlighter Evolved plugin. */
	if ( ! empty( $settings['syntaxhighlighter_theme'] ) ) {
		wp_register_style( 'syntaxhighlighter-theme-ghostbird', get_template_directory_uri() . '/style-syntax-highlighter.css', array( 'syntaxhighlighter-core' ), '1' );
		add_filter( 'syntaxhighlighter_themes', '_ghostbird_syntaxhighlighter_theme' );
	}
}
add_action( 'after_setup_theme', '_ghostbird_setup' );