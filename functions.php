<?php
/**
 * Functions
 *
 * @package      Ghostbird
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        1.0
 * 
 * BEFORE 1.0 RELEASE
 * @todo Style global tables.
 * @todo Test "big" tag across browsers - not sure about positioning here :)
 * @todo Completely test and rewrite all examples in docs or remove if feeling lazy ;)
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
 * @todo Make dialog colors match new theme colors.
 * @todo Rename .heading-action class everywhere.
 * @todo Left margin on blockquotes.
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

define( 'GHOSTBIRD_VERSION', '0.9.2' );
 
/**
 * Theme Setup
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

	global $content_width;
	if ( ! isset( $content_width ) ) {
		$content_width = 608;
	}

	$settings = ghostbird_get_settings();

	load_theme_textdomain( 'ghostbird', get_template_directory() . '/languages' );
	
	add_theme_support( 'menus' );
	add_theme_support( 'post-formats', array( 'aside', 'gallery', 'status' ) );
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
	add_filter( 'post_class',                 '_ghostbird_post_class_entry' );
	add_filter( 'post_class',                 '_ghostbird_post_class_featured' );
	add_filter( 'post_class',                 '_ghostbird_post_class_more' );
	add_filter( 'post_thumbnail_html',        '_ghostbird_featured_image_first_attachment' );
	add_filter( 'post_thumbnail_html',        '_ghostbird_featured_image_avatar' );
	add_action( 'the_content',                '_ghostbird_related_images' );
	add_filter( 'excerpt_more',               '_ghostbird_excerpt_more_auto' );
	add_filter( 'get_the_excerpt',            '_ghostbird_excerpt_more_custom' );
	add_filter( 'embed_oembed_html',          '_ghostbird_oembed_dataparse', 10, 4 );
	add_filter( 'embed_googlevideo',          '_ghostbird_oembed_dataparse', 10, 2 );
	add_action( 'widget_title',               '_ghostbird_calendar_title', 10, 3 );

	/* Settings section. */
	add_action( 'admin_menu', '_ghostbird_settings_page_link' );
	add_action( 'admin_init', '_ghostbird_admin_init' );

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

/**#@+
 * Public Functions
 *
 * Any function defined in this file may be used
 * freely in appropriate template files. Please see
 * each function's documentation for intended usage.
 *
 * Functions are roughly defined in the order that
 * they would be called during a template request.
 *
 * @access    public
 */

/**
 * Print the logo.
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
function ghostbird_logo( $before = '', $after = '' ) {
	$url = get_header_image();
	if ( ! empty( $url ) ) {
		$img = '<img src="' . $url . '" width="' . HEADER_IMAGE_WIDTH . '" height="' . HEADER_IMAGE_HEIGHT . '" alt="' . esc_attr( get_bloginfo( 'blogname' ) ) . '">';
		if ( ! is_front_page() || is_paged() ) {
			$img = '<a href="' . home_url() . '">' . $img . '</a>';
		}
		print "\n" . $before . $img . $after;
	}
}

/**
 * Print the site's title.
 *
 * Value is defined by administrator via
 * Settings -> General -> Site Title.
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
function ghostbird_site_title( $before = '', $after = '' ) {
	$text = get_bloginfo( 'blogname' );
	if ( ! empty( $text ) ) {
		if ( ! is_front_page() || is_paged() ) {
			$text = '<a href="' . home_url() . '">' . $text . '</a>';
		}
		print "\n" . $before . $text . $after;
	}
}

/**
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
function ghostbird_tagline( $before = '', $after = '' ) {
	$text = get_bloginfo( 'description' );
	if ( ! empty( $text ) ) {
		print "\n" . $before . $text . $after;
	}
}

/**
 * Generate a title for each view.
 *
 * @param     string    Text to include before the title.
 * @param     string    Text to include after the title.
 * @param     bool      Should this function print? Defaults to true.
 * @return    string/void
 *
 * @since     1.0
 */
function ghostbird_title( $before = '', $after = '', $print = true ) {
	$o = '';
	$url = '';
	$post_type_qv = get_query_var( 'post_type' );
	if ( 'page' == $post_type_qv ) {
		$o = __( 'Pages', 'ghostbird' );
		if ( is_paged() ) {
			$url = add_query_arg( 'post_type', 'page', trailingslashit( home_url() ) );
			$o = apply_filters( 'ghostbird_title_timeline_paged', '<a href="' . esc_url( $url ) . '">' . $o . '</a>' );
		}
	}
	else if ( is_home() ) {
		$o = apply_filters( 'ghostbird_title_timeline', __( 'Timeline', 'ghostbird' ) );
		if ( is_paged() ) {
			$o = apply_filters( 'ghostbird_title_timeline_paged', '<a href="' . esc_url( home_url() ) . '">' . $o . '</a>' );
			$o.= ' <span class="heading-action">Page ' . (int) get_query_var( 'paged' ) . '<span>';
		}
	}
	else if ( is_singular() ) {
		global $post;
		$title = get_the_title();
		$format = get_post_format();
		if ( isset( $post->post_type ) && 'post' == $post->post_type && empty( $title ) ) {
			$title = ghostbird_post_label();
		}
		$title = apply_filters( "ghostbird_title_singular_{$post->post_type}", $title );
		$o = apply_filters( 'the_title', $title );
	}
	else if ( is_tax() || is_category() || is_tag() ) {
		global $wp_query;
		$term = $wp_query->get_queried_object();
		if ( isset( $term->name ) && isset( $term->taxonomy ) && isset( $term->slug ) ) {
			if ( 'post_format' == $term->taxonomy ) {
				$term->name = ghostbird_post_label( false );
			}
			$o = apply_filters( "ghostbird_title_taxonomy_{$term->taxonomy}", $term->name );
			if ( is_paged() ) {
				$url = get_term_link( $term, $term->taxonomy );
				$o = apply_filters( 'ghostbird_title_timeline_paged', '<a href="' . esc_url( $url ) . '">' . $o . '</a>' );
			}
		}
	}
	else if ( is_search() ) {
		$o = apply_filters( 'ghostbird_title_search', __( 'Search Results', 'ghostbird' ) );
	}
	else if ( is_author() ) {
		global $wp_query;
		$author = $wp_query->get_queried_object();
		if ( isset( $author->display_name ) ) {
			$o = sprintf( __( 'All entries by %1$s', 'ghostbird' ), $author->display_name );
			$o = apply_filters( 'ghostbird_title_author', $o, $author );
		}
	}
	else if ( is_day() ) {
		$o = sprintf( __( 'Entries from %1$s', 'ghostbird' ), get_the_date() );
		$o = apply_filters( 'ghostbird_title_day', $o );
	}
	else if ( is_month() ) {
		$o = sprintf( __( 'Entries from %1$s', 'ghostbird' ), get_the_date( 'F, Y' ) );
		$o = apply_filters( 'ghostbird_title_month_year', $o );
	}
	else if ( is_year() ) {
		$o = sprintf( __( 'Entries from %1$s', 'ghostbird' ), get_the_date( 'Y' ) );
		$o = apply_filters( 'ghostbird_title_year', $o );
	}
	else if ( is_post_type_archive() ) {
		$o = post_type_archive_title( '', false );
		global $wp_query;
		$post_type = $wp_query->get_queried_object();
		if ( isset( $post_type->name ) && is_paged() ) {
			$url = get_post_type_archive_link( $post_type->name );
			$o = apply_filters( 'ghostbird_title_timeline_paged', '<a href="' . esc_url( $url ) . '">' . $o . '</a>' );
		}
	}

	$o = "\n" . $before . apply_filters( 'ghostbird-title-text', $o, $url ) . $after;

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
 * Display the author of an entry in singular views.
 *
 * @param     string    Text to print before.
 * @param     string    Text to print after.
 * @return    void
 *
 * @since     1.0
 */
function ghostbird_byline( $before = '', $after = '' ) {
	$byline = '';
	$author_name = '';
	if ( is_singular() && ! is_attachment() ) {
		$author_name = get_the_author();
		/* get_the_author() only works inside the loop. Need to do manual labor if ghostbird_byline() is used outside the loop. */
		if ( empty( $author_name ) ) {
			global $posts;
			if ( isset( $posts[0]->post_author ) ) {
				$author = get_userdata( $posts[0]->post_author );
				if ( isset( $author->display_name ) ) {
					$author_name = esc_html( $author->display_name );
				}
			}
		}
	}
	if ( ! empty( $author_name ) ) {
		$byline = sprintf( __( 'By %1$s', 'ghostbird' ), $author_name );
	}
	$byline = apply_filters( 'ghostbird-byline', $byline, $author_name );
	if ( ! empty( $byline ) ) {
		print "\n" . $before . $byline . $after;
	}
}

/**
 * Summary.
 *
 * This function is will look for a summary for the
 * queried object. There are currently 3 supported summary
 * types:
 *
 * The first is for taxonomy term archives. If the queried
 * term has a value in its description field, this will be used 
 * as the summary.
 *
 * The second is for pages. Ghostbird enables
 * the exceprt field for pages which is intended to be used as a 
 * summary for the page.
 *
 * The third is for author archives. If the queried author has
 * filled out the "Biographical Info" portion of their profile
 * this data will be used as the summary.
 *
 * Child themes and plugins should use the 'ghostbird_summary'
 * filter to add custom data to this function. For instance, if you would
 * like to add a summary for a custom post_type archive view, you may
 * want to use code similar to:
 *
 * <code>
 * function mfields_ghostbird_summary( $summary ) {
 *     if ( is_post_type_archive( 'mfields_bookmark' ) ) {
 *         $summary = '<p>I created this section to store webpages that I have read, found interesting or may need to reference in the future. Not all bookmarks found here directly pertain to WordPress.</p>';
 *     }
 *     return $summary;
 * }
 * add_filter( 'ghostbird_summary', 'mfields_ghostbird_summary' );
 * </code>
 *
 * @param     string         Text to print before the summary.
 * @param     string         Text to print after the summary.
 * @param     bool           True to print the summary, false to return it as a string.
 * @return    void/string
 *
 * @since     1.0
 */
function ghostbird_summary( $before = '', $after = '', $print = true ) {
	$summary = '';
	if ( is_category() || is_tag() || is_tax() ) {
		$summary = term_description();
	}
	else if ( is_page() && has_excerpt() ) {
		$summary = apply_filters( 'the_excerpt', get_the_excerpt() );
	}
	else if ( is_author() ) {
		global $wp_query;
		$summary = get_the_author_meta( 'description', $wp_query->get_queried_object_id() );
	}
	$summary = apply_filters( 'ghostbird_summary', $summary );
	if ( ! empty( $summary ) ) {
		$summary = "\n" . $before . $summary . $after;
		if ( ! $print ) {
			return $summary;
		}
		print $summary;
	}
}

/**
 * Print meta information pertain to the current view.
 *
 * @param     string    Text to print before.
 * @param     string    Text to print after.
 * @return    void
 *
 * @todo      localize
 * @since     1.0
 */
function ghostbird_intro_meta( $before = '', $after = '' ) {
	$sentence = '';

	global $wp_query;

	$total = 0;
	if ( isset( $wp_query->found_posts ) ) {
		$total = (int) $wp_query->found_posts;
	}
	if ( is_attachment() ) {
		$parent = false;
		$id = get_the_ID();
		$attachment = get_post( $id );
		if ( isset( $attachment->post_parent ) ) {
			$parent = get_post( $attachment->post_parent );
		}
		if ( isset( $parent->ID ) && isset( $parent->post_title ) ) {
			$parent_link = '<a href="' . get_permalink( $parent->ID ) . '">' . apply_filters( 'the_title', $parent->post_title ) . '</a>';
			$sentence = 'This file is attached to ' . $parent_link;
			if ( isset( $attachment->post_mime_type ) && 0 === strpos( $attachment->post_mime_type, 'image' ) ) {
				$sentence = 'This image is attached to ' . $parent_link;
				if ( 'gallery' == get_post_format( $parent->ID ) ) {
					$sentence = 'This image is part of the gallery titled ' . $parent_link . '.';
				}
			}
		}
	}
	else if ( is_category() || is_tag() || is_tax() ) {
		$term = $wp_query->get_queried_object();
		if ( isset( $term->name ) && isset( $term->taxonomy ) && isset( $term->slug ) ) {
			$taxonomy = get_taxonomy( $term->taxonomy );
			if ( isset( $taxonomy->labels->singular_name ) ) {
				$taxonomy_name = strtolower( $taxonomy->labels->singular_name );
			}

			$term_name = strtolower( $term->name );

			switch( $term->taxonomy ) {
				case 'category' :
					$feed_title = 'Get updates when a new entry is added to the ' . $term_name . ' category.';
					$sentence = 'There are ' . $total . ' entries in this ' . $taxonomy_name . '.';
					if ( 1 == $total ) {
						$sentence = 'There is 1 entry in this ' . $taxonomy_name . '.';
					}
					break;
				case 'post_tag' :
					$feed_title = 'Get updates when a new entry is tagged with ' . $term_name;
					$sentence = $total . ' entries have been tagged with the term <em>&#8220;' . $term_name . '&#8221;</em>.';
					if ( 1 == $total ) {
						$sentence = '1 entry has been tagged with the term <em>&#8220;' . $term_name . '&#8221;</em>.';
					}
					break;
				case 'post_format' :
					$feed_title = 'Get updates when a new ' . ghostbird_post_label() . ' is published.';
					$sentence = 'This site contains ' . $total . ' ' . ghostbird_post_label( false ) . '.';
					if ( 1 == $total ) {
						$sentence = 'This site contains one ' . ghostbird_post_label() . '.';
					}
					break;
				default :
					$feed_title = sprintf( esc_attr__( 'Subscribe to this %1$s', 'ghostbird' ), $taxonomy_name );
					$sentence = $total . ' entries are associated with the term <em>&#8220;' . $term_name . '&#8221;</em>.';
					if ( 1 == $total ) {
						$sentence = '1 entry is associated with the term <em>&#8220;' . $term_name . '&#8221;</em>.';
					}
					break;
			}
			$feed_url = esc_url( get_term_feed_link( $term->term_id, $term->taxonomy ) );
		}
	}
	else if ( is_post_type_archive() ) {
		$post_type = $wp_query->get_queried_object();
		$sentence = 'There are ' . $total . ' ' . strtolower( $post_type->labels->name ) . '.';
		if ( 1 == $total ) {
			$sentence = 'There is 1 ' . strtolower( $post_type->labels->singular_name ) . '.';
		}
		$feed_url = esc_url( get_post_type_archive_feed_link( $post_type->name ) );
		$feed_title = 'Get updates when new ' . strtolower( $post_type->labels->name ) . ' are published.';
	}
	if ( ! empty( $feed_url ) ) {
		$sentence.= ' <span class="subscribe"><a href="' . $feed_url . '" title="' . $feed_title . '">' . __( 'Subscribe', 'ghostbird' ) . '</a></span>';
	}
	if ( ! empty( $sentence ) ) {
		print "\n" . $before . $sentence . $after;
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
function ghostbird_continue_reading_link() {
	return ' <a href="'. get_permalink() . '">' . __( 'Continue reading', 'ghostbird' ) . '</a>';
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
 */
function ghostbird_entry_meta_date() {

	print "\n" . '<p>';

	/* Date + Permalink */
	$format = get_post_format();
	if ( empty( $format ) ) {
		$format = 'post';
	}
	$title_attr  = sprintf( esc_attr__( 'Permanent link to this %1$s', 'ghostbird' ), $format );
	$date_format = sprintf( __( '%1$s \a\t %2$s', 'ghostbird' ), get_option( 'date_format' ), get_option( 'time_format' ) );
	$datestamp = '<a class="datetime" href="' . get_permalink() . '" title="' . $title_attr . '">' . get_the_time( $date_format ) . '</a>';
	if ( is_single() ) {
		$datestamp =  '<span class="datetime" title="' . $title_attr . '">' . get_the_time( $date_format ) . '</span>';
	}

	printf( __( 'Posted on %1$s', 'ghostbird' ), $datestamp );

	/* Comments */
	if ( ! is_singular() && ( ( comments_open() && ! post_password_required() ) || 0 < get_comments_number() ) ) {
		print ' ';
		$comment = _x( 'Comment', 'verb', 'ghostbird' );
		print '<span class="comment-link">';
		comments_popup_link( $comment, $comment, $comment, '', '' );
		print '</span>';
	}

	/* Edit link */
	edit_post_link( __( 'Edit', 'ghostbird' ), ' <span class="post-edit">', '</span>' );

	print '</p>';
}

/**
 * Entry Taxonomy Meta
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
 * @return    void
 *
 * @since     1.0
 */
function ghostbird_entry_meta_taxonomy() {
	$sentence   = '';
	$label      = ghostbird_post_label();
	$label_url  = get_post_format_link( get_post_format() );

	$sentence = apply_filters( 'ghostbird_entry_meta_taxonomy', $sentence, $label, $label_url );
	if ( ! empty( $sentence ) ) {
		print $sentence;
		return;
	}

	$post_tags  = get_the_tag_list( '', ', ' );
	$categories = get_the_category_list( ', ' );

	if ( ! empty( $label ) && ! empty( $label_url ) ) {
		$plural = ghostbird_post_label( false );
		$title = '';
		if ( ! empty( $plural ) ) {
			$title = sprintf( esc_attr__( 'View all %1$s', 'ghostbird' ), strtolower( $plural ) );
			$title = ' title="' . $title . '"';
		}
		$label = '<a href="' . esc_url( $label_url ) . '"' . $title . '>' . esc_html( $label ) . '</a>';
	}

	if ( ! empty( $label ) ) {
		if( ! empty( $categories ) && ! empty( $post_tags ) ) {
			$sentence = 'This ' . $label . ' is filed under ' . $categories . ' and tagged ' . $post_tags . '.';
		}
		else if ( ! empty( $categories ) ) {
			$sentence = 'This ' . $label . ' is filed under ' . $categories . '.';
		}
		else if ( ! empty( $post_tags ) ) {
			$sentence = 'This ' . $label . ' is tagged ' . $post_tags . '.';
		}
	}
	else {
		if ( ! empty( $categories ) && ! empty( $post_tags ) ) {
			$sentence = 'Filed under ' . $categories . ' and tagged ' . $post_tags . '.';
		}
		else if ( ! empty( $categories ) ) {
			$sentence = 'Filed under ' . $categories . '.';
		}
		else if ( ! empty( $post_tags ) ) {
			$sentence = 'Tagged ' . $post_tags . '.';
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
function ghostbird_paged_nav( $before = '', $after = '' ) {
	$clear = '<div class="clear"></div>';
	if ( is_singular() ) {
		print $before;
		previous_post_link( '<div class="older-posts">%link</div>', __( 'Next <span>&raquo;</span>', 'ghostbird' ) );
		next_post_link( '<div class="newer-posts">%link</div>', __( '<span>&laquo;</span> Back', 'ghostbird' ) );
		print $clear . $after;
	}
	else {
		$next = get_next_posts_link( __( 'More <span>&raquo;</span>', 'ghostbird' ) );
		if ( ! empty( $next ) ) {
			$next =  '<div class="more-posts">' . $next . '</div>';
		}
		$prev = get_previous_posts_link( __( '<span>&laquo;</span> Back', 'ghostbird' ) );
		if ( ! empty( $prev ) ) {
			$prev = '<div class="back-posts">' . $prev . '</div>';
		}
		if ( ! empty( $prev ) || ! empty( $next ) ) {
			print "\n" . $before . $prev . $next . $clear . $after;
		}
	}
}

/**
 * Print the author bio if one exists.
 *
 * Author's avatar will not be printed on status posts.
 *
 * @return    void
 *
 * @since     1.0
 */
function ghostbird_author( $before = '', $after = '' ) {
	if ( get_the_author_meta( 'description' ) ) {
		$size   = apply_filters( 'ghostbird_author_size', 60 );
		$class  = '';
		$avatar = get_avatar( get_the_author_meta( 'user_email' ), $size );
		if ( ! empty( $avatar ) && 'status' != get_post_format() ) {
			$class.= ' class="has-avatar"';
			$avatar = "\n" . '<div class="author-avatar">' . $avatar . '</div>';
		}
		else {
			$avatar = '';
		}
		print "\n\n";
		print "\n" . '<div id="author-box"' . $class . '>';
		print $avatar;
		print "\n" . '<h2 class="author-name">' . sprintf( esc_attr__( 'About %s', 'ghostbird' ), get_the_author() ) . '</h2>';
		print "\n" . '<div class="author-bio">' . get_the_author_meta( 'description' ) . '</div>';
		print "\n" . '</div><!--author-box-->';
	}
}

/**
 * Post Label.
 *
 * Returns a noun representing the type or format of the global
 * post object. This function is used internally by the
 * ghostbird_entry_meta_taxonomy() function to create a sentence much
 * like the following: "This Status Update is filed under News."
 * where "Status Update" is the post label and "News" is the category.
 *
 * A "post label" can be one of two things: Post Format or Custom Post Type Label.
 *
 * For "posts" having a post format, a string representing the format will be used.
 * If no format has been defined (assumung "standard" post format) This function
 * will use the term "entry".
 *
 * Even though Ghostbird does not support all available post_formats
 * any blog may have posts associated with unsupported formats.
 * This function should return a valid result for every post format
 * regardless of whether it supports it.
 *
 * For all other post_types, Ghostbird will use the values defined in
 * the post_type's "labels" array for singular and plural values.
 *
 * The output of this function may be extended by using the built-in filters:
 *
 * 'ghostbird_post_label_single' and 'ghostbird_post_label_plural'
 *
 * @param     bool      True for singular label, false for plural label.
 * @return    string    An appropriate label for the post.
 *
 * @since     1.0
 */
function ghostbird_post_label( $singular = true ) {
	$label       = '';
	$post_type   = get_post_type();
	$post_format = get_post_format();

	if ( 'post' == $post_type ) {
		switch ( $post_format ) {
			case 'aside' :
				$single = _x( 'Aside', 'post format term', 'ghostbird' );
				$plural = _x( 'Asides', 'post format term', 'ghostbird' );
				break;
			case 'audio' :
				$single = _x( 'Audio File', 'post format term', 'ghostbird' );
				$plural = _x( 'Audio Files', 'post format term', 'ghostbird' );
				break;
			case 'chat' :
				$single = _x( 'Chat Transcript', 'post format term', 'ghostbird' );
				$plural = _x( 'Chat Transcripts', 'post format term', 'ghostbird' );
				break;
			case 'gallery' :
				$single = _x( 'Gallery', 'post format term', 'ghostbird' );
				$plural = _x( 'Galleries', 'post format term', 'ghostbird' );
				break;
			case 'image' :
				$single = _x( 'Image', 'post format term', 'ghostbird' );
				$plural = _x( 'Images', 'post format term', 'ghostbird' );
				break;
			case 'link' :
				$single = _x( 'Link', 'post format term', 'ghostbird' );
				$plural = _x( 'Links', 'post format term', 'ghostbird' );
				break;
			case 'quote' :
				$single = _x( 'Quote', 'post format term', 'ghostbird' );
				$plural = _x( 'Quotes', 'post format term', 'ghostbird' );
				break;
			case 'status' :
				$single = _x( 'Status Update', 'post format term', 'ghostbird' );
				$plural = _x( 'Status Updates', 'post format term', 'ghostbird' );
				break;
			case 'video' :
				$single = _x( 'Video', 'post format term', 'ghostbird' );
				$plural = _x( 'Videos', 'post format term', 'ghostbird' );
				break;
			case '' :
			case 'standard' :
			default :
				$single = _x( 'Entry', 'post format term', 'ghostbird' );
				$plural = _x( 'Entries', 'post format term', 'ghostbird' );
				break;
		}
	}
	else {
		$post_type_object = get_post_type_object( $post_type );
		if ( isset( $post_type_object->labels->singular_name ) && ! empty( $post_type_object->labels->singular_name ) ) {
			$single = $post_type_object->labels->singular_name;
		}
		if ( isset( $post_type_object->labels->name ) && ! empty( $post_type_object->labels->name ) ) {
			$plural = $post_type_object->labels->name;
		}
	}

	$single =  apply_filters( 'ghostbird_post_label_single', $single, $post_type, $post_format );
	$plural =  apply_filters( 'ghostbird_post_label_plural', $plural, $post_type, $post_format );

	$label = $single;
	if ( ! $singular ) {
		$label = $plural;
	}

	return apply_filters( 'ghostbird_post_label', $label, $post_type, $post_format );
}

/**#@-*/

/**#@+
 * Private Functions.
 *
 * The functions defined below are deemed to be private
 * meaning that they should not be used in any template file for
 * any reason. They are mainly callbacks for WordPress core functions,
 * actions and filters. These functions may or may not be presnt in
 * future releases of the ghostbird theme. If you feel that you
 * absolutely need to use one of them it is suggested that you
 * copy the full function into your child theme's functions.php file
 * and rename it. This will ensure that it always exists in your
 * installation regardless of developments of Ghostbird.
 *
 * Functions are roughly defined in the order that
 * they would be called during a template request.
 *
 * @access    private
 */

/**
 * Calendar Widget Title
 *
 * For some reason, WordPress will print a non-breaking space
 * entity wrapped in the appropriate tags for the calendar
 * widget even if the title's value is left empty by the user.
 * This function will remove the empty heading tag.
 *
 * Note: empty values are IMPORTANT! in parameters.
 *
 * @param     string         The value of the calendar widget's title for this instance.
 * @param     n/a            n/a
 * @param     n/a            n/a
 * @return    string         Calendar widget title.
 *
 * @since     1.0
 */
function _ghostbird_calendar_title( $title = '', $instance = '', $id_base = '' ) {
	if ( 'calendar' == $id_base && '&nbsp;' == $title ) {
		$title = '';
	}
	return $title;
}

/**
 * Register Widgetized Areas
 *
 * @return    void
 *
 * @since     1.0
 */
function _ghostbird_widgets_init() {

	/* Area 1 - Left column below content. */
	register_sidebar( array(
		'name'          => __( 'Bottom 1', 'ghostbird' ),
		'id'            => 'first-footer-widget-area',
		'description'   => __( 'The first footer widget area', 'ghostbird' ),
		'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	/* Area 2 - Middle column below content. */
	register_sidebar( array(
		'name'          => __( 'Bottom 2', 'ghostbird' ),
		'id'            => 'second-footer-widget-area',
		'description'   => __( 'The second footer widget area', 'ghostbird' ),
		'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	/* Area 3, Right column bottom of content . */
	register_sidebar( array(
		'name'          => __( 'Bottom 3', 'ghostbird' ),
		'id'            => 'third-footer-widget-area',
		'description'   => __( 'The third footer widget area', 'ghostbird' ),
		'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	/* Discussion Guidelines. */
	register_sidebar( array(
		'name'          => __( 'Discussion Guidelines', 'ghostbird' ),
		'id'            => 'discussion-guidelines',
		'description'   => __( 'Add a text widget to this area and instruct your visitors how they should comment on your entries. The content of this area will display above the textarea in the comment form.', 'ghostbird' ),
		'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}

/**
 * Discussion Guidelines
 *
 * The discussion guidelines section is delivered via a widgetized
 * area. If the 'discussion-guidelines' area is active its contents
 * will be prepended to the textarea created by comment_form() in
 * comments.php.
 *
 * @return    string    The contents of all widgets assigned to the discussion-guidelines area.
 *
 * @since     1.0
 */
function _ghostbird_discussion_guidelines() {
	$widget = '';
	if ( is_active_sidebar( 'discussion-guidelines' ) ) {
		ob_start();
		dynamic_sidebar( 'discussion-guidelines' );
		$widget = ob_get_clean();
	}
	if ( ! empty( $widget ) ) {
		$toggle = apply_filters( 'ghostbird_discussion_guidelines_toggle_text', __( 'Read the Guidelines', 'ghostbird' ) );
		return <<<EOF
<div id="discussion-guidelines">
	<span id="discussion-guidelines-toggle">{$toggle}</span>
	<div id="discussion-guidelines-widgets">{$widget}</div>
</div>
EOF;
	}
}

/**
 * Configuration for enabling the WordPress custom header image feature.
 *
 * @return    void
 *
 * @since     1.0
 */
function _ghostbird_custom_image_header() {
	define( 'HEADER_TEXTCOLOR', 'b9d8f2' );
	define( 'HEADER_IMAGE', get_template_directory_uri() . '/images/ghostbird.png' );
	define( 'HEADER_IMAGE_WIDTH', 240 );
	define( 'HEADER_IMAGE_HEIGHT', 60 );
	define( 'NO_HEADER_TEXT', true );
	add_custom_image_header( '_ghostbird_custom_image_header_live', '_ghostbird_custom_image_header_admin' );
}

/**
 * CSS for displaying custom header in public views.
 *
 * @return    void
 *
 * @since     1.0
 */
function _ghostbird_custom_image_header_live() {
	print '<style>#site-name,#site-name a,#tagline{color:#' . HEADER_TEXTCOLOR . '}</style>';
}

/**
 * CSS for displaying custom header in administration views.
 *
 * @return    void
 *
 * @since     1.0
 */
function _ghostbird_custom_image_header_admin() {
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
 * @since     1.0
 */
function _ghostbird_body_class( $classes ) {
	if ( in_array( 'blog', $classes ) || in_array( 'archive', $classes ) ) {
		$classes[] = 'many';
	}
	if ( is_single() || is_page() ) {
		$classes[] = 'singular';
	}
	return array_unique( $classes );
}

/**
 * Wrap top navigation element in a div.
 *
 * In the event the wp_nav_menu() falls back to wp_page_menu() it is
 * necessary to ensure that it's output is encloded in a div with the
 * "id" attribute with a value of "menu" or "menu-bottom".
 *
 * @param     string    Unordered list html.
 * @param     array     Arguments inherited by wp_page_menu() from wp_nav_menu().
 * @return    string
 *
 * @since     1.0
 */
function _ghostbird_page_menu_wrap( $menu, $args ) {
	if ( isset( $args['container'] ) && isset( $args['container_id'] ) && 'div' == $args['container'] && ( 'menu-top' == $args['container_id'] || 'menu-bottom' == $args['container_id'] ) ) {
		return "<{$args['container']} id='{$args['container_id']}'>{$menu}</{$args['container']}>";
	}
	return $menu;
}

/**
 * Add a class of "entry" to the list generated by post_class().
 *
 * @param     array     All classes for the post container.
 * @return    array     All classes for the post container + entry.
 *
 * @since     1.0
 */
function _ghostbird_post_class_entry( $classes ) {
	if ( ! in_array( 'entry', $classes ) ) {
		$classes[] = 'entry';
	}
	return $classes;
}

/**
 * Add a class of 'more' to posts having a "Read More" tag.
 *
 * @param     array     All classes for the post container.
 * @return    array     Modified classes for the post container.
 *
 * @since     1.0
 */
function _ghostbird_post_class_more( $classes ) {
	global $post;
	if ( ( is_archive() || is_home() ) && false !== strpos( $post->post_content, '<!--more-->' ) && ! in_array( 'more', $classes ) ) {
		$classes[] = 'more';
	}
	return $classes;
}

/**
 * Add a class of 'has-featured-image' to posts having a featured image.
 *
 * @param     array     All classes for the post container.
 * @return    array     Modified classes for the post container.
 *
 * @since     1.0
 */
function _ghostbird_post_class_featured( $classes ) {
	$featured_image = get_the_post_thumbnail();
	if ( ! empty( $featured_image ) ) {
		$classes[] = 'has-featured-image';
	}
	return $classes;
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
 * @since     1.0
 */
function _ghostbird_featured_image_first_attachment( $html ) {
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
 * Featured Image: Status format.
 *
 * Use the avatar for the featured image on status posts.
 *
 * @param     string    Thumbnail html or empty string.
 * @return    string    HTML img tag to the first attached for posts with the "status" format, value of $html otherwise.
 *
 * @since     1.0
 */
function _ghostbird_featured_image_avatar( $html ) {
	if ( 'status' == get_post_format() ) {
		global $post;
		$html = get_avatar( $post->post_author, $size = '60' );
	}
	return $html;
}

/**
 * Print images related to the image being queried.
 *
 * Child themes and plugins may disable this feature
 * by using the following code:
 *
 * <code>
 * <?php remove_action( 'ghostbird_entry_end', 'ghostbird_related_images' ); ?>
 * </code>
 *
 * Code similar to the following can be used to change the title text.
 *
 * <code>
 * function mytheme_related_images_title_text() {
 *     return 'MY CUSTOM TEXT';
 * }
 * add_filter( 'ghostbird_related_images_title_text', 'mytheme_related_images_title_text' );
 * </code>
 *
 * Likewise, the size of the image can be changed with the following code.
 * It is suggested that you only use image sizes that are cropped.
 *
 * <code>
 * function mytheme_related_images_size() {
 *     return 'thumbnail';
 * }
 * add_filter( 'ghostbird_related_images_size', 'mytheme_related_images_size' );
 * </code>
 *
 * @todo      Update docs. This is now a filter for the_content.
 *
 * @return    void
 * @since     1.0
 */
function _ghostbird_related_images( $content ) {
	if ( is_attachment() ) {
		global $post;
		$type = 'image';
		$size = apply_filters( 'ghostbird_related_images_size', 'ghostbird_detail' );
		$title = apply_filters( 'ghostbird_related_images_title_text',  __( 'Related Images', 'ghostbird' ) );
		if ( isset( $post->post_mime_type ) && 0 === strpos( $post->post_mime_type, $type ) ) {
			$images = get_children( array(
				'post_parent'    => $post->post_parent,
				'post_status'    => 'inherit',
				'post_type'      => 'attachment',
				'post_mime_type' => $type,
				'exclude'        => $post->ID
				) );
			if ( ! empty( $images ) ) {
				$content.= "\n" . '<h2>' . $title . '</h2>';
				$content.= "\n" . '<ul id="related-images">';
				foreach ( (array) $images as $image ) {
					$content.= "\n" . '<li>' . wp_get_attachment_link( $image->ID, $size, true, false ) . '</li>';
				}
				$content.= "\n" . '</ul>';
				$content.= "\n" . '<div class="clear"></div>';
			}
		}
	}
	return $content;
}

/**
 * Excerpt More (auto).
 *
 * In cases where a post does not have an excerpt defined
 * WordPress will append the string "[...]" to a shortened
 * version of the post_content field. Ghostbird will replace
 * this string with an ellipsis followed by a link to the 
 * full post.
 *
 * This filter is attached to the 'excerpt_more' hook
 * in the _ghostbird_setup() function.
 *
 * @return    string         An ellipsis followed by a link to the single post.
 *
 * @since     1.0
 */
function _ghostbird_excerpt_more_auto( $more ) {
	return ' &hellip; ' . ghostbird_continue_reading_link();
}

/**
 * Excerpt More (custom).
 *
 * For posts that have a custom excerpt defined, WordPress
 * will show this excerpt instead of shortening the post_content.
 * Ghostbird will append a link to the post's single view to the excerpt.
 *
 * This filter is attached to the 'get_the_excerpt' hook
 * in the _ghostbird_setup() function.
 *
 * @return    string         Excerpt with a link to the post's single view.
 *
 * @since     1.0
 */
function _ghostbird_excerpt_more_custom( $excerpt ) {
	if ( has_excerpt() && ! is_attachment() && ! is_page() ) {
		$excerpt .= ghostbird_continue_reading_link();
	}
	return $excerpt;
}

/**
 * Print the author bio on single post views.
 *
 * @return    void
 *
 * @since     1.0
 */
function _ghostbird_author( $before = '', $after = '' ) {
	if ( is_single() ) {
		print ghostbird_author( $before, $after );
	}
}

/**
 * Append a link to the author's archive view to their description.
 *
 * @param     string    Author description.
 * @return    string    Filtered author description.
 *
 * @since     1.0
 */
function _ghostbird_author_link( $description ) {
	if ( ! empty( $description ) && ! is_author() ) {
		$link = "\n" . '<a href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '">' . sprintf( __( ' View all entries by %s.', 'ghostbird' ), get_the_author() ) . '</a>';
		$description.= apply_filters( 'ghostbird_author_link', $link );
	}
	return $description;
}

/**
 * Comment Date.
 *
 * Return the date and time that the current global comment
 * was submitted. This string will be linked directly to the
 * comment it represents.
 *
 * @return    string         Linked comment date.
 *
 * @since     1.0
 */
function _ghostbird_comment_date() {
	global $comment;
	return "\n" . '<a class="comment-date" href="' . get_comment_link( $comment->comment_ID ) . '"  title="' . esc_attr__( 'Direct link to this comment.', 'ghostbird' ) . '">' . sprintf( __( '%1$s at %2$s', 'ghostbird' ), get_comment_date(),  get_comment_time() ) . '</a>';
}

/**
 * Comment start.
 *
 * Prints most of a single comment.
 * @see _ghostbird_comment_end().
 *
 * @param     stdClass       Comment object.
 * @param     array          Arguments passed to wp_list_comments() merged with default values.
 * @param     int            Position of the current comment in relation to the root comment of this tree. Starts at zero.
 * @param     void
 *
 * @since     1.0
 */
function _ghostbird_comment_start( $comment, $args, $depth ) {

	$GLOBALS['comment'] = $comment;

	if ( '' == $comment->comment_type ) {
		print "\n\n\n\n" . '<li id="comment-'; comment_ID(); print '" '; comment_class(); print '>';
		if ( 0 === (int) $comment->comment_approved ) {
			print __( 'Your comment is awaiting moderation.', 'ghostbird' );
		}
		else {
			$avatar = get_avatar( $comment, 45 );
			print "\n" . '<div class="comment-head">';
			print "\n" . $avatar;
			print "\n" . '<span class="comment-author">' . get_comment_author_link( $comment->comment_ID ) . '</span>';
			print "\n" . '<span class="comment-meta">' . _ghostbird_comment_date();
			if ( current_user_can( 'edit_comment', $comment->comment_ID ) ) {
				print "\n" . '<span class="comment-edit"> <a href="' . get_edit_comment_link( $comment->comment_ID ) . '">' . __( 'Edit', 'ghostbird' ) . '</a></span>';
			}
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
			print "\n" . '<span class="comment-edit"> <a href="' . get_edit_comment_link( $comment->comment_ID ) . '">' . __( 'Edit', 'ghostbird' ) . '</a></span>';
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
 * @since     1.0
 */
function _ghostbird_comment_end( $comment, $args, $depth ) {
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
 * If a user has actived the Discussion Guidelines widgetized
 * area, its javascript file will be added to the queue as well.
 *
 * @since     1.0
 */
function _ghostbird_comment_reply_js() {
	if ( is_singular() && comments_open() ) {
		if ( get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
		if ( is_active_sidebar( 'discussion-guidelines' ) ) {
			wp_enqueue_script( 'ghostbird-discussion', get_template_directory_uri() . '/discussion-guidelines.js', array(), GHOSTBIRD_VERSION, true );
		}
	}
}

/**
 * Enclose embedded media in a div.
 *
 * Wrapping all flash embeds in a div allows for easier
 * styling with CSS media queries.
 *
 * @todo      Add to UI.
 * @todo      Document parameters.
 *
 * @since     1.0
 */
function _ghostbird_oembed_dataparse( $cache, $url, $attr = '', $post_ID = '' ) {
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
 * @since     1.0
 */
function _ghostbird_syntaxhighlighter_theme( $themes ) {
	$themes['ghostbird'] = 'Ghostbird';
	return $themes;
}

/**#@-*/

/**#@+
 * Settings
 *
 * This file contains all function and hook definitions
 * responsible for alling a user to interact with Ghostbird
 * via user interface.
 */

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
		'display_site_title'      => 0,
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
	add_settings_field( 'elements', __( 'Elements', 'ghostbird' ),       '_ghostbird_control_elements', 'ghostbird', 'ghostbird_main' );
	add_settings_field( 'plugins',  __( 'Plugin Support', 'ghostbird' ), '_ghostbird_control_plugins',  'ghostbird', 'ghostbird_main' );
	global $ghostbird_settings;
	$ghostbird_settings = ghostbird_get_settings();
}

/**
 * Plugin compatibility.
 *
 * Prints all setting controls for plugin compatibility.
 *
 * @return    void
 *
 * @access    private
 * @since     1.0
 */
function _ghostbird_control_plugins() {
	_ghostbird_control_boolean( 'syntaxhighlighter_theme',  __( 'Enable custom theme for the SyntaxHighlighter Evolved plugin.', 'ghostbird' ) );
}

/**
 * Elements.
 *
 * Prints all setting controls for elements. "Elements"
 * are certain structures that a user may want to hide.
 *
 * @return    void
 *
 * @access    private
 * @since     1.0
 */
function _ghostbird_control_elements() {
	_ghostbird_control_boolean( 'display_site_title',  __( 'Display site title.', 'ghostbird' ) );
	_ghostbird_control_boolean( 'display_tagline',     __( 'Display tagline.', 'ghostbird' ) );
	_ghostbird_control_boolean( 'display_author',      __( 'Display author box at the bottom of all entries.', 'ghostbird' ) );
	_ghostbird_control_boolean( 'display_author_link', __( 'Enable link to author archives after description.', 'ghostbird' ) );
}

/**
 * Boolean control.
 *
 * Generates and prints a form element/label to
 * control a boolean setting.
 *
 * @param     string    The key of a recognized setting. See ghostbird_settings_default() for a list.
 * @param     string    Localized, human-readable label for the setting.
 * @return    void
 *
 * @access    private
 * @since     1.0
 */
function _ghostbird_control_boolean( $id, $label ) {
	global $ghostbird_settings;
	if ( isset( $ghostbird_settings[$id] ) ) {
		print "\n\n" . '<input' . ( ! empty( $ghostbird_settings[$id] ) ? ' checked="checked"' : '' ) . ' type="checkbox" id="ghostbird-' . $id . '" name="ghostbird[' . $id . ']" value="1" /> ';
		print "\n" . '<label for="ghostbird-' . $id . '">' . $label . '</label><br />';
	}
}

/**#@-*/