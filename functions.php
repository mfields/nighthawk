<?php
/**
 * Functions
 *
 * @package      Nighthawk
 * @author       Michael Fields <michael@mfields.org>
 * @copyright    Copyright (c) 2011, Michael Fields
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since        Nighthawk 1.0
 */

class Nighthawk {

	const prefix = 'Nighthawk::';

	/**
	 * Table Columns.
	 *
	 * @since Nighthawk 1.0
	 */
	static private $columns = null;

	/**
	 * Init.
	 *
	 * @since Nighthawk 1.0
	 */
	public static function init() {
		self::$columns = array(
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
		add_action( 'after_setup_theme', array( __class__, 'setup' ) );
	}

	/**
	 * Setup Nighthawk
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
	 * @since     Nighthawk 1.0
	 */
	public static function setup() {
		if ( is_admin() )
			require_once get_template_directory() . '/admin.php';

		global $content_width;
		if ( ! isset( $content_width ) )
			$content_width = 700;

		load_theme_textdomain( 'nighthawk', get_template_directory() . '/languages' );

		add_editor_style( 'style-editor.css' );

		add_theme_support( 'menus' );
		add_theme_support( 'post-formats', array( 'image', 'status' ) );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );

		add_custom_background();

		/* A few extras for pages. */
		add_post_type_support( 'page', 'excerpt' );
		add_post_type_support( 'page', 'thumbnail' );

		/* Image sizes. */
		set_post_thumbnail_size( 150, 150, false );
		add_image_size( 'nighthawk_detail', 70, 70, true );

		/* Navigation menus. */
		register_nav_menus( array( 'primary' => 'Primary', 'secondary' => 'Secondary' ) );

		add_filter( 'get_the_author_description', 'wptexturize' );
		add_filter( 'get_the_author_description', 'convert_chars' );
		add_filter( 'get_the_author_description', 'wpautop' );

		add_action( 'comment_form_after',  self::prefix . 'commentform_after' );
		add_action( 'comment_form_before', self::prefix . 'commentform_before' );
		add_filter( 'edit_post_link',      self::prefix . 'edit_post_link', 9, 2 );
		add_filter( 'embed_oembed_html',   self::prefix . 'oembed_dataparse', 10, 4 );
		add_filter( 'embed_googlevideo',   self::prefix . 'oembed_dataparse', 10, 2 );
		add_filter( 'excerpt_more',        self::prefix . 'excerpt_more_auto' );
		add_filter( 'post_class',          self::prefix . 'post_class' );
		add_action( 'template_redirect',   self::prefix . 'filter_table_columns' );
		add_action( 'template_redirect',   self::prefix . 'post_labels_init' );
		add_filter( 'the_password_form',   self::prefix . 'password_form' );
		add_action( 'the_title',           self::prefix . 'filter_post_title' );
		add_action( 'widgets_init',        self::prefix . 'register_widget_areas' );
		add_action( 'wp_enqueue_scripts',  self::prefix . 'script_comment_reply' );
		add_action( 'wp_enqueue_scripts',  self::prefix . 'css_heading_font' );
		add_action( 'wp_enqueue_scripts',  self::prefix . 'css_syntaxhighlighter' );
		add_action( 'wp_enqueue_scripts',  self::prefix . 'script_dropdown_widgets' );
		add_action( 'wp_loaded',           self::prefix . 'custom_header_config' );

		add_filter( 'syntaxhighlighter_themes', self::prefix . 'syntaxhighlighter_theme' );
	}
	/**
	 * Configuration for enabling the WordPress custom header image feature.
	 *
	 * @since     Nighthawk 1.0
	 */
	function custom_header_config() {
		define( 'HEADER_TEXTCOLOR', '777' );
		define( 'HEADER_IMAGE', get_template_directory_uri() . '/images/lanterns.jpg' );
		define( 'HEADER_IMAGE_WIDTH', 1000 );
		define( 'HEADER_IMAGE_HEIGHT', 288 );
		define( 'NO_HEADER_TEXT', true );

		add_custom_image_header( '_nighthawk_custom_image_header_live', '_nighthawk_custom_image_header_admin' );
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
	 * @since     Nighthawk 1.0
	 */
	public static function script_comment_reply() {
		if ( is_singular() && comments_open() ) {
			if ( get_option( 'thread_comments' ) )
				wp_enqueue_script( 'comment-reply' );
		}
	}

	/**
	 * Dropdown Widgets Script.
	 *
	 * @since     Nighthawk 1.0
	 */
	public static function script_dropdown_widgets() {
		wp_enqueue_script(
			'dropdown-widgets',
			get_template_directory_uri() . '/inc/dropdowns.js',
			array( 'jquery' ),
			'0.1',
			true
		);
	}

	/**
	 * Heading Font Styles.
	 *
	 * @since     Nighthawk 1.0
	 */
	public static function css_heading_font() {
		wp_enqueue_style(
			'nighthawk-cabin',
			'http://fonts.googleapis.com/css?family=Cabin:regular,regularitalic,bold,bolditalic',
			array(),
			'1'
		);
	}

	/**
	 * SyntaxHighlighter Evolved Styles.
	 *
	 * @see       http://wordpress.org/extend/plugins/syntaxhighlighter/
	 * @since     Nighthawk 1.0
	 */
	public static function css_syntaxhighlighter() {
		wp_register_style(
			'syntaxhighlighter-theme-nighthawk',
			get_template_directory_uri() . '/style-syntax-highlighter.css',
			array( 'syntaxhighlighter-core' ),
			'1'
		);
	}

	/**
	 * Register Widgetized Areas.
	 *
	 * @return    void
	 *
	 * @since     Nighthawk 1.0
	 */
	public static function register_widget_areas() {
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
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );

		/* Area 2 - Middle column below content. */
		register_sidebar( array(
			'name'          => __( 'Bottom 2', 'nighthawk' ),
			'id'            => 'second-footer-widget-area',
			'description'   => __( 'The second footer widget area', 'nighthawk' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );

		/* Area 3, Right column bottom of content . */
		register_sidebar( array(
			'name'          => __( 'Bottom 3', 'nighthawk' ),
			'id'            => 'third-footer-widget-area',
			'description'   => __( 'The third footer widget area', 'nighthawk' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );
	}

	/**
	 * Add custom class names to individual posts.
	 *
	 * This filter is attached to the 'post_class' hook
	 * @see Nighthawk::setup()
	 *
	 * @param     array     $classes All classes for the post container.
	 * @return    array     Modified classes for the post container.
	 *
	 * @access    private
	 * @since     Nighthawk 1.0
	 */
	public static function post_class( $classes ) {
		if ( is_search() )
			return array( 'search-result', 'box' );

		$classes[] = 'entry';
		$classes[] = 'box';

		$featured_image = get_the_post_thumbnail();
		if ( ! empty( $featured_image ) )
			$classes[] = 'has-featured-image';

		return array_unique( $classes );
	}

	/**
	 * Excerpt More (auto).
	 *
	 * In cases where a post does not have an excerpt defined
	 * WordPress will append the string "[...]" to a shortened
	 * version of the post_content field. Nighthawk will replace
	 * this string with an ellipsis.
	 *
	 * This filter is attached to the 'excerpt_more' hook
	 * @see Nighthawk::setup()
	 *
	 * @param     string         $more unused.
	 * @return    string         An ellipsis followed by a link to the single post.
	 *
	 * @access    private
	 * @since     Nighthawk 1.0
	 */
	public static function excerpt_more_auto( $more ) {
		return ' &hellip;';
	}

	/**
	 * Post label.
	 *
	 * Returns a noun representing the type or format of the global
	 * post object. This function is used internally by the
	 * nighthawk_entry_meta_taxonomy() function to create a sentence much
	 * like the following: "This Status Update is filed under News."
	 * where "Status Update" is the post label and "News" is the category.
	 *
	 * @uses      NighthawkPostLabel::get_label()
	 *
	 * @param     string    $type Optional. May be either "singular" or "plural". Defaults to "singular".
	 * @return    string    A noun representing the global post object.
	 *
	 * @access    public
	 * @since     Nighthawk 1.0
	 */
	public static function post_label( $type = 'singular' ) {
		if ( class_exists( 'NighthawkPostLabel' ) )
			return NighthawkPostLabel::get( $type );
		else if ( 'singular' == $type )
			return __( 'entry', 'nighthawk' );
		else
			return __( 'entries', 'nighthawk' );
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
	 * @since     Nighthawk 1.0
	 */
	public static function oembed_dataparse( $cache, $url, $attr = '', $post_ID = '' ) {
		return '<div class="embed">' . $cache . '</div>';
	}

	/**
	 * SyntaxHighlighter Evolved Support.
	 *
	 * Registers a custom theme with the SyntaxHighlighter Evolved plugin.
	 *
	 * {@link http://wordpress.org/extend/plugins/syntaxhighlighter/ SyntaxHighlighter Evolved }
	 *
	 * @param     array     $themes All themes registered with the SyntaxHighlighter Evolved plugin.
	 * @return    array     Same list with custom theme appended.
	 *
	 * @access    private
	 * @since     Nighthawk 1.0
	 */
	public static function syntaxhighlighter_theme( $themes ) {
		$themes['nighthawk'] = 'Nighthawk';
		return $themes;
	}

	public static function filter_post_title( $title ) {
		if ( ! is_singular() )
			return $title;

		if ( empty( $title ) && 'post' == get_post_type() )
			$title = ucfirst( self::post_label() ); // todo: ucfirst for English only if possible.

		return $title;
	}
	public static function commentform_before() {
		echo '<div class="box">';
	}
	public static function commentform_after() {
		echo '</div>';
	}
	public static function post_labels_init() {
		require_once get_template_directory() . '/inc/post-labels.php';
		NighthawkPostLabel::init();
	}

	/**
	 * Edit post link filter.
	 *
	 * Modifies the output of WordPress
	 * core function edit_post_link();
	 *
	 * @param     string    $html Default anchor tag generated by WordPress.
	 * @param     int       $ID Post ID.
	 *
	 * @access    private
	 * @since     Nighthawk 1.0
	 */
	public static function edit_post_link( $html, $ID ) {
		return '<a class="post-edit-link" href="' . esc_url( get_edit_post_link( $ID ) ) . '" title="' . esc_attr( sprintf( __( 'Edit this %1$s', 'nighthawk' ), self::post_label() ) ) . '">' . esc_html( wp_strip_all_tags( $html ) ) . '</a>';
	}

	/**
	 * Password Form.
	 *
	 * @param     string    $form Default password-protected post from.
	 * @return    string    Custom form from template.
	 *
	 * @access    private
	 * @since     Nighthawk 1.0
	 */
	public static function password_form( $form ) {
		ob_start();
		get_template_part( 'password-protected-post-form' );
		$form = ob_get_contents();
		ob_end_clean();
		return $form;
	}

	/**
	 * Search Form ID.
	 *
	 * @return    string    ID attribute for search form.
	 *
	 * @since     Nighthawk 1.0
	 */
	public static function searchform_id() {
		static $id = 0;
		return 'search-form-' . $id++;
	}

	/**
	 * Total Posts.
	 *
	 * @return    int       ID attribute for search form.
	 *
	 * @since     Nighthawk 1.0
	 */
	static public function post_total() {
		global $wp_query;
		if ( isset( $wp_query->found_posts ) )
			return (int) $wp_query->found_posts;
		return 0;
	}

	/**
	 * Get table columns.
	 *
	 * @uses      Nighthawk::$columns
	 * @return    int       ID attribute for search form.
	 *
	 * @since     Nighthawk 1.0
	 */
	static public function get_table_columns() {
		if ( current_user_can( 'edit_posts' ) ) {
			$edit = array(
				'label'    => __( 'Edit', 'nighthawk' ),
				'class'    => 'edit-post icon',
				'callback' => 'nighthawk_td_edit',
			);
			array_unshift( self::$columns, $edit );
		}
		return (array) self::$columns;
	}

	/**
	 * Set table columns.
	 *
	 * @uses      Nighthawk::$columns
	 * @return    array     Table columns configuration.
	 *
	 * @since     Nighthawk 1.0
	 */
	static public function set_table_columns( $columns = null ) {
		self::$columns = $columns;
	}

	/**
	 * Filter table columns.
	 *
	 * Allow plugins to adjust the table columns.
	 *
	 * @uses      Nighthawk::$columns
	 * @return    array     Table columns configuration.
	 *
	 * @since     Nighthawk 1.0
	 */
	static public function filter_table_columns() {
		self::$columns = apply_filters( 'nighthawk_table_columns', self::$columns );
	}
}

Nighthawk::init();

/**
 * Summary Meta.
 *
 * Print meta information pertaining to the current view.
 *
 * @param     string         $before Text to prepend to the summary meta.
 * @param     string         $after Text to append to the summary meta.
 * @param     bool           $print True to print, false to return a string. Defaults to true.
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
		$post_type = get_queried_object();
		if ( empty( $post_type ) ) {
			$post_type = get_post_type_object( 'post' );
		}
		if ( isset( $post_type->name ) && isset( $post_type->label ) && isset( $post_type->labels->singular_name ) ) {
			$feed_url   = get_post_type_archive_feed_link( $post_type->name );
			$feed_title = sprintf( __( 'Get updated whenever new %1$s are published.', 'nighthawk' ), $post_type->label );
			$sentence   = sprintf( _n( 'Only one %3$s found in this archive.', 'There are %1$s %2$s in this archive.', $total, 'nighthawk' ), number_format_i18n( $total ), Nighthawk::post_label( 'plural' ), Nighthawk::post_label() );
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
			$label = Nighthawk::post_label();
			$sentence = sprintf( __( 'This %1$s is attached to %2$s.', 'nighthawk' ), $label, $parent_link );
			$sentence = apply_filters( 'nighthawk_summary_file', $sentence );
		}
	}
	else if ( is_tax() ) {
		$term = get_queried_object();
		if ( isset( $term->term_id ) && isset( $term->name ) && isset( $term->taxonomy ) ) {
			$taxonomy = get_taxonomy( $term->taxonomy );
			$taxonomy_name = __( 'taxonomy', 'nighthawk' );
			if ( isset( $taxonomy->labels->singular_name ) ) {
				$taxonomy_name = $taxonomy->labels->singular_name;
			}

			switch ( $term->taxonomy ) {
				case 'post_format' :
					$feed_title = sprintf( __( 'Get updated whenever a new %1$s is published.', 'nighthawk' ), Nighthawk::post_label() );
					$sentence = sprintf( _n( 'This site contains one %2$s.', 'This site contains %1$s %3$s.', $total, 'nighthawk' ), number_format_i18n( $total ), Nighthawk::post_label(), Nighthawk::post_label( 'plural' ) );
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
			echo $sentence;
		}
		else {
			return $sentence;
		}
	}
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
		echo $sentence;
		return;
	}

	$label     = Nighthawk::post_label();
	#$label_url = get_post_format_link( get_post_format() );
	$label_url = get_permalink();

	if ( 'post' != get_post_type() ) {
		return;
	}

	$post_tags  = get_the_tag_list( '', ', ' );
	$categories = get_the_category_list( ', ' );

	if ( ! empty( $label ) && ! empty( $label_url ) ) {
		$plural = Nighthawk::post_label( 'plural' );
		$title = '';
		if ( ! empty( $plural ) ) {
			$title = ' title="' . sprintf( esc_attr__( 'View all %1$s', 'nighthawk' ), strtolower( $plural ) ) . '"';
		}
		$label = '<a href="' . esc_url( $label_url ) . '"' . $title . '>' . esc_html( $label ) . '</a>';
	}

	if ( ! empty( $label ) ) {
		if ( ! empty( $categories ) && ! empty( $post_tags ) ) {
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
		echo '<p>' . $sentence . '</p>';
	}
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
		'paragraph' => sprintf( __( 'Please enter your email address and click subscribe to receive an email whenever a new comment is made about this %1$s.', 'nighthawk' ), Nighthawk::post_label() ),
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

	$sg_subscribe->show_errors( 'solo_subscribe', '<div class="solo-subscribe-errors">', '</div>', __( 'Error: ', 'nighthawk' ), '' );

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
		echo $form;
	}
	else {
		return $form;
	}
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
	echo '<style>#site-name,#site-name a,#tagline{color:#' . HEADER_TEXTCOLOR . '}</style>';
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
	echo <<< EOF
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
 * Comment start.
 *
 * Prints most of a single comment.
 * @see _nighthawk_comment_end().
 *
 * @param     stdClass  $comment Comment object.
 * @param     array     $args Arguments passed to wp_list_comments() merged with default values.
 * @param     int       $depth Position of the current comment in relation to the root comment of this tree. Starts at zero.
 *
 * @access    private
 * @since     1.0
 */
function _nighthawk_comment_start( $comment, $args, $depth ) {

	$GLOBALS['comment'] = $comment;

	if ( '' == $comment->comment_type ) {
		echo "\n\n\n\n" . '<li id="comment-'; comment_ID(); echo '" '; comment_class( 'box' ); echo '>';
		if ( 0 === (int) $comment->comment_approved ) {
			echo esc_html__( 'Your comment is awaiting moderation.', 'nighthawk' );
		}
		else {
			echo "\n" . get_avatar( $comment, 45 );
			echo "\n" . '<span class="heading commenter">' . get_comment_author_link( $comment->comment_ID ) . '</span>';
			echo "\n" . '<span class="meta">';

			/* Comment date. */
			echo "\n" . '<a class="comment-date" href="' . get_comment_link( $comment->comment_ID ) . '"  title="' . esc_attr__( 'Direct link to this comment.', 'nighthawk' ) . '">' . sprintf( esc_html__( '%1$s at %2$s', 'nighthawk' ), get_comment_date(),  get_comment_time() ) . '</a>';

			/* Edit comment link. */
			if ( current_user_can( 'edit_comment', $comment->comment_ID ) ) {
				echo "\n" . '<span class="comment-edit"> <a href="' . esc_url( get_edit_comment_link( $comment->comment_ID ) ) . '">' . esc_html__( 'Edit', 'nighthawk' ) . '</a></span>';
			}

			/* Reply to comment link. */
			comment_reply_link( array_merge( $args, array(
				'depth'     => $depth,
				'max_depth' => $args['max_depth'],
				'before'    => "\n" . ' <span class="comment-reply">',
				'after'     => '</span>'
				) ) );

			echo '</span><!-- .meta -->';

			echo "\n" . '<div class="content">'; comment_text(); echo '</div>';
		}
	}
	else {
		echo '<li class="trackback box">';
		echo '<div class="content">';
			comment_author_link();
			if ( current_user_can( 'edit_comment', $comment->comment_ID ) ) {
				echo "\n" . '<span class="comment-edit"> <a href="' . esc_url( get_edit_comment_link( $comment->comment_ID ) ) . '">' . esc_html__( 'Edit', 'nighthawk' ) . '</a></span>';
			}
		echo '</div>';
	}
}

/**
 * Comment end.
 *
 * Custom callback for wp_list_comments().
 * Print a closing html list-item element.
 *
 * @param     stdClass  $comment Comment object.
 * @param     array     $args Arguments passed to wp_list_comments() merged with default values.
 * @param     int       $depth Position of the current comment in relation to the root comment of this tree. Starts at zero.
 *
 * @access    private
 * @since     1.0
 */
function _nighthawk_comment_end( $comment, $args, $depth ) {
	echo '</li>';
}

function nighthawk_td_edit( $column = array() ) {
	echo "\n\t" . '<td class="' . esc_attr( $column['class'] ) . '">';
	echo '<a href="' . esc_url( get_edit_post_link() ) . '"><img src="' . esc_url( get_template_directory_uri() . '/images/edit.png' ) . '" alt="' . esc_attr__( 'Edit', 'nighthawk' ) . '"></a>';
	echo '</td>';
}

function nighthawk_td_title( $column = array() ) {
	$post_type = get_post_type();
	if ( ! post_type_supports( $post_type, 'title' ) ) {
		echo "\n\t" . '<td class="' . esc_attr( $column['class'] ) . ' empty-cell"></td>';
		return;
	}

	$title = the_title( '', '', false );
	if ( empty( $title ) )
		$title = sprintf( __( 'Untitled %1$s', 'nighthawk' ), Nighthawk::post_label() );

	$url = get_post_meta( get_the_ID(), '_mfields_bookmark_url', true );
	if ( ! empty( $url ) ) {
		$title_attr = __( 'Visit this document', 'nighthawk' );
		$action = get_post_meta( get_the_ID(), '_mfields_bookmark_link_text', true );
		if ( ! empty( $action ) ) {
			$title_attr = ' title="' . esc_attr( $action ) . '"';
		}
		$title  = '<a href="' . esc_url( $url ) . '" rel="external"' . $title_attr . '>' . $title . '</a>';
	}

	echo "\n\t" . '<td class="' . esc_attr( $column['class'] ) . '"><a href="' . esc_url( get_permalink() ) . '">' . $title . '</a></td>';
}

function nighthawk_td_comment_count( $column = array() ) {
	if ( post_password_required() ) {
		echo "\n\t" . '<td class="' . esc_attr( $column['class'] ) . ' empty-cell"></td>';
		return;
	}
	echo "\n\t" . '<td class="' . esc_attr( $column['class'] ) . '">';
	comments_popup_link( '', '1', '%', 'comments-link', '' );
	echo '</td>';
}

function nighthawk_td_comment_icon( $column = array() ) {
	$post_type = get_post_type();
	if ( ! post_type_supports( $post_type, 'comments' ) ) {
		echo "\n\t" . '<td class="' . esc_attr( $column['class'] ) . ' empty-cell"></td>';
		return;
	}

	if ( ! comments_open( get_the_ID() ) ) {
		nighthawk_td_permalink_icon( $column );
		return;
	}

	echo "\n\t" . '<td class="' . esc_attr( $column['class'] ) . '"><a href="' . esc_url( get_permalink() . '#respond' ) . '" class="comment-icon">' . esc_html__( 'Add a comment', 'nighthawk' ) . '</a></td>';
}

function nighthawk_td_permalink_icon( $column = array() ) {
	echo "\n\t" . '<td class="' . esc_attr( $column['class'] ) . '"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark" class="permalink-icon">' . esc_html__( 'Permalink', 'nighthawk' ) . '</a></td>';
}