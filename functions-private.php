<?php
/**
 * Private Functions
 *
 * The functions defined in this file are deemed to be private
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
 * @package      Ghostbird
 * @subpackage   Functions
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        1.0
 */

/**
 * Remove empty heading tag from the calendar widget.
 *
 * @param     array     Classes for the body tag.
 * @return    array     Modified classes for the body tag.
 *
 * @access    private
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
 * @return    void.
 *
 * @access    private
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
 * @access    private
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
 * @access    private
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
 * @access    private
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
 * @access    private
 * @since     1.0
 */
function _ghostbird_custom_image_header_admin() {
	
	$h = HEADER_IMAGE_HEIGHT + 10;
	$background_color = get_theme_mod( 'background_color', '375876' );
	
	print <<< EOF
<style type="text/css">
div#headimg {
	
	max-height:20px !important;
	overflow:hidden;
	
	/*
	height:20px !important;
	padding:10px;
	background-color:#{$background_color};
	background-repeat:no-repeat;
	background-position:10px 10px;
	*/
}
#headimg h1,
#headimg #desc {
	position:relative;
	left:999em;
	display:none !important;
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
 * @access    private
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
 * @access    private
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
 * @access    private
 * @since     1.0
 */
function _ghostbird_post_class_hentry( $classes ) {
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
 * @access    private
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
 * @access    private
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
 * Post Content: Image format.
 *
 * This filter will allow authors to post only a url to an image
 * in the post content and have the image display properly in the
 * theme as an img tag. 
 *
 * An "alt" attribute will always be generated for each image. An
 * author can specify the value of the alt attribute by creating
 * a custom field with a key of "ghostbird_alt". If no custom field
 * is available, an empty string will be used.
 *
 * The title attribute of the img tag will be populated by the value
 * produced by get_the_title(). In the event that there is no post
 * title available no title attribute will be printed.
 *
 * Child themes may customize the attributes generated for each image
 * by registering a custom function for the 'ghostbird-image-format-attributes'
 * filter. An example is below:
 * 
 * This filter will add a class of "mysite" to all images hosted at
 * the same domain as WordPress is installed on.
 *
 * <code>
 * function mytheme_filter_image_format_attributes( $atts, $src ) {
 *     if ( false !== strstr( $src, site_url( '', 'http' ) ) ) {
 *         if ( isset( $atts['class'] ) ) {
 *             $atts['class'] = (array) $atts['class'];
 *         }
 *         $atts['class'][] = 'mysite';
 *     }
 *     return $atts;
 * }
 * add_filter( 'ghostbird-image-format-attributes', 'mytheme_filter_image_format_attributes', 10, 2 );
 * </code>
 *
 * @param     string    Post content.
 * @return    string    HTML img tag if all conditions are met, unfiltered content otherwise.
 *
 * @access    private
 * @since     1.0
 */
function _ghostbird_filter_content_for_image_format( $content ) {
	if ( 'image' == get_post_format() ) {
		$src = esc_url( $content );
		$gis = @getimagesize( $src );
		if ( ! empty( $src ) && is_array( $gis ) ) {
			$attributes = array();
			$post_id = get_the_ID();
			$title = get_the_title();
			$defaults = array(
				'alt'    => get_post_meta( $post_id, 'ghostbird_alt', true ),
				'width'  => $gis[0],
				'height' => $gis[1]
				);
			if ( ! empty( $title ) ) {
				$defaults['title'] = $title;
			}
			$filtered = apply_filters( 'ghostbird-image-format-attributes', $defaults, $src, $gis );
			$atts = array_merge( $defaults, $filtered );
			foreach( $atts as $name => $value ) {
				if ( in_array( $name, array( 'id', 'class', 'alt', 'title', 'height', 'width', 'longdesc', 'style' ) ) ) {
					if ( 'class' == $name && is_array( $value ) ) {
						$value = implode( ' ', $value );
					}
					$attributes[] = $name . '="' . esc_attr( $value ) . '"';
				}
			}
			$content = '<img class="ghostbird-image" src="' . $src . '" ' . implode( ' ', $attributes ) . '>';
		}
	}
	return $content;
}

/**
 * Post Content: Chat format.
 *
 * This filter is designed to be attached to the post_content hook.
 * In the event that the current global post object has been assigned
 * a format of "chat", this function will attemt to find the first pre
 * tag in $content. This pre tag is understood to contain the contents
 * of a chat transcript. If the transcript is found each line will be 
 * enclosed in a span element and odd numbered lines will be given the
 * class attribute of "alt". The pre tag is then replaced with a div
 * tag with a class attribute of "chat-log".
 *
 * @param     string    post_content field of the current entry.
 * @return    string    Filtered results for chat formats, unaltered value of $content otherwise.
 *
 * @access    private
 * @since     1.0
 *
 * @todo      Allow users to disable via UI.
 */
function _ghostbird_filter_content_for_chat_format( $content ) {
	if ( 'chat' == get_post_format() ) {
		/* Attempt to match the first pre element. */
		preg_match( '/<pre>(.*?)<\/pre>/s', $content, $matches );

		if ( isset( $matches[1] ) ) {
			$lines = explode( "\n", $matches[1] );
			$filtered = '';
			foreach ( (array) $lines as $order => $line ) {
				$filtered.= "\n" . '<span' . ( 1 == $order % 2 ? '' : ' class="alt"' ) . '>' . str_replace( array( '<br>', '<br />', '<br/>' ), '', $line ) . '</span>';
			}
			if ( $filtered ) {
				$content = "\n" . '<div class="chat-log">' . preg_replace( '/<pre>(.*?)<\/pre>/s', $filtered, $content, 1 ) . '</div>';
			}
		}
	}
	return $content;
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
 * @access    private
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
 * <?php remove_action( 'ghostbird_hentry_end', 'ghostbird_related_images' ); ?>
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
 * Print the author bio on single post views.
 *
 * @return    void
 *
 * @access    private
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
 * @access    private
 * @since     1.0
 */
function _ghostbird_author_link( $description ) {
	if ( ! empty( $description ) ) {
		$link = '<a href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '">' . sprintf( __( ' View all entries by %s.', 'ghostbird' ), get_the_author() ) . '</a>';
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
 * @access    private
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
 * @access    private
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
 * @access    private
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
 * @access    private
 * @since     1.0
 */
function _ghostbird_comment_reply_js() {
	if ( is_singular() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
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
 * @access    private
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
 * @access    private
 * @since     1.0
 */
function _ghostbird_syntaxhighlighter_theme( $themes ) {
	$themes['ghostbird'] = 'Ghostbird';
	return $themes;
}