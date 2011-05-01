<?php
/**
 * Functions
 *
 * @package      Nighthawk
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        1.0
 * @alter        1.1
 *
 */

define( 'NIGHTHAWK_VERSION', '1.1DEV' );

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
 * @alter     1.1
 */
function _nighthawk_setup() {

	global $content_width;
	if ( ! isset( $content_width ) ) {
		$content_width = 615;
	}

	load_theme_textdomain( 'nighthawk', get_template_directory() . '/languages' );

	add_theme_support( 'menus' );
	add_theme_support( 'post-formats', array( 'aside', 'gallery', 'link', 'status', 'quote', 'video' ) );
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

	/* Nighthawk hooking into WordPress. */
	add_action( 'body_class',                 '_nighthawk_body_class' );
	add_filter( 'edit_post_link',             '_nighthawk_edit_post_link', 9, 2 );
	add_filter( 'embed_oembed_html',          '_nighthawk_oembed_dataparse', 10, 4 );
	add_filter( 'embed_googlevideo',          '_nighthawk_oembed_dataparse', 10, 2 );
	add_filter( 'excerpt_more',               '_nighthawk_excerpt_more_auto' );
	add_filter( 'get_search_form',            '_nighthawk_search_form' );
	add_filter( 'get_the_excerpt',            '_nighthawk_excerpt_more_custom' );
	add_filter( 'post_class',                 '_nighthawk_post_class_entry' );
	add_filter( 'post_class',                 '_nighthawk_post_class_featured' );
	add_filter( 'post_thumbnail_html',        '_nighthawk_featured_image_first_attachment' );
	add_action( 'the_content',                '_nighthawk_related_images' );
	add_filter( 'the_content',                '_nighthawk_content_prepend_title', 9 );
	add_filter( 'the_content',                '_nighthawk_content_append_link', 9 );
	add_filter( 'the_content',                '_nighthawk_content_append_link_edit', 9 );
	add_filter( 'the_password_form',          '_nighthawk_password_form' );
	add_action( 'widget_title',               '_nighthawk_calendar_widget_title', 10, 3 );
	add_action( 'widgets_init',               '_nighthawk_widgets_init' );
	add_action( 'wp_loaded',                  '_nighthawk_custom_image_header' );
	add_action( 'wp_print_scripts',           '_nighthawk_comment_reply_js' );
	add_action( 'wp_print_styles',            '_nighthawk_google_fonts' );

	/* Ajax Callbacks */
	add_action( 'wp_ajax_nighthawk_hide_message_nav_menu', '_nighthawk_ajax_hide_message_nav_menu' );

	/* Custom hooks. */
	add_action( 'nighthawk_logo',               'nighthawk_logo', 10, 2 );
	add_action( 'nighthawk_paged_navigation',   'nighthawk_paged_nav', 10, 2 );
	add_action( 'nighthawk_loop_search_start',  '_nighthawk_excerpt_search_toggle_wpautop' );
	add_action( 'nighthawk_loop_search_end',    '_nighthawk_excerpt_search_toggle_wpautop' );

	/* Theme modifications. */
	add_action( 'custom_header_options', '_nighthawk_settings_custom_header_text_controls' );
	add_action( 'admin_head-appearance_page_custom-header', '_nighthawk_process_custom_header_settings', 51 );
	if ( 0 != (int) get_theme_mod( 'nighthawk_display_site_title', 0 ) ) {
		add_action( 'nighthawk_site_title', 'nighthawk_site_title', 10, 2 );
	}
	if ( 0 != (int) get_theme_mod( 'nighthawk_display_tagline', 0 ) ) {
		add_action( 'nighthawk_tagline', 'nighthawk_tagline', 10, 2 );
	}

	/* Nighthawk hooking into SyntaxHighlighter Evolved plugin. */
	wp_register_style( 'syntaxhighlighter-theme-nighthawk', get_template_directory_uri() . '/style-syntax-highlighter.css', array( 'syntaxhighlighter-core' ), '1' );
	add_filter( 'syntaxhighlighter_themes', '_nighthawk_syntaxhighlighter_theme' );
}
add_action( 'after_setup_theme', '_nighthawk_setup' );

/**#@+
 * Public Functions
 *
 * Any function defined in this this section may be used
 * freely in appropriate template files. Please see
 * each function's documentation for intended usage.
 *
 * Functions are roughly defined in the order that
 * they would be called during a template request.
 *
 * @access    public
 */

/**
 * Logo.
 *
 * The logo is defined using the WordPress Header Image
 * feature. User can upload a custom logo from their
 * hard drive or choose to use no logo at all.
 *
 * The "Site Title" as defined by the user via
 * Settings -> General will be used to populate
 * the image's alt attribute.
 *
 * This function will wrap the image in an anchor tag
 * for all views save the front page.
 *
 * @param     string    Text to print before the logo.
 * @param     string    Text to print after the logo.
 * @return    void      Print the logo if stored value is not empty.
 *
 * @since     1.0
 */
function nighthawk_logo( $before = '', $after = '' ) {
	$url = get_header_image();
	if ( ! empty( $url ) ) {
		$img = '<img src="' . esc_url( $url ) . '" width="' . esc_attr( HEADER_IMAGE_WIDTH ) . '" height="' . esc_attr( HEADER_IMAGE_HEIGHT ) . '" alt="' . esc_attr( get_bloginfo( 'blogname' ) ) . '">';
		if ( ! is_front_page() || is_paged() ) {
			$img = '<a href="' . esc_attr( home_url() ) . '">' . $img . '</a>';
		}
		print "\n" . $before . $img . $after;
	}
}

/**
 * Title.
 *
 * Print the site's title. Value is defined
 * under Settings -> General -> Site Title.
 * in the administration panels.
 *
 * This function will wrap the title in an anchor tag
 * for all views save the front page.
 *
 * @param     string    Text to print before the site title.
 * @param     string    Text to print after the site title.
 * @return    void      Print the site title if stored value is not empty.
 *
 * @since     1.0
 */
function nighthawk_site_title( $before = '', $after = '' ) {
	$text = get_bloginfo( 'blogname' );
	if ( ! empty( $text ) ) {
		if ( ! is_front_page() || is_paged() ) {
			$text = '<a href="' . esc_url( home_url() ) . '">' . esc_html( $text ) . '</a>';
		}
		print "\n" . $before . $text . $after;
	}
}

/**
 * Tagline.
 *
 * Print the tagline of the site.
 *
 * Value is defined by administrator via
 * Settings -> General -> Tagline.
 *
 * @param     string    Text to print before the tagline.
 * @param     string    Text to print after the tagline.
 * @return    void      Print the tagline if stored value is not empty.
 *
 * @since     1.0
 */
function nighthawk_tagline( $before = '', $after = '' ) {
	$text = get_bloginfo( 'description' );
	if ( ! empty( $text ) ) {
		print "\n" . $before . esc_html( $text ) . $after;
	}
}

/**
 * Title.
 *
 * Generate a title for each view.
 *
 * v1.1 - Search support removed.
 *
 * @param     string    Text to include before the title.
 * @param     string    Text to include after the title.
 * @param     bool      Should this function print? Defaults to true.
 * @return    string/void
 *
 * @since     1.0
 */
function nighthawk_title( $before = '', $after = '', $print = true ) {
	$o = '';
	$url = '';
	$title = '';
	$post_type_qv = get_query_var( 'post_type' );
	if ( 'page' == $post_type_qv ) {
		$title = __( 'Pages', 'nighthawk' );
		if ( is_paged() ) {
			$url = add_query_arg( 'post_type', 'page', trailingslashit( home_url() ) );
		}
	}
	else if ( is_home() ) {
		$title = apply_filters( 'nighthawk_title_timeline', __( 'Timeline', 'nighthawk' ) );
		if ( is_paged() ) {
			$url = home_url();
		}
	}
	else if ( is_singular() ) {
		$post_type = get_post_type();
		$title = get_the_title();
		if ( empty( $title ) && 'post' == $post_type ) {
			$title = ucfirst( nighthawk_post_label_singular() );
		}
		$title = apply_filters( 'nighthawk_title_singular', $title );
		$title = apply_filters( "nighthawk_title_singular_{$post_type}", $title );
	}
	else if ( is_tax() || is_category() || is_tag() ) {
		global $wp_query;
		$term = $wp_query->get_queried_object();
		if ( isset( $term->name ) && isset( $term->taxonomy ) && isset( $term->slug ) ) {
			if ( 'post_format' == $term->taxonomy ) {
				$term->name = ucfirst( nighthawk_post_label_plural() );
			}
			$title = apply_filters( "nighthawk_title_taxonomy_{$term->taxonomy}", $term->name );
			if ( is_paged() ) {
				$url = get_term_link( $term, $term->taxonomy );
			}
		}
	}
	else if ( is_author() ) {
		global $wp_query;
		$author = $wp_query->get_queried_object();
		if ( isset( $author->display_name ) ) {
			$title = sprintf( __( 'All entries by %1$s', 'nighthawk' ), $author->display_name );
		}
	}
	else if ( is_day() ) {
		$title = sprintf( __( 'Entries from %1$s', 'nighthawk' ), get_the_date() );
	}
	else if ( is_month() ) {
		$title = sprintf( __( 'Entries from %1$s', 'nighthawk' ), get_the_date( 'F, Y' ) );
	}
	else if ( is_year() ) {
		$title = sprintf( __( 'Entries from %1$s', 'nighthawk' ), get_the_date( 'Y' ) );
	}
	else if ( is_post_type_archive() ) {
		$title = post_type_archive_title( '', false );
		global $wp_query;
		$post_type = $wp_query->get_queried_object();
		if ( isset( $post_type->name ) && is_paged() ) {
			$url = get_post_type_archive_link( $post_type->name );
		}
	}

	if ( ! empty( $title ) ) {
		$allowed = array(
			'abbr'    => array(),
			'b'       => array(),
			'em'      => array(),
			'i'       => array(),
			'q'       => array(),
			'span'    => array(),
			'strong'  => array(),
			'var'     => array(),
			);
		if ( ! empty( $url ) ) {
			$o = '<a href="' . esc_url( $url ) . '">' . wp_kses( $title, $allowed ) . '</a>';
		}
		else {
			$allowed['a'] = array(
				'class' => array (),
				'href'  => array (),
				'id'    => array (),
				'title' => array (),
				'rel'   => array (),
				'rev'   => array (),
				'name'  => array ()
				);
			$o = wp_kses( $title, $allowed );
		}
	}

	$o = $before . $o . $after;

	if ( $print ) {
		print $o;
	}
	else {
		return $o;
	}
}

/**
 * Byline.
 *
 * Display the name of an entry's author in singular views.
 *
 * @param     string    Text to print before.
 * @param     string    Text to print after.
 * @return    void
 *
 * @since     1.0
 */
function nighthawk_byline( $before = '', $after = '' ) {
	$byline = '';
	$author_name = '';
	if ( is_singular() && ! is_attachment() ) {
		$author_name = get_the_author();
		/*
		 * get_the_author() only works inside the loop.
		 * Need to do manual labor if nighthawk_byline()
		 * is used outside the loop.
		 */
		if ( empty( $author_name ) ) {
			global $posts;
			if ( isset( $posts[0]->post_author ) ) {
				$author = get_userdata( $posts[0]->post_author );
				if ( isset( $author->display_name ) ) {
					$author_name = $author->display_name;
				}
			}
		}
	}
	if ( ! empty( $author_name ) ) {
		$byline = sprintf( __( 'By %1$s', 'nighthawk' ), $author_name );
	}
	$byline = apply_filters( 'nighthawk-byline', $byline, $author_name );
	if ( ! empty( $byline ) ) {
		print "\n" . $before . esc_html( $byline ) . $after;
	}
}

/**
 * Summary.
 *
 * This function is will look for a summary for the
 * queried object. There are currently 4 supported summary
 * types:
 *
 * The first is for taxonomy term archives. If the queried
 * term has a value in its description field, this will be used
 * as the summary.
 *
 * The second is for pages. Nighthawk enables the exceprt meta box
 * for "page" post_type. If defined, the excerpt will be recognized
 * as the summary.
 *
 * The third is for author archives. If the queried author has
 * filled out the "Biographical Info" portion of their profile
 * this data will be used as the summary.
 *
 * The fourth is for post_type archive pages. If the post_type
 * has been registered with a 'description' property, the value
 * of this property will be used as the summary.
 *
 * Child themes and plugins can use the 'nighthawk_summary'
 * filter to modify this function's output.
 *
 * v1.1 - Support for pages has been removed.
 *
 * @param     string         Text to print before the summary.
 * @param     string         Text to print after the summary.
 * @param     bool           True to print the summary, false to return it as a string.
 * @return    void/string
 *
 * @since     1.0
 * @alter     1.1
 */
function nighthawk_summary( $before = '', $after = '', $print = true ) {
	$summary = '';
	if ( is_category() || is_tag() || is_tax() ) {
		$summary = term_description();
	}
	else if ( is_author() ) {
		global $wp_query;
		$summary = get_the_author_meta( 'description', $wp_query->get_queried_object_id() );
		$summary = apply_filters( 'nighthawk_filter_text', $summary );
	}
	else if ( is_post_type_archive() ) {
		global $wp_query;
		$post_type  = $wp_query->get_queried_object();
		if ( isset( $post_type->description ) && ! empty( $post_type->description ) ) {
			$summary = apply_filters( 'nighthawk_filter_text', esc_html( $post_type->description ) );
		}
	}
	$summary = apply_filters( 'nighthawk_summary', $summary );
	if ( ! empty( $summary ) ) {
		$summary = "\n" . $before . $summary . $after;
		if ( ! $print ) {
			return $summary;
		}
		print $summary;
	}
}

/**
 * Summary Meta.
 *
 * Print meta information pertaining to the current view.
 *
 * v1.1 - Support for categories has been removed.
 * v1.1 - Support for tags has been removed.
 *
 * @param     string         Text to prepend to the summary meta.
 * @param     string         Text to append to the summary meta.
 * @param     bool           True to print, false to return a string. Defaults to true.
 * @return    void/string
 *
 * @since     1.0
 * @alter     1.1
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
			$sentence   = sprintf( _n( 'Only one %2$s found in this archive.', 'There are %1$s %2$s in this archive.', $total, 'nighthawk' ), number_format_i18n( $total ), nighthawk_post_label_plural(), nighthawk_post_label_singular() );
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
 * @since     1.0
 */
function nighthawk_continue_reading_link() {
	$text = __( 'Continue reading', 'nighthawk' );
	if ( 'gallery' == get_post_format() ) {
		$text = __( 'View this gallery', 'nighthawk' );
	}
	return ' <a href="'. esc_url( get_permalink() ) . '">' . esc_html( $text ) . '</a>';
}

function nighthawk_entry_meta_classes() {
	$classes = array( 'entry-meta' );
	if ( post_password_required() ) {
		$classes[] = 'hide';
	}
	return implode( ' ', $classes );
}

/**
 * Entry Meta Date
 *
 * Generate and display a sentence containing the date
 * and time that the global post object was published
 * and a link to the comments section. The date and time
 * string will be linked to the post's single view. The
 * comment link should not display in singular views.
 *
 * If the current logged-in user has the capability to
 * edit the post, an edit link will be displayed as well.
 *
 * An example sentence might read:
 * "Posted on January 24, 2011 at 12:20 am * Comment * Edit"
 *
 * The star (*) character in the above example represents a
 * bullet point U+2022 (8226) which is included via the css
 * :before pseudo class.
 *
 * @return    void
 *
 * @since     1.0
 * @alter     1.1
 */
function nighthawk_entry_meta_date() {
	if ( post_password_required() ) {
		return '';
	}

	print "\n" . '<p>';

	/* Date + Permalink */
	$title_attr  = sprintf( __( 'Permanent link to this %1$s', 'nighthawk' ), nighthawk_post_label_singular() );
	$date_format = sprintf( __( '%1$s \a\t %2$s', 'nighthawk' ), get_option( 'date_format' ), get_option( 'time_format' ) );
	$date        = get_the_time( $date_format );
	$datestamp = '<a class="datetime" href="' . esc_url( get_permalink() ) . '" title="' . esc_attr( $title_attr ) . '">' . esc_html( $date ) . '</a>';
	if ( is_single() ) {
		$datestamp =  '<span class="datetime" title="' . esc_attr( $title_attr ) . '">' . esc_html( $date ) . '</span>';
	}

	printf( __( 'Posted on %1$s', 'nighthawk' ), $datestamp );

	/* Comments */
	if ( ! is_singular() && ( ( comments_open() && ! post_password_required() ) || 0 < get_comments_number() ) ) {
		print ' ';
		print '<span class="comment-link">';
		comments_popup_link(
			esc_html( _x( 'Comment', 'verb', 'nighthawk' ) ), /* Zero comments */
			esc_html( __( '1 Comment', 'nighthawk' ) ),       /* One Comment */
			esc_html( __( '% Comments', 'nighthawk' ) ),      /* More than one comment */
			'',                                               /* CSS class */
			esc_html( __( '% Comments', 'nighthawk' ) )       /* Comments Disabled */
			);
		print '</span>';
	}

	/* Edit link */
	edit_post_link( esc_html__( 'Edit', 'nighthawk' ), ' <span class="post-edit">', '</span>' );

	print '</p>';
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
 * @return    void
 *
 * @since     1.0
 * @alter     1.1
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
 * @return    void
 *
 * @since     1.0
 *
 * @todo      Add support for wp_pagenavi plugin.
 */
function nighthawk_paged_nav( $args = array() ) {
	$next = get_next_posts_link( __( 'More', 'nighthawk' ) );
	if ( ! empty( $next ) ) {
		$next =  '<div class="more-posts">' . $next . '</div>';
	}
	$prev = get_previous_posts_link( __( 'Back', 'nighthawk' ) );
	if ( ! empty( $prev ) ) {
		$prev = '<div class="back-posts">' . $prev . '</div>';
	}
	if ( ! empty( $prev ) || ! empty( $next ) ) {
		print "\n" . $prev . $next;
	}
}

/**
 * Featured Image.
 *
 * @param     string         Text to prepend to the image tag.
 * @param     string         Text to append to the image tag.
 * @param     bool           True to print, false to return a string. Defaults to true.
 * @return    void/string
 *
 * @todo Allow to be hidden by postmeta.
 *
 * @since     1.0
 */
function nighthawk_featured_image( $before = '', $after = '', $print = true ) {
	if ( post_password_required() ) {
		return '';
	}
	$image = '';
	$featured_image = get_the_post_thumbnail();
	if ( ! empty( $featured_image ) ) {
		$image = $featured_image;
		if ( ! is_singular() ) {
			$image = '<a href="' . esc_url( get_permalink() ) . '">' . $image . '</a>';
		}
	}
	if ( ! empty( $image ) ) {
		$image = $before . $image . $after;
		if ( $print ) {
			print $image;
		}
		else {
			return $image;
		}
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
 * @see       _nighthawk_label() for full documentation.
 *
 * @return    string
 *
 * @since     1.0
 * @alter     1.1
 */
function nighthawk_post_label_singular() {
	$label  = '';
	$labels = _nighthawk_label();
	if ( isset( $labels[0] ) ) {
		$label = $labels[0];
	}
	return $label;
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
 * @see       _nighthawk_label() for full documentation.
 *
 * @return    string
 *
 * @since     1.0
 * @alter     1.1
 */
function nighthawk_post_label_plural() {
	$label  = '';
	$labels = _nighthawk_label();
	if ( isset( $labels[1] ) ) {
		$label = $labels[1];
	}
	return $label;
}

/**
 * Subscribe to comments checkbox.
 *
 * @return    string
 *
 * @access    public
 * @since     1.1
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
 * @since     1.1
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

/**#@-*/

/**#@+
 * Private Functions.
 *
 * The functions defined below are deemed to be private
 * meaning that they should not be used in any template file for
 * any reason. They are mainly callbacks for WordPress core functions,
 * actions and filters. These functions may or may not be presnt in
 * future releases of the Nighthawk theme. If you feel that you
 * absolutely need to use one of them it is suggested that you
 * copy the full function into your child theme's functions.php file
 * and rename it. This will ensure that it always exists in your
 * installation regardless of how Nighthawk changes.
 *
 * Functions are roughly defined in the order that
 * they would be called during a template request.
 *
 * @access    private
 */

function _nighthawk_google_fonts() {
	wp_enqueue_style( 'nighthawk-cabin', 'http://fonts.googleapis.com/css?family=Cabin:regular,regularitalic,bold,bolditalic', array(), NIGHTHAWK_VERSION );
}

/**
 * Post Label.
 *
 * Returns a noun representing the type or format of the global
 * post object. This function is used internally by the
 * nighthawk_entry_meta_taxonomy() function to create a sentence much
 * like the following: "This Status Update is filed under News."
 * where "Status Update" is the post label and "News" is the category.
 *
 * A "post label" can be one of three things:'
 * post format, custom post_type label or the mime type of an attachment.
 *
 * For "posts" having a post format, a string representing the format will be used.
 * If no format has been defined (assumung "standard" post format) This function
 * will use the term "post".
 *
 * For all other post_types, Nighthawk will use the values defined in
 * the post_type's "labels" array for singular and plural values.
 *
 * The output of this function may be extended by using the built-in filters:
 *
 * 'nighthawk_post_label_single' and 'nighthawk_post_label_plural'
 *
 * @return    array     Index "0" is the singular form while index "1" is the plural form.
 *
 * @access    private
 * @since     1.1
 */
function _nighthawk_label() {

	static $cache = array();

	$cache_id = 0;

	if ( is_tax( 'post_format' ) ) {
		global $wp_query;
		$term = get_term( $wp_query->get_queried_object(), 'post_format' );
		if ( isset( $term->slug ) ) {
			$cache_id = str_replace( 'post-format-', '', $term->slug );
		}
	}
	else if ( is_post_type_archive() ) {
		global $wp_query;
		$obj = $wp_query->get_queried_object();
		if ( isset( $obj->post_type ) ) {
			$cache_id = $obj->post_type;
		}
	}
	else {
		$cache_id = get_the_ID();
	}

	if ( isset( $cache[$cache_id] ) ) {
		return $cache[$cache_id];
	}

	$output = apply_filters( 'nighthawk_post_label_default', _nx_noop( 'entry', 'entries', 'post label' ) );

	$post_type = get_post_type();

	if ( 'post' == $post_type || is_tax( 'post_format' ) ) {
		$output = _nighthawk_label_post();
	}
	else if ( 'page' == $post_type ) {
		$output = apply_filters( 'nighthawk_post_label_page', _nx_noop( 'page', 'pages', 'post label' ) );
	}
	else if ( 'attachment' == $post_type ) {
		$output = _nighthawk_label_attachment();
	}
	else {
		$output = _nighthawk_label_custom_post_type( $post_type );
	}

	$cache[$cache_id] = $output;

	return $cache[$cache_id];
}

/**
 * Label for posts.
 *
 * @access    private
 * @since     1.1
 */
function _nighthawk_label_post( $post_format = null ) {

	$output = apply_filters( 'nighthawk_post_label_default', _nx_noop( 'post', 'posts', 'post label' ) );

	$post_format_strings = array(
		''        => $output,
		'aside'   => _nx_noop( 'aside',           'asides',           'post label' ),
		'audio'   => _nx_noop( 'audio file',      'audio files',      'post label' ),
		'chat'    => _nx_noop( 'chat transcript', 'chat transcripts', 'post label' ),
		'gallery' => _nx_noop( 'gallery',         'galleries',        'post label' ),
		'image'   => _nx_noop( 'image',           'images',           'post label' ),
		'link'    => _nx_noop( 'link',            'links',            'post label' ),
		'quote'   => _nx_noop( 'quote',           'quotes',           'post label' ),
		'status'  => _nx_noop( 'status update',   'status updates',   'post label' ),
		'video'   => _nx_noop( 'video',           'videos',           'post label' )
		);

	if ( empty( $post_format ) ) {
		$post_format = get_post_format();
	}

	if ( isset( $post_format_strings[$post_format] ) ) {
		$output = $post_format_strings[$post_format];
	}

	return apply_filters( 'nighthawk_post_label_archive_post_format', $output, $post_format );
}

/**
 * Label for attachments.
 *
 * @access    private
 * @since     1.1
 */
function _nighthawk_label_attachment() {
	$mime = 'file';
	$mime_strings = array(
		'file'        => _nx_noop( 'file',        'files',        'post label' ),
		'image'       => _nx_noop( 'image',       'images',       'post label' ),
		'icon'        => _nx_noop( 'icon',        'icons',        'post label' ),
		'zip'         => _nx_noop( 'zip archive', 'zip archives', 'post label' ),
		'doc'         => _nx_noop( 'document',    'documents',    'post label' ),
		'pdf'         => _nx_noop( 'PDF',         'PDFs',         'post label' ),
		'spreadsheet' => _nx_noop( 'spreadsheet', 'spreadsheets', 'post label' ),
		'video'       => _nx_noop( 'video',       'videos',       'post label' ),
		);

	$post_mime_type = get_post_mime_type();

	if ( in_array( $post_mime_type, array( 'image/jpeg', 'image/gif', 'image/png', 'image/bmp', 'image/tiff' ) ) ) {
		$mime = 'image';
	}
	else if ( 'image/x-icon' == $post_mime_type ) {
		$mime = 'icon';
	}
	else if ( 'application/zip' == $post_mime_type ) {
		$mime = 'zip';
	}
	else if ( in_array( $post_mime_type, array( 'application/msword', 'application/vnd.oasis.opendocument.text' ) ) ) {
		$mime = 'doc';
	}
	else if ( 'application/pdf' == $post_mime_type ) {
		$mime = 'pdf';
	}
	else if ( in_array( $post_mime_type, array( 'application/vnd.ms-excel', 'application/vnd.oasis.opendocument.spreadsheet' ) ) ) {
		$mime = 'spreadsheet';
	}
	else if ( in_array( $post_mime_type, array( 'video/asf', 'video/avi', 'video/divx', 'video/x-flv', 'video/quicktime', 'video/mpeg', 'video/mp4', 'video/ogg', 'video/x-matroska' ) ) ) {
		$mime = 'video';
	}

	return apply_filters( 'nighthawk_post_label_attachment', $mime_strings[$mime], $post_mime_type );
}

/**
 * Label for custom post type objects.
 *
 * @access    private
 * @since     1.1
 */
function _nighthawk_label_custom_post_type( $post_type = null ) {

	$output = _nx_noop( 'entry', 'entries', 'post label' );

	if ( empty( $post_type ) ) {
		$post_type = get_post_type();
	}

	$post_type_object = get_post_type_object( $post_type );

	if ( isset( $post_type_object->labels->singular_name ) && ! empty( $post_type_object->labels->singular_name ) ) {
		$output[0]        = $post_type_object->labels->singular_name;
		$output['single'] = $post_type_object->labels->singular_name;
	}
	if ( isset( $post_type_object->labels->name ) && ! empty( $post_type_object->labels->name ) ) {
		$output[1]        = $post_type_object->labels->name;
		$output['plural'] = $post_type_object->labels->name;
	}

	return $output;
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
		define( 'HEADER_IMAGE', get_template_directory_uri() . '/images/nighthawk.png' );
	}
	if ( ! defined( 'HEADER_IMAGE_WIDTH' ) ) {
		define( 'HEADER_IMAGE_WIDTH', 240 );
	}
	if ( ! defined( 'HEADER_IMAGE_HEIGHT' ) ) {
		define( 'HEADER_IMAGE_HEIGHT', 60 );
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
	$background_color = get_theme_mod( 'background_color', '375876' );
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
 * Body Class Filter.
 *
 * Apply custom css classes to the body tag.
 *
 * <ul>
 * <li>Adds the "many" class to all views containing multiple entries.</li>
 * <li>Adds the "singular" class to all views containing one entry.</li>
 * </ul>
 *
 * @param     array     Classes for the body tag.
 * @return    array     Modified classes for the body tag.
 *
 * @access    private
 * @since     1.0
 */
function _nighthawk_body_class( $classes ) {
	if ( in_array( 'blog', $classes ) || in_array( 'archive', $classes ) ) {
		$classes[] = 'many';
	}
	if ( is_single() || is_page() ) {
		$classes[] = 'singular';
	}
	return array_unique( $classes );
}

/**
 * "Entry" Post Class.
 *
 * Add a class of "entry" to the list generated by post_class().
 *
 * @param     array     All classes for the post container.
 * @return    array     All classes for the post container + entry.
 *
 * @access    private
 * @since     1.0
 */
function _nighthawk_post_class_entry( $classes ) {
	if ( ! in_array( 'entry', $classes ) ) {
		$classes[] = 'entry';
	}
	return array_unique( $classes );
}

/**
 * Featured Image Post Class.
 *
 * Add a class of 'has-featured-image' to posts having a featured image.
 *
 * @param     array     All classes for the post container.
 * @return    array     Modified classes for the post container.
 *
 * @access    private
 * @since     1.0
 */
function _nighthawk_post_class_featured( $classes ) {
	$featured_image = get_the_post_thumbnail();
	if ( ! empty( $featured_image ) ) {
		$classes[] = 'has-featured-image';
	}
	return array_unique( $classes );
}

/**
 * Featured Image: Gallery format.
 *
 * Use the first image attachment for the featured image in
 * archive views. This function should respect a user's
 * choice to assign an image via the Featured Image
 * meta box. If $html has any value at all, this function
 * does nothing.
 *
 * @param     string    Thumbnail html or empty string.
 * @return    string    HTML img tag to the first attached for posts with the gallery format in all archives, value of $html otherwise.
 *
 * @access    private
 * @since     1.0
 */
function _nighthawk_featured_image_first_attachment( $html ) {
	if ( 'gallery' == get_post_format() && ! is_single() && empty( $html ) ) {
		$images = get_children( array(
			'post_parent'    => get_the_ID(),
			'post_type'      => 'attachment',
			'post_mime_type' => 'image',
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'numberposts'    => 1
			) );
		if ( $images ) {
			$image = array_shift( $images );
			$html = wp_get_attachment_image( $image->ID, 'thumbnail' );
		}
	}
	return $html;
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
 * Toggle wpautop for search excerpts.
 *
 * Search results have special formatting that is
 * not conducive to the_excerpt() enclosing its
 * output in paragraph tags. This function will
 * remove this filter at the start of loop-search.php
 * and reinstate it after the loop has completed.
 *
 * This function will do absolutely nothing in every
 * other case.
 *
 * @return    void
 *
 * @access    private
 * @since     1.1
 */
function _nighthawk_excerpt_search_toggle_wpautop() {
	$hook = current_filter();
	if ( 'nighthawk_loop_search_start' == $hook ) {
		remove_filter( 'the_excerpt', 'wpautop' );
	}
	else if ( 'nighthawk_loop_search_end' == $hook ) {
		add_filter( 'the_excerpt', 'wpautop' );
	}
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
		print "\n\n\n\n" . '<li id="comment-'; comment_ID(); print '" '; comment_class(); print '>';
		if ( 0 === (int) $comment->comment_approved ) {
			print esc_html__( 'Your comment is awaiting moderation.', 'nighthawk' );
		}
		else {
			$avatar = get_avatar( $comment, 100 );
			print "\n" . '<div class="comment-head">';
			print "\n" . $avatar;
			print "\n" . '<span class="comment-author">' . get_comment_author_link( $comment->comment_ID ) . '</span>';
			print "\n" . '<span class="comment-meta">';

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
			print '</span>';
			print "\n" . '<div style="clear:both"></div>';
			print "\n" . '</div>';
			print "\n" . '<div class="comment-content">'; comment_text(); print '</div>';
		}
	}
	else {
		print '<li class="trackback">';
		comment_author_link();
		if ( current_user_can( 'edit_comment', $comment->comment_ID ) ) {
			print "\n" . '<span class="comment-edit"> <a href="' . esc_url( get_edit_comment_link( $comment->comment_ID ) ) . '">' . esc_html__( 'Edit', 'nighthawk' ) . '</a></span>';
		}
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
	_nighthawk_control_boolean( 'nighthawk_display_site_title', esc_html__( 'Display site title.', 'nighthawk' ), get_theme_mod( 'nighthawk_display_site_title', 0 ) );
	_nighthawk_control_boolean( 'nighthawk_display_tagline', esc_html__( 'Display tagline.', 'nighthawk' ), get_theme_mod( 'nighthawk_display_tagline', 0 ) );
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
 * Search Form.
 *
 * @param     string    Default WordPress search from.
 * @return    string    Custom Search form.
 *
 * @access    private
 * @since     1.0
 */
function _nighthawk_search_form( $form ) {
	static $id = 0;
	$id++;
	$id_attr = 'search-form-' . $id;

	$form = "\n\n";
	$form.= "\n" . '<form class="bullet" role="search" method="get" action="' . get_option( 'siteurl' ) . '">';
	$form.= "\n" . '<label class="bullet-label" for="' . esc_attr( $id_attr ) . '">Search</label>';
	$form.= "\n" . '<input class="bullet-term" id="' . esc_attr( $id_attr ) . '" type="text" value="' . esc_attr( get_search_query( false ) ) . '" name="s" />';
	$form.= "\n" . '<input class="bullet-button" type="submit" value="Search" />';
	$form.= "\n" . '</form>';
	return $form;
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
 * Prepend title to content.
 *
 * Posts formatted as an "aside" and "link" will have
 * the title prepended to the content on all multiple
 * views. The title will be linked to the post's single
 * view and will have a class attribute of "entry-title".
 *
 * @param     string    Post content.
 * @return    string    Custom post content.
 *
 * @access    private
 * @since     1.0
 */
function _nighthawk_content_prepend_title( $content ) {
	if ( is_single() ) {
		return $content;
	}
	if ( post_password_required() ) {
		return $content;
	}
	$post_format = get_post_format();
	if ( in_array( $post_format, array( 'aside', 'link', 'status' ) ) ) {
		$title      = get_the_title();
		$title_attr = sprintf( __( 'Permalink to this %1$s', 'nighthawk' ), nighthawk_post_label_singular() );
		if ( ! empty( $title ) ) {
			$content = '<a class="post-title" title="' . esc_attr( $title_attr ) . '" href="' . esc_url( get_permalink() )  . '">' . get_the_title() . '</a> ' . esc_html__( '&#8210;', 'nighthawk' ) . ' ' . $content;
		}
	}
	return $content;
}

/**
 * Append permalink to content.
 *
 * In cases where a post does not have a title,
 * a link will be appended to the post content.
 * This link will have a class attribute of "auto-link".
 *
 * @param     string    Post content.
 * @return    string    Custom post content.
 *
 * @access    private
 * @since     1.0
 */
function _nighthawk_content_append_link( $content ) {
	if ( is_single() ) {
		return $content;
	}
	$link = 'class="more-link"';
	if ( false !== strpos( $content, $link ) ) {
		return $content;
	}
	$title = get_the_title();
	if ( empty( $title ) ) {
		$content .= ' <a class="auto-link" title="' . sprintf( esc_attr__( 'Permalink to this %1$s', 'nighthawk' ), nighthawk_post_label_singular() ) . '" href="' . esc_url( get_permalink() )  . '">' . esc_html__( 'link', 'nighthawk' ) . '</a>';
	}
	return $content;
}

/**
 * Append edit link to content.
 *
 * Certain post formats (aside, link and status)
 * do not display meta information at the bottom
 * of the entry box. While this makes them more
 * compact, it also removes the helpful edit link.
 * This function will add the link inside the_content
 * before wpautop() fires and after the [link] link
 * is appended.
 *
 * @param     string    Post content.
 * @return    string    Custom post content.
 *
 * @access    private
 * @since     1.1
 */
function _nighthawk_content_append_link_edit( $content ) {
	if ( is_single() ) {
		return $content;
	}
	$url = get_edit_post_link();
	if ( empty( $url ) ) {
		return $content;
	}
	$format = get_post_format();
	if ( in_array( $format, array( 'aside', 'link' ) ) ) {
		$content .= ' <a class="post-edit-link auto-link" href="' . esc_url( $url ) . '" title="' . sprintf( esc_attr__( 'Edit this %1$s', 'nighthawk' ), nighthawk_post_label_singular() ) . '">' . esc_html__( 'edit', 'nighthawk' ) . '</a>';
	}
	return $content;
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
 * @since     1.1
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
		$first = esc_html__( 'You have not defined a navigation menu for this theme location.' );

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
 * @since     1.1
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
 * @since     1.1
 */
function _nighthawk_edit_post_link( $html, $ID ) {
	return '<a class="post-edit-link" href="' . esc_url( get_edit_post_link( $ID ) ) . '" title="' . sprintf( esc_attr__( 'Edit this %1$s', 'nighthawk' ), nighthawk_post_label_singular() ) . '">' . esc_html( wp_strip_all_tags( $html ) ) . '</a>';
}
/**#@-*/