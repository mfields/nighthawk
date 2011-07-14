<?php
/**
 * Functions
 *
 * This file defines three specific types of functions.
 * Please see the @type tag in each function's docblock
 * to determine how the function should be used.
 *
 * 1. Template Tags
 *
 * Any function defined in this this section may be used
 * freely in appropriate template files. Please see
 * each function's documentation for intended usage.
 *
 * 2. Core Callbacks
 *
 * Functions of this type are intended to be used as callbacks
 * for WordPress core functions and template tags. They are not
 * to be used on their own.
 *
 * 3. Private Functions.
 *
 * The functions defined below are deemed to be private
 * meaning that they should not be used in any template file for
 * any reason. These functions may or may not be presnt in
 * future releases of the Nighthawk theme. If you feel that you
 * absolutely need to use one of them it is suggested that you
 * copy the full function into your child theme's functions.php file
 * and rename it. This will ensure that it always exists in your
 * installation regardless of how Nighthawk changes.
 *
 * @package      Nighthawk
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        1.0
 */

define( 'NIGHTHAWK_VERSION', '0.2DEV' );

/**
 * Theme Setup
 *
 * If you would like to customize the theme setup you
 * are encouraged to adopt the following process.
 *
 * <ol>
 * <li>Create a child theme with a functions.php file.</li>
 * <li>Create a new function named mytheme_nighthawk_setup().</li>
 * <li>Hook this function into the 'after_setup_theme' action at or after 11.</li>
 * <li>call remove_filter(), remove_action() and/or remove_theme_support() as needed.</li>
 * </ol>
 *
 * @return    void
 *
 * @access    private
 * @since     1.0
 */
function _nighthawk_setup() {

	global $content_width;
	if ( ! isset( $content_width ) ) {
		$content_width = 700;
	}

	load_theme_textdomain( 'nighthawk', get_template_directory() . '/languages' );
	add_action( 'template_redirect', 'nighthawk_post_labels_init' );

	add_theme_support( 'menus' );
	add_theme_support( 'post-formats', array( 'status' ) );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_custom_background();
	add_editor_style( 'style-editor.css' );

	/* A few extras for pages. */
	add_post_type_support( 'page', 'excerpt' );
	add_post_type_support( 'page', 'thumbnail' );

	/* Image sizes. */
	set_post_thumbnail_size( 150, 150, false );
	add_image_size( 'nighthawk_detail', 70, 70, true );

	/* Navigation menus. */
	register_nav_menus( array( 'primary' => 'Primary', 'secondary' => 'Secondary' ) );

	/* WordPress Hooking into WordPress */
	add_filter( 'get_the_author_description', 'wptexturize' );
	add_filter( 'get_the_author_description', 'convert_chars' );
	add_filter( 'get_the_author_description', 'wpautop' );

	/* WordPress Core. */
	add_filter( 'edit_post_link',    '_nighthawk_edit_post_link', 9, 2 );
	add_filter( 'embed_oembed_html', '_nighthawk_oembed_dataparse', 10, 4 );
	add_filter( 'embed_googlevideo', '_nighthawk_oembed_dataparse', 10, 2 );
	add_filter( 'excerpt_more',      '_nighthawk_excerpt_more_auto' );
	add_filter( 'get_the_excerpt',   '_nighthawk_excerpt_more_custom' );
	add_filter( 'post_class',        '_nighthawk_post_class' );
	add_action( 'the_content',       '_nighthawk_related_images' );
	add_filter( 'the_password_form', '_nighthawk_password_form' );
	add_action( 'widget_title',      '_nighthawk_calendar_widget_title', 10, 3 );
	add_action( 'widgets_init',      '_nighthawk_widgets_init' );
	add_action( 'wp_loaded',         '_nighthawk_custom_image_header' );
	add_action( 'wp_print_scripts',  '_nighthawk_comment_reply_js' );
	add_action( 'wp_print_styles',   '_nighthawk_google_fonts' );

	/* Ajax Callbacks */
	add_action( 'wp_ajax_nighthawk_hide_message_nav_menu', '_nighthawk_ajax_hide_message_nav_menu' );

	/* Custom hooks. */
	add_action( 'nighthawk_paged_navigation', 'nighthawk_paged_nav', 10, 2 );

	/* Theme modifications. */
	add_action( 'custom_header_options', '_nighthawk_settings_custom_header_text_controls' );
	add_action( 'admin_head-appearance_page_custom-header', '_nighthawk_process_custom_header_settings', 51 );

	/* SyntaxHighlighter Evolved plugin. */
	wp_register_style( 'syntaxhighlighter-theme-nighthawk', get_template_directory_uri() . '/style-syntax-highlighter.css', array( 'syntaxhighlighter-core' ), '1' );
	add_filter( 'syntaxhighlighter_themes', '_nighthawk_syntaxhighlighter_theme' );
}
add_action( 'after_setup_theme', '_nighthawk_setup' );

/**
 * Summary Meta.
 *
 * Print meta information pertaining to the current view.
 *
 * @param     string         Text to prepend to the summary meta.
 * @param     string         Text to append to the summary meta.
 * @param     bool           True to print, false to return a string. Defaults to true.
 * @return    void/string
 *
 * @access    public
 * @since     1.0
 */
function nighthawk_summary_meta( $before = '', $after = '', $print = true ) {
	global $wp_query;

	$total = 0;
	if ( isset( $wp_query->found_posts ) ) {
		$total = (int) $wp_query->found_posts;
	}

	$sentence = '';
	$feed_url = '';

	if ( is_home() || is_post_type_archive() ) {
		global $posts;
		$post_type = $wp_query->get_queried_object();
		if ( empty( $post_type ) ) {
			$post_type = get_post_type_object( 'post' );
		}
		if ( isset( $post_type->name ) && isset( $post_type->label ) && isset( $post_type->labels->singular_name ) ) {
			$feed_url   = get_post_type_archive_feed_link( $post_type->name );
			$feed_title = sprintf( __( 'Get updated whenever new %1$s are published.', 'nighthawk' ), $post_type->label );
			$sentence   = sprintf( _n( 'Only one %3$s found in this archive.', 'There are %1$s %2$s in this archive.', $total, 'nighthawk' ), number_format_i18n( $total ), nighthawk_post_label_plural(), nighthawk_post_label_singular() );
			$sentence   = apply_filters( 'nighthawk_summary_meta_post_type_archive', $sentence, $post_type );
			$sentence   = apply_filters( "nighthawk_summary_meta_{$post_type->name}_archive", $sentence, $post_type );
		}
	}
	else if ( is_attachment() ) {
		$parent = false;
		$id = get_the_ID();
		$attachment = get_post( $id );
		if ( isset( $attachment->post_parent ) && 0 != $attachment->post_parent ) {
			$parent = get_post( $attachment->post_parent );
		}
		if ( isset( $parent->ID ) && isset( $parent->post_title ) ) {
			$parent_link = '<a href="' . get_permalink( $parent->ID ) . '">' . apply_filters( 'the_title', $parent->post_title ) . '</a>';
			$label = nighthawk_post_label_singular();
			$sentence = sprintf( __( 'This %1$s is attached to %2$s.', 'nighthawk' ), $label, $parent_link );
			$sentence = apply_filters( 'nighthawk_summary_file', $sentence );
			if ( 'gallery' == get_post_format( $parent->ID ) ) {
				$sentence = sprintf( __( 'This %1$s is part of the gallery titled %2$s.', 'nighthawk' ), $label, $parent_link );
				$sentence = apply_filters( 'nighthawk_summary_image_in_gallery', $sentence );
			}
		}
	}
	else if ( is_tax() ) {
		$term = $wp_query->get_queried_object();
		if ( isset( $term->term_id ) && isset( $term->name ) && isset( $term->taxonomy ) ) {
			$taxonomy = get_taxonomy( $term->taxonomy );
			$taxonomy_name = __( 'taxonomy', 'nighthawk' );
			if ( isset( $taxonomy->labels->singular_name ) ) {
				$taxonomy_name = $taxonomy->labels->singular_name;
			}

			switch( $term->taxonomy ) {
				case 'post_format' :
					$feed_title = sprintf( __( 'Get updated whenever a new %1$s is published.', 'nighthawk' ), nighthawk_post_label_singular() );
					$sentence = sprintf( _n( 'This site contains one %2$s.', 'This site contains %1$s %3$s.', $total, 'nighthawk' ), number_format_i18n( $total ), nighthawk_post_label_singular(), nighthawk_post_label_plural() );
					break;
				default :
					$feed_title = sprintf( __( 'Subscribe to this %1$s', 'nighthawk' ), $taxonomy_name );
					$sentence = sprintf( _n( 'One entry is associated with the term %2$s.', '%1$s entries are associated with the term %2$s.', $total, 'nighthawk' ), number_format_i18n( $total ), $term->name );
					break;
			}
			$feed_url = get_term_feed_link( $term->term_id, $term->taxonomy );
		}
	}
	if ( ! empty( $feed_url ) ) {
		$sentence.= ' <span class="subscribe"><a href="' . esc_url( $feed_url ) . '" title="' . esc_attr( $feed_title ) . '">' . esc_html__( 'Subscribe', 'nighthawk' ) . '</a></span>';
	}
	if ( ! empty( $sentence ) ) {
		$sentence = "\n" . $before . $sentence . $after;
		if ( $print ) {
			print $sentence;
		}
		else {
			return $sentence;
		}
	}
}

/**
 * Continue Reading Link.
 *
 * Get a link to the global post's single view
 * with the phraze "Continue Reading".
 *
 * @return    string         Permalink with the text "Continue Reading".
 *
 * @access    public
 * @since     1.0
 */
function nighthawk_continue_reading_link() {
	$text = __( 'Continue reading', 'nighthawk' );
	if ( 'gallery' == get_post_format() ) {
		$text = __( 'View this gallery', 'nighthawk' );
	}
	return ' <a href="'. esc_url( get_permalink() ) . '">' . esc_html( $text ) . '</a>';
}

/**
 * Entry Meta Taxonomy.
 *
 * Generate and display a sentence containing all core
 * taxonomies associated with the global post object
 * having the "post" post_type.
 *
 * The sentence should conform to the following structure:
 * "This FORMAT is filed under CATEGORY, CATEGORY, CATEGORY and tagged TAG, TAG, TAG."
 *
 * Each capitalized value in the above example should be linked to an
 * archive page that lists all posts of that taxonomy.
 *
 * This function should do nothing for custom post_types.
 *
 * @todo      localize
 *
 * @access    public
 * @since     1.0
 */
function nighthawk_entry_meta_taxonomy() {
	if ( post_password_required() ) {
		return '';
	}

	$sentence = apply_filters( 'nighthawk_entry_meta_taxonomy', '' );
	if ( ! empty( $sentence ) ) {
		print $sentence;
		return;
	}

	$label      = nighthawk_post_label_singular();
	$label_url  = get_post_format_link( get_post_format() );

	if ( 'post' != get_post_type() ) {
		return;
	}

	$post_tags  = get_the_tag_list( '', ', ' );
	$categories = get_the_category_list( ', ' );

	if ( ! empty( $label ) && ! empty( $label_url ) ) {
		$plural = nighthawk_post_label_plural();
		$title = '';
		if ( ! empty( $plural ) ) {
			$title = ' title="' . sprintf( esc_attr__( 'View all %1$s', 'nighthawk' ), strtolower( $plural ) ) . '"';
		}
		$label = '<a href="' . esc_url( $label_url ) . '"' . $title . '>' . esc_html( $label ) . '</a>';
	}

	if ( ! empty( $label ) ) {
		if( ! empty( $categories ) && ! empty( $post_tags ) ) {
			$sentence = sprintf( __( 'This %1$s is filed under %2$s and tagged %3$s.', 'nighthawk' ), $label, $categories, $post_tags );
		}
		else if ( ! empty( $categories ) ) {
			$sentence = sprintf( __( 'This %1$s is filed under %2$s.', 'nighthawk' ), $label, $categories );
		}
		else if ( ! empty( $post_tags ) ) {
			$sentence = sprintf( __( 'This %1$s is tagged %2$s.', 'nighthawk' ), $label, $post_tags );
		}
	}
	else {
		if ( ! empty( $categories ) && ! empty( $post_tags ) ) {
			$sentence = sprintf( __( 'Filed under %1$s and tagged %2$s.', 'nighthawk' ), $categories, $post_tags );
		}
		else if ( ! empty( $categories ) ) {
			$sentence = sprintf( __( 'Filed under %1$s.', 'nighthawk' ), $categories );
		}
		else if ( ! empty( $post_tags ) ) {
			$sentence = sprintf( __( 'Tagged %1$s.', 'nighthawk' ), $post_tags );
		}
	}

	if ( ! empty( $sentence ) ) {
		print '<p>' . $sentence . '</p>';
	}
}

/**
 * Paged Navigation.
 *
 * Print appropriate paged navigation for the current view.
 *
 * @todo      Add support for wp_pagenavi plugin.
 *
 * @access    public
 * @since     1.0
 */
function nighthawk_paged_nav( $args = array() ) {
	$next = get_next_posts_link( __( 'More', 'nighthawk' ) );
	if ( ! empty( $next ) ) {
		$next =  '<div class="nav-paged timeline-regress">' . $next . '</div>';
	}
	$prev = get_previous_posts_link( __( 'Back', 'nighthawk' ) );
	if ( ! empty( $prev ) ) {
		$prev = '<div class="nav-paged timeline-progress">' . $prev . '</div>';
	}
	if ( ! empty( $prev ) || ! empty( $next ) ) {
		print "\n" . $prev . $next;
	}
}

/**
 * Post label - singular.
 *
 * Returns a noun representing the type or format of the global
 * post object. This function is used internally by the
 * nighthawk_entry_meta_taxonomy() function to create a sentence much
 * like the following: "This Status Update is filed under News."
 * where "Status Update" is the post label and "News" is the category.
 *
 * @param     Default value to return.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
function nighthawk_post_label_singular( $default = '' ) {
	$labels = Mfields_Post_Label::get_label();
	if ( isset( $labels[0] ) ) {
		return $labels[0];
	}
	return $default;
}

/**
 * Post label - plural.
 *
 * Returns a noun representing the type or format of the global
 * post object. This function is used internally by the
 * nighthawk_summary_meta() function to create a title attribute
 * for the "Subscribe" link that reads something like:
 * "This image is part of the gallery titled Taco Pictures."
 * where "image" is the post label and "Taco Pictures" is the
 * title of the parent post.
 *
 * @param     Default value to return.
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
function nighthawk_post_label_plural( $default = '' ) {
	$labels = Mfields_Post_Label::get_label();
	if ( isset( $labels[1] ) ) {
		return $labels[1];
	}
	return $default;
}

/**
 * Subscribe to comments checkbox.
 *
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
function nighthawk_subscribe_to_comments_checkbox() {
	$checkbox = '';
	if ( ! function_exists( 'show_subscription_checkbox' ) ) {
		return $checkbox;
	}
	
	ob_start();
	show_subscription_checkbox();
	$checkbox = ob_get_clean();
	 
	return $checkbox;
}

/**
 * Subscribe to comments manual form.
 *
 * @return    string
 *
 * @access    public
 * @since     1.0
 */
function nighthawk_subscribe_to_comments_manual_form( $before = '', $after = '', $print = true, $args = array() ) {
	$args = wp_parse_args( $args, array(
		'heading'   => __( 'Subscribe without commenting', 'nighthawk' ),
		'paragraph' => sprintf( __( 'Please enter your email address and click subscribe to receive an email whenever a new comment is made about this %1$s.', 'nighthawk' ), nighthawk_post_label_singular() ),
		) );
	$form = '';
	global $id, $sg_subscribe, $user_email;

	if ( ! function_exists( 'sg_subscribe_start' ) ) {
		return $form;
	}
	if ( ! is_object( $sg_subscribe ) ) {
		return $form;
	}
	if ( ! method_exists( $sg_subscribe, 'show_errors' ) ) {
		return $form;
	}
	if ( ! method_exists( $sg_subscribe, 'current_viewer_subscription_status' ) ) {
		return $form;
	}

	sg_subscribe_start();

	$sg_subscribe->show_errors( 'solo_subscribe', '<div class="solo-subscribe-errors">', '</div>', __( 'Error: ', 'nighthawk' ), '<br />' );

	if ( ! $sg_subscribe->current_viewer_subscription_status() ) {
		get_currentuserinfo();
		$form.= '<h3>' . esc_html( $args['heading'] ) . '</h3>';
		$form.= '<p>' . esc_html( $args['paragraph'] ) . '</p>';
		$form.= '<form class="bullet subscribe-without-commenting" action="" method="post">';
		$form.= '<input type="hidden" name="solo-comment-subscribe" value="solo-comment-subscribe" />';
		$form.= '<input type="hidden" name="postid" value="' . esc_attr( $id ) . '" />';
		$form.= '<input type="hidden" name="ref" value="' . esc_attr( wp_get_referer() ) . '" />';
		$form.= '<label class="bullet-label" for="solo-subscribe-email">' . esc_html__( 'E-Mail', 'nighthawk' ) . '</label>';
		$form.= '<input class="bullet-term" type="text" name="email" id="solo-subscribe-email" size="22" value="' . esc_attr( $user_email ) . '" />';
		$form.= '<input class="bullet-button" type="submit" name="submit" value="' . esc_attr__( 'Subscribe', 'nighthawk' ) . '" />';
		$form.= '</form>';
	}

	if ( ! empty( $form ) ) {
		$form = $before . $form . $after;
	}

	if ( $print ) {
		print $form;
	}
	else {
		return $form;
	}
}

function _nighthawk_google_fonts() {
	wp_enqueue_style( 'nighthawk-cabin', 'http://fonts.googleapis.com/css?family=Cabin:regular,regularitalic,bold,bolditalic', array(), NIGHTHAWK_VERSION );
}

/**
 * Calendar Widget Title
 *
 * For some reason, WordPress will print a non-breaking space
 * entity wrapped in the appropriate tags for the calendar
 * widget even if the title's value is left empty by the user.
 * This function will remove the empty heading tag.
 *
 * @param     string         The value of the calendar widget's title for this instance.
 * @param     n/a            n/a
 * @param     n/a            n/a
 * @return    string         Calendar widget title.
 *
 * @access    private
 * @since     1.0
 */
function _nighthawk_calendar_widget_title( $title = '', $instance = '', $id_base = '' ) {
	if ( 'calendar' == $id_base && '&nbsp;' == $title ) {
		$title = '';
	}
	return $title;
}

/**
 * Register Widgetized Areas.
 *
 * @return    void
 *
 * @access    private
 * @since     1.0
 */
function _nighthawk_widgets_init() {

	register_sidebar( array(
		'name'          => 'Dropdowns',
		'id'            => 'dropdowns',
		'description'   => 'Dropdowns that appear at the top of the page on all views.',
		'before_widget' => '<div id="%1$s" class="dropdown widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	/* Area 1 - Left column below content. */
	register_sidebar( array(
		'name'          => __( 'Bottom 1', 'nighthawk' ),
		'id'            => 'first-footer-widget-area',
		'description'   => __( 'The first footer widget area', 'nighthawk' ),
		'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	/* Area 2 - Middle column below content. */
	register_sidebar( array(
		'name'          => __( 'Bottom 2', 'nighthawk' ),
		'id'            => 'second-footer-widget-area',
		'description'   => __( 'The second footer widget area', 'nighthawk' ),
		'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	/* Area 3, Right column bottom of content . */
	register_sidebar( array(
		'name'          => __( 'Bottom 3', 'nighthawk' ),
		'id'            => 'third-footer-widget-area',
		'description'   => __( 'The third footer widget area', 'nighthawk' ),
		'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}

/**
 * Configuration for enabling the WordPress custom header image feature.
 *
 * @return    void
 *
 * @access    private
 * @since     1.0
 */
function _nighthawk_custom_image_header() {
	if ( ! defined( 'HEADER_TEXTCOLOR' ) ) {
		define( 'HEADER_TEXTCOLOR', '777' );
	}
	if ( ! defined( 'HEADER_IMAGE' ) ) {
		define( 'HEADER_IMAGE', get_template_directory_uri() . '/images/lanterns.jpg' );
	}
	if ( ! defined( 'HEADER_IMAGE_WIDTH' ) ) {
		define( 'HEADER_IMAGE_WIDTH', 1000 );
	}
	if ( ! defined( 'HEADER_IMAGE_HEIGHT' ) ) {
		define( 'HEADER_IMAGE_HEIGHT', 288 );
	}
	if ( ! defined( 'NO_HEADER_TEXT' ) ) {
		define( 'NO_HEADER_TEXT', true );
	}
	add_custom_image_header( '_nighthawk_custom_image_header_live', '_nighthawk_custom_image_header_admin' );
}

/**
 * CSS for displaying custom header in public views.
 *
 * @return    void
 *
 * @access    private
 * @since     1.0
 */
function _nighthawk_custom_image_header_live() {
	print '<style>#site-name,#site-name a,#tagline{color:#' . HEADER_TEXTCOLOR . '}</style>';
}

/**
 * CSS for displaying custom header in administration views.
 *
 * @return    void
 *
 * @access    private
 * @since     1.0
 */
function _nighthawk_custom_image_header_admin() {
	$background_color = get_theme_mod( 'background_color', 'ffffff' );
	print <<< EOF
<style type="text/css">
div#headimg {
	overflow:hidden;
	background-color:#{$background_color};
	background-repeat:no-repeat;
	background-position:50% 50%;
	padding:0 2em;
}
</style>
EOF;
}

/**
 * Post Classes.
 *
 * @param     array     All classes for the post container.
 * @return    array     Modified classes for the post container.
 *
 * @access    private
 * @since     1.0
 */
function _nighthawk_post_class( $classes ) {
	if ( is_search() ) {
		return array( 'search-result', 'box' );
	}

	$classes[] = 'entry';
	$classes[] = 'box';

	$featured_image = get_the_post_thumbnail();
	if ( ! empty( $featured_image ) ) {
		$classes[] = 'has-featured-image';
	}

	return array_unique( $classes );
}

/**
 * Related Images.
 *
 * Print images related to the image being queried.
 *
 * Child themes and plugins may disable this feature
 * by using the following code:
 *
 * <code>
 * <?php remove_action( 'nighthawk_entry_end', 'nighthawk_related_images' ); ?>
 * </code>
 *
 * Code similar to the following can be used to change the title text.
 *
 * <code>
 * function mytheme_related_images_title_text() {
 *     return 'MY CUSTOM TEXT';
 * }
 * add_filter( 'nighthawk_related_images_title_text', 'mytheme_related_images_title_text' );
 * </code>
 *
 * Likewise, the size of the image can be changed with the following code.
 * It is suggested that you only use image sizes that are cropped.
 *
 * <code>
 * function mytheme_related_images_size() {
 *     return 'thumbnail';
 * }
 * add_filter( 'nighthawk_related_images_size', 'mytheme_related_images_size' );
 * </code>
 *
 * @todo      Update docs. This is now a filter for the_content.
 *
 * @return    void
 *
 * @access    private
 * @since     1.0
 */
function _nighthawk_related_images( $content ) {
	if ( is_attachment() ) {
		$images = array();
		$size = apply_filters( 'nighthawk_related_images_size', 'nighthawk_detail' );
		$title = apply_filters( 'nighthawk_related_images_title_text',  __( 'Related Images', 'nighthawk' ) );
		$parent_id = (int) wp_get_post_parent_id( 0 );
		if ( 0 === strpos( get_post_mime_type(), 'image' ) && ! empty( $parent_id ) ) {
			$images = get_children( array(
				'post_parent'    => $parent_id,
				'post_status'    => 'inherit',
				'post_type'      => 'attachment',
				'post_mime_type' => 'image',
				'exclude'        => get_the_ID()
				) );
		}
		if ( ! empty( $images ) && ! empty( $title ) ) {
			$content.= "\n" . '<h2>' . esc_html( $title ) . '</h2>';
		}
		if ( ! empty( $images ) ) {
			$content.= "\n" . '<ul id="related-images">';
			foreach ( (array) $images as $image ) {
				if ( isset( $image->ID ) ) {
					$content.= "\n" . '<li>' . wp_get_attachment_link( $image->ID, $size, true, false ) . '</li>';
				}
			}
			$content.= "\n" . '</ul>';
			$content.= "\n" . '<div class="clear"></div>';
		}
	}
	return $content;
}

/**
 * Excerpt More (auto).
 *
 * In cases where a post does not have an excerpt defined
 * WordPress will append the string "[...]" to a shortened
 * version of the post_content field. Nighthawk will replace
 * this string with an ellipsis followed by a link to the
 * full post.
 *
 * This filter is attached to the 'excerpt_more' hook
 * in the _nighthawk_setup() function.
 *
 * @return    string         An ellipsis followed by a link to the single post.
 *
 * @access    private
 * @since     1.0
 */
function _nighthawk_excerpt_more_auto( $more ) {
	if ( is_search() ) {
		return ' &hellip;';
	}
	else {
		return ' &hellip; ' . nighthawk_continue_reading_link();
	}
}

/**
 * Excerpt More (custom).
 *
 * For posts that have a custom excerpt defined, WordPress
 * will show this excerpt instead of shortening the post_content.
 * Nighthawk will append a link to the post's single view to the excerpt.
 *
 * This filter is attached to the 'get_the_excerpt' hook
 * in the _nighthawk_setup() function.
 *
 * @return    string         Excerpt with a link to the post's single view.
 *
 * @access    private
 * @since     1.0
 */
function _nighthawk_excerpt_more_custom( $excerpt ) {
	if ( has_excerpt() && ! is_search() && ! is_attachment() && ! is_singular() ) {
		$excerpt .= "\n" . nighthawk_continue_reading_link();
	}
	return $excerpt;
}

/**
 * Comment start.
 *
 * Prints most of a single comment.
 * @see _nighthawk_comment_end().
 *
 * @param     stdClass       Comment object.
 * @param     array          Arguments passed to wp_list_comments() merged with default values.
 * @param     int            Position of the current comment in relation to the root comment of this tree. Starts at zero.
 * @param     void
 *
 * @access    private
 * @since     1.0
 */
function _nighthawk_comment_start( $comment, $args, $depth ) {

	$GLOBALS['comment'] = $comment;

	if ( '' == $comment->comment_type ) {
		print "\n\n\n\n" . '<li id="comment-'; comment_ID(); print '" '; comment_class( 'box' ); print '>';
		if ( 0 === (int) $comment->comment_approved ) {
			print esc_html__( 'Your comment is awaiting moderation.', 'nighthawk' );
		}
		else {
			print "\n" . get_avatar( $comment, 45 );
			print "\n" . '<span class="heading commenter">' . get_comment_author_link( $comment->comment_ID ) . '</span>';
			print "\n" . '<span class="meta">';

			/* Comment date. */
			print "\n" . '<a class="comment-date" href="' . get_comment_link( $comment->comment_ID ) . '"  title="' . esc_attr__( 'Direct link to this comment.', 'nighthawk' ) . '">' . sprintf( esc_html__( '%1$s at %2$s', 'nighthawk' ), get_comment_date(),  get_comment_time() ) . '</a>';

			/* Edit comment link. */
			if ( current_user_can( 'edit_comment', $comment->comment_ID ) ) {
				print "\n" . '<span class="comment-edit"> <a href="' . esc_url( get_edit_comment_link( $comment->comment_ID ) ) . '">' . esc_html__( 'Edit', 'nighthawk' ) . '</a></span>';
			}

			/* Reply to comment link. */
			comment_reply_link( array_merge( $args, array(
				'depth'     => $depth,
				'max_depth' => $args['max_depth'],
				'before'    => "\n" . ' <span class="comment-reply">',
				'after'     => '</span>'
				) ) );

			print '</span><!-- .meta -->';

			print "\n" . '<div class="content">'; comment_text(); print '</div>';
		}
	}
	else {
		print '<li class="trackback box">';
		print '<div class="content">';
			comment_author_link();
			if ( current_user_can( 'edit_comment', $comment->comment_ID ) ) {
				print "\n" . '<span class="comment-edit"> <a href="' . esc_url( get_edit_comment_link( $comment->comment_ID ) ) . '">' . esc_html__( 'Edit', 'nighthawk' ) . '</a></span>';
			}
		print '</div>';
	}
}

/**
 * Comment end.
 *
 * Custom callback for wp_list_comments().
 * Print a closing html list-item element.
 *
 * @param     stdClass       Comment object.
 * @param     array          Arguments passed to wp_list_comments() merged with default values.
 * @param     int            Position of the current comment in relation to the root comment of this tree. Starts at zero.
 * @param     void
 *
 * @access    private
 * @since     1.0
 */
function _nighthawk_comment_end( $comment, $args, $depth ) {
	print '</li>';
}

/**
 * Comment Reply Script.
 *
 * Enqueue comment reply script on singular views.
 *
 * In the event that a user has threaded comments enabled
 * for their installation this function will include the
 * appropriate javascript files on single views where
 * commenting is enabled.
 *
 * @access    private
 * @since     1.0
 */
function _nighthawk_comment_reply_js() {
	if ( is_singular() && comments_open() ) {
		if ( get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
}

/**
 * Enclose embedded media in a div.
 *
 * Wrapping all flash embeds in a div allows for easier
 * styling with CSS media queries.
 *
 * @todo      Document parameters.
 *
 * @access    private
 * @since     1.0
 */
function _nighthawk_oembed_dataparse( $cache, $url, $attr = '', $post_ID = '' ) {
	return '<div class="embed">' . $cache . '</div>';
}

/**
 * SyntaxHighlighter Evolved Support.
 *
 * Registers a custom theme with the SyntaxHighlighter Evolved plugin.
 *
 * {@link http://wordpress.org/extend/plugins/syntaxhighlighter/ SyntaxHighlighter Evolved }
 *
 * @param     array     All themes registered with the SyntaxHighlighter Evolved plugin.
 * @return    array     Same list with custom theme appended.
 *
 * @access    private
 * @since     1.0
 */
function _nighthawk_syntaxhighlighter_theme( $themes ) {
	$themes['nighthawk'] = 'Nighthawk';
	return $themes;
}

/**
 * Custom Header Controls.
 *
 * Print a settings section enabling user to choose which
 * individual text settings will appear in the theme.
 * These controls should appear at the bottom of the form
 * located under Appearance -> Header in the administration
 * panels. It will inherit a nonce from the core form. The
 * values will be saved as "Theme Modifications" and the values
 * are processed by _nighthawk_process_custom_header_settings().
 *
 * This action is attached to the 'custom_header_options'
 * hook in the _nighthawk_setup() function.
 *
 * @return    void
 *
 * @access    private
 * @since     1.0
 */
function _nighthawk_settings_custom_header_text_controls() {
	print '<table class="form-table"><tbody><tr><th>' . esc_html__( 'Header Text', 'nighthawk' ) . '</th><td>';
	_nighthawk_control_boolean( 'nighthawk_display_site_title', esc_html__( 'Display site title.', 'nighthawk' ), get_theme_mod( 'nighthawk_display_site_title', 1 ) );
	_nighthawk_control_boolean( 'nighthawk_display_tagline', esc_html__( 'Display tagline.', 'nighthawk' ), get_theme_mod( 'nighthawk_display_tagline', 1 ) );
	print '</td></tr></tbody></table>';
}

/**
 * Process Custom Header Settings.
 *
 * This action is attached to the 'admin_head-appearance_page_custom-header'
 * hook in the _nighthawk_setup() function.
 *
 * @return    void
 *
 * @access    private
 * @since     1.0
 */
function _nighthawk_process_custom_header_settings() {
	if ( isset( $_POST['save-header-options'] ) ) {
		check_admin_referer( 'custom-header-options', '_wpnonce-custom-header-options' );

		if ( ! current_user_can( 'edit_theme_options' ) ) {
			return;
		}

		$display_title = 0;
		if ( isset( $_POST['nighthawk_display_site_title'] ) ) {
			$display_title = 1;
		}
		set_theme_mod( 'nighthawk_display_site_title', $display_title );

		$display_tagline = 0;
		if ( isset( $_POST['nighthawk_display_tagline'] ) ) {
			$display_tagline = 1;
		}
		set_theme_mod( 'nighthawk_display_tagline', $display_tagline );
	}
}

/**
 * Boolean control.
 *
 * Generates and prints a form element/label to
 * control a boolean setting.
 *
 * @param     string    The key of a recognized setting.
 * @param     string    Localized, human-readable label for the setting.
 * @param     bool      Current value of the setting.
 * @return    void
 *
 * @access    private
 * @since     1.0
 */
function _nighthawk_control_boolean( $id, $label, $value = 0 ) {
	print "\n\n" . '<input' . ( ! empty( $value ) ? ' checked="checked"' : '' ) . ' type="checkbox" id="' . esc_attr( $id ) . '" name="' . esc_attr( $id ) . '" value="1" /> ';
	print "\n" . '<label for="' . esc_attr( $id ) . '">' . esc_html( $label ) . '</label><br />';
}

/**
 * Search Form ID.
 *
 * @return    string    ID attribute for search form.
 *
 * @access    public
 * @since     1.0
 */
function nighthawk_search_id() {
	static $id = 0;
	$id++;
	return 'search-form-' . $id;
}

/**
 * Password Form.
 *
 * @param     string    Default WordPress search from.
 * @return    string    Custom Search form.
 *
 * @access    private
 * @since     1.0
 */
function _nighthawk_password_form( $form ) {
	static $id = 0;
	$id++;
	$id_attr = 'password-form-' . $id;

	$form = "\n\n";
	$form.= '<p>' . esc_html__( 'This post is password protected. To view it please enter your password below:', 'nighthawk' ) . '</p>';
	$form.= '<form class="bullet" action="' . esc_url( get_option( 'siteurl' ) . '/wp-pass.php' ) . '" method="post">';
	$form.= '<label class="bullet-label" for="' . esc_attr( $id_attr ) . '">' . __( 'Enter Password', 'nighthawk' ) . '</label>';
	$form.= '<input class="bullet-term" name="post_password" id="' . esc_attr( $id_attr ) . '" type="password" size="20" />';
	$form.= '<input class="bullet-button" type="submit" name="Submit" value="' . esc_attr__( 'Unlock', 'nighthawk' ) . '" />';
	$form.= '</form>';
	return $form;
}

/**
 * Menu dialog.
 *
 * Override WordPress default fallback for wp_nav_menu().
 * Instead of listing all pages, we will display a dialog
 * informing users with the appropriate capability that
 * they can create a custom menu for this section of their
 * theme. A link will be provided to wp-admin.nav-menus.php.
 * Visitors and authenticated users with insufficient
 * capabilities will be shown nothing.
 *
 * User has the option of hiding the menu in the event that
 * they do not wish to see the message anymore.
 *
 * @todo      Enable Ajax functionality for menu hiding.
 *
 * @param     array     Arguments originally passed to wp_nav_menu.
 * @return    string    Dialog for those who can edit theme options - empty sting to all others.
 *
 * @access    private
 * @since     1.0
 */
function _nighthawk_menu_dialog( $args ) {
	$defaults = array(
		'container'      => 'div',
		'container_id'   => '',
		'theme_location' => ''
		);

	$args = wp_parse_args( $args, $defaults );

	if ( ! in_array( trim( strtolower( $args['container'] ) ), array( 'div', 'section', 'nav', 'li' ) ) ) {
		$args['container'] = $defaults['container'];
	}

	$id = '';
	if ( ! empty( $args['container_id'] ) ) {
		$id = ' id="' . esc_attr( $args['container_id'] ) . '"';
	}

	if ( ! isset( $args['theme_location'] ) ) {
		return;
	}

	if ( 1 == get_theme_mod( 'hide_message_for_menu_' . $args['theme_location'], 0 ) ) {
		print '<' . $args['container'] . $id . '></' . $args['container'] . '>';
		return;
	}

	$class = '';
	$message = '';

	/* Only create a message for users who can edit nav menus. */
	if ( current_user_can( 'edit_theme_options' ) ) {
		$class = ' class="no-menu"';

		global $_wp_registered_nav_menus;

		/* Default message. */
		$first = esc_html__( 'You have not defined a navigation menu for this theme location.', 'nighthawk' );

		/* Attempt to retrieve the actual name of the current theme location. */
		if ( ! empty( $args['theme_location'] ) && isset( $_wp_registered_nav_menus[$args['theme_location']] ) ) {
			$first = sprintf( esc_html__( 'You have not defined a navigation menu for the theme location named "%1$s".', 'nighthawk' ), $_wp_registered_nav_menus[$args['theme_location']] );
		}

		$message = '<p class="dialog notice">';

		/* Provide a link to the appropriate administration panel. */
		$message.= $first . '<br>' . sprintf( esc_html__( 'Please visit the %1$s to manage your menus.', 'nighthawk' ), '<a href="' . esc_url( admin_url( '/nav-menus.php' ) ) . '">' . esc_html__( 'menus page' ) . '</a>' );

		/* Build a link to hide the message. */
		$message.= '<a href="' . esc_url( admin_url( '/admin-ajax.php?action=nighthawk_hide_message_nav_menu&_wpnonce=' . wp_create_nonce( 'nighthawk_hide_menu_' . $args['theme_location'] ) . '&menu=' . $args['theme_location'] ) ) . '"> ' . esc_html__( 'Hide this message', 'nighthawk' ) . '</a>';

		$message.= '</p>';
	}

	print '<' . $args['container'] . $id . $class . '>' . $message . '</' . $args['container'] . '>';
}

/**
 * Hide nav menu messages.
 *
 * This function will fire on a request to admin-ajax.php
 * where action is passed as "nighthawk_hide_message_nav_menu".
 * Although Ajax is not currently used this seemed like the
 * most appropriate place to hook into WordPress.
 *
 * @todo      Enable Ajax functionality for menu hiding.
 *
 * @access    private
 * @since     1.0
 */
function _nighthawk_ajax_hide_message_nav_menu() {

	$clean = array();

	/* Menu needs to be set. */
	if ( ! isset( $_GET['menu'] ) ) {
		wp_safe_redirect( wp_get_referer() );
	}

	/* Menu needs to exist. */
	$locations = (array) get_nav_menu_locations();
	if ( ! array_key_exists( $_GET['menu'], $locations ) ) {
		wp_safe_redirect( wp_get_referer() );
	}

	$clean['menu'] = $_GET['menu'];

	/* Nonce check. */
	if ( false === check_ajax_referer( 'nighthawk_hide_menu_' . $clean['menu'], false, false ) ) {
		wp_safe_redirect( wp_get_referer() );
	}

	/* User needs to have the correct capability. */
	if ( current_user_can( 'edit_theme_options' ) ) {
		set_theme_mod( 'hide_message_for_menu_' . $clean['menu'], 1 );
	}

	wp_safe_redirect( wp_get_referer() );
}

/**
 * Edit post link filter.
 *
 * @access    private
 * @since     1.0
 */
function _nighthawk_edit_post_link( $html, $ID ) {
	return '<a class="post-edit-link" href="' . esc_url( get_edit_post_link( $ID ) ) . '" title="' . sprintf( esc_attr__( 'Edit this %1$s', 'nighthawk' ), nighthawk_post_label_singular() ) . '">' . esc_html( wp_strip_all_tags( $html ) ) . '</a>';
}

function nighthawk_entry_id() {
	print esc_attr( nighthawk_entry_id_get() );
}

function nighthawk_entry_id_get() {
	$parts = array(
		get_post_type(),
		get_post_format(),
		get_the_ID()
		);
	$attr = implode( '-', array_filter( $parts ) );
	if ( ctype_alnum( $attr ) ) {
		return 'entry-' . $attr;
	}
	return $attr;
}

function nighthawk_entry_template_name() {
	$template = get_post_type();
	if ( 'post' == $template ) {
		$format = get_post_format();
		if ( ! empty( $format ) ) {
			$template .= '-' . get_post_format();
		}
	}
	return sanitize_title_with_dashes( $template );
}

function _nighthawk_filter_post_title( $title ) {
	if ( ! is_singular() ) {
		return $title;
	}
	if ( empty( $title ) && 'post' == get_post_type() ) {
		$title = ucfirst( nighthawk_post_label_singular() );
	}
	return $title;
}
add_action( 'the_title', '_nighthawk_filter_post_title' );

function _nighthawk_commentform_before() {
	print "\n" . '<div class="box">';
}
add_action( 'comment_form_before', '_nighthawk_commentform_before' );

function _nighthawk_commentform_after() {
	print "\n" . '</div>';
}
add_action( 'comment_form_after', '_nighthawk_commentform_after' );

function _nighthawk_widget_dropdowns_scripts() {
	if ( is_admin() ) {
		return;
	}
	wp_enqueue_script(
		'dropdown-widgets',
		get_template_directory_uri() . '/dropdowns.js',
		array( 'jquery' ),
		'0.1',
		true
	);
}
add_action( 'wp_print_scripts', '_nighthawk_widget_dropdowns_scripts' );

function nighthawk_post_labels_init() {
	require_once 'mfields-post-label.php';
	Mfields_Post_Label::init( 'nighthawk' );
	#add_action( 'shutdown', array( 'Mfields_Post_Label', 'dump' ) );
}

Nighthawk::init();

class Nighthawk {
	static private $query = null;
	static private $theme = null;
	static public function init() {
		add_action( 'template_redirect', array( __class__, 'setup' ) );
	}
	static public function post_total() {
		return (int) self::$query->total;
	}
	static public function columns() {
		if ( current_user_can( 'edit_posts' ) ) {
			$edit = array(
				'label'    => __( 'Edit', 'nighthawk' ),
				'class'    => 'edit-post icon',
				'callback' => 'nighthawk_td_edit',
			);
			array_unshift( self::$theme->columns, $edit );
		}
		return (array) self::$theme->columns;
	}
	static public function set_columns( $columns = null ) {
		self::$theme->columns = $columns;
	}
	static public function setup() {
		self::$query = new stdClass();

		global $wp_query;
		$total = 0;
		if ( isset( $wp_query->found_posts ) ) {
			self::$query->total = $wp_query->found_posts;
		}

		self::$theme->columns = array(
			array(
				'label'    => __( 'Post Title', 'nighthawk' ),
				'class'    => 'post-title',
				'callback' => 'nighthawk_td_title',
			),
			array(
				'label'    => __( 'Comment Count', 'nighthawk' ),
				'class'    => 'comment-count',
				'callback' => 'nighthawk_td_comment_count',
			),
			array(
				'label'    => __( 'Comment Link', 'nighthawk' ),
				'class'    => 'comment-respond icon',
				'callback' => 'nighthawk_td_comment_icon',
			),
		);
	}
}

function nighthawk_td_edit( $column = array() ) {
	print "\n\t" . '<td class="' . esc_attr( $column['class'] ) . '">';
	print '<a href="' . esc_url( get_edit_post_link() ) . '"><img src="' . esc_url( get_template_directory_uri() . '/images/edit.png' ) . '" alt="' . esc_attr__( 'Edit', 'nighthawk' ) . '"></a>';
	print '</td>';
}

function nighthawk_td_comment_count( $column = array() ) {
	$post_type = get_post_type();
	if ( ! post_type_supports( $post_type, 'title' ) ) {
		print "\n\t" . '<td class="' . esc_attr( $column['class'] ) . ' empty-cell"></td>';
		return;
	}
	print "\n\t" . '<td class="' . esc_attr( $column['class'] ) . '">';
	comments_popup_link( '0', '1', '%', 'comments-link', '' );
	print '</td>';
}

function nighthawk_td_title( $column = array() ) {
	$post_type = get_post_type();
	if ( ! post_type_supports( $post_type, 'title' ) ) {
		print "\n\t" . '<td class="' . esc_attr( $column['class'] ) . ' empty-cell"></td>';
		return;
	}

	$title = the_title( '', '', false );
	if ( empty( $title ) ) {
		$title = sprintf( 'untitled %1$s', nighthawk_post_label_singular() );
	}

	$url = get_post_meta( get_the_ID(), '_mfields_bookmark_url', true );
	if ( ! empty( $url ) ) {
		$title_attr = 'Visit this document';
		$action = get_post_meta( get_the_ID(), '_mfields_bookmark_link_text', true );
		if ( ! empty( $action ) ) {
			$title_attr = ' title="' . esc_attr( $action ) . '"';
		}
		$title  = '<a href="' . esc_url( $url ) . '" rel="external"' . $title_attr . '>' . $title . '</a>';
	}

	print "\n\t" . '<td class="' . esc_attr( $column['class'] ) . '"><a href="' . esc_url( get_permalink() ) . '">' . $title . '</a></td>';
}

function nighthawk_td_comment_icon( $column = array() ) {
	$post_type = get_post_type();
	if ( ! post_type_supports( $post_type, 'comments' ) ) {
		print "\n\t" . '<td class="' . esc_attr( $column['class'] ) . ' empty-cell"></td>';
		return;
	}

	if ( ! comments_open( get_the_ID() ) ) {
		nighthawk_td_permalink_icon( $column );
		return;
	}

	print "\n\t" . '<td class="' . esc_attr( $column['class'] ) . '"><a href="' . esc_url( get_permalink() . '#respond' ) . '" class="comment-icon" title="' . esc_attr__( 'Add a comment', 'nighthawk' ) . '"><img src="' . esc_url( get_template_directory_uri() ) . '/images/comment.png" alt="" /></a></td>';
}

/**
 * @todo Get a permalink icon for here!!!
 */
function nighthawk_td_permalink_icon( $column = array() ) {
	print "\n\t" . '<td class="' . esc_attr( $column['class'] ) . '"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark" class="comment-icon" title="' . esc_attr__( 'Permalink', 'nighthawk' ) . '"><img src="' . esc_url( get_template_directory_uri() ) . '/images/comment.png" alt="" /></a></td>';
}

function nighthawk_td_bookmark_source( $column = array() ) {
	$taxonomy = 'mfields_bookmark_source';
	$sources = get_the_terms( get_the_ID(), $taxonomy );

	if ( is_wp_error( $sources ) ) {
		return;
	}

	$link = '';
	if ( ! empty( $sources ) && is_array( $sources ) ) {
		$source = current( $sources );
		if ( isset( $source->name ) ) {
			$link = '<a href="' . esc_url( get_term_link( $source, $taxonomy ) ) . '">' . esc_html( $source->name ) . '</a>';
		}
	}

	print "\n\t" . '<td class="' . esc_attr( $column['class'] ) . '">' . $link . '</td>';
}
