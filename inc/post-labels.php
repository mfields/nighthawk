<?php

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
 * @since     Nighthawk 1.0
 */

class Mfields_Post_Label {
	const version = '2.0';
	const context = 'post label';

	static public $map = null;
	static public $labels = null;
	static public $textdomain = null;

	static public $count = null;

	static public function init( $textdomain = null ) {
		self::$labels = array(
			'page' => array(
				'standard' => _nx_noop( 'page',            'pages',            self::context ),
			),
			'post_format' => array(
				'standard' => _nx_noop( 'post',            'posts',            self::context ),
				'aside'    => _nx_noop( 'aside',           'asides',           self::context ),
				'audio'    => _nx_noop( 'audio file',      'audio files',      self::context ),
				'chat'     => _nx_noop( 'chat transcript', 'chat transcripts', self::context ),
				'gallery'  => _nx_noop( 'gallery',         'galleries',        self::context ),
				'image'    => _nx_noop( 'image',           'images',           self::context ),
				'link'     => _nx_noop( 'link',            'links',            self::context ),
				'quote'    => _nx_noop( 'quote',           'quotes',           self::context ),
				'status'   => _nx_noop( 'status update',   'status updates',   self::context ),
				'video'    => _nx_noop( 'video',           'videos',           self::context )
			),
			'attachment' => array(
				'standard'    => _nx_noop( 'file',        'files',             self::context ),
				'image'       => _nx_noop( 'image',       'images',            self::context ),
				'icon'        => _nx_noop( 'icon',        'icons',             self::context ),
				'zip'         => _nx_noop( 'zip archive', 'zip archives',      self::context ),
				'doc'         => _nx_noop( 'document',    'documents',         self::context ),
				'pdf'         => _nx_noop( 'PDF',         'PDFs',              self::context ),
				'spreadsheet' => _nx_noop( 'spreadsheet', 'spreadsheets',      self::context ),
				'video'       => _nx_noop( 'video',       'videos',            self::context ),
			),
		);

		$post_types = get_post_types( array( 'public' => true, '_builtin' => false ), 'objects' );
		if ( ! empty( $post_types ) && is_array( $post_types ) ) {
			self::$labels['custom'] = array();
			foreach ( (array) $post_types as $name => $post_type ) {
				self::$labels['custom'][$name] = array(
					0          => $post_type->labels->singular_name,
					1          => $post_type->labels->name,
					2          => self::context,
					'singular' => $post_type->labels->singular_name,
					'plural'   => $post_type->labels->name,
					'context'  => self::context,
				);
			}
		}
	}

	static public function get_label() {
		self::$count++;

		$key = get_the_ID();
		if ( is_tax( 'post_format' ) ) {
			global $wp_query;
			$term = get_term( $wp_query->get_queried_object(), 'post_format' );
			if ( isset( $term->slug ) ) {
				$key = str_replace( 'post-format-', '', $term->slug );
			}
		}

		if ( isset( self::$map[$key] ) ) {
			return self::$map[$key];
		}

		$post_type = get_post_type();

		switch ( get_post_type() ) {
			case 'post' :
				$output = self::post();
				break;
			case 'page' :
				$output = array(
					'context' => 'page',
					'type'    => 'standard',
				);
				break;
			case 'attachment' :
				$output = self::attachment();
				break;
			default :
				$output = array(
					'context' => 'custom',
					'type'    => $post_type,
				);
				break;
		}

		$output = self::find( $output );

		self::$map[$key] = $output;
		return $output;
	}

	static public function post() {
		$type = get_post_format();
		if ( empty( $type ) ) {
			$type = 'standard';
		}
		return array(
			'context' => 'post_format',
			'type'    => $type,
		);
	}

	static public function attachment() {
		$mime = get_post_mime_type();
		switch ( $mime ) {
			case 'image/jpeg' :
			case 'image/gif' :
			case 'image/png' :
			case 'image/bmp' :
			case 'image/tiff' :
			case 'video/asf' :
			case 'video/avi' :
			case 'video/divx' :
			case 'video/x-flv' :
			case 'video/quicktime' :
			case 'video/mpeg' :
			case 'video/mp4' :
			case 'video/ogg' :
			case 'video/x-matroska' :
				$parts = explode( '/', $mime );
				$type = $parts[0];
				break;
			case 'image/x-icon' :
				$type = 'icon';
				break;
			case 'application/pdf' :
				$type = 'pdf';
				break;
			case 'application/zip' :
				$type = 'zip';
				break;
			case 'image/x-icon' :
				$type = 'icon';
				break;
			case 'application/vnd.ms-excel' :
			case 'application/vnd.oasis.opendocument.spreadsheet' :
				$type = 'doc';
				break;
			case 'application/msword' :
			case 'application/vnd.oasis.opendocument.text' :
				$type = 'doc';
				break;
			default :
				$type = 'default';
				break;
		}

		return array(
			'context' => 'attachment',
			'type'    => $type,
		);
	}

	static public function find( $v ) {
		$v = wp_parse_args( $v, array(
			'context' => null,
			'type'    => null,
		) );

		extract( (array) $v );

		if ( isset( self::$labels[$context][$type] ) ) {
			return self::$labels[$context][$type];
		}
		else {
			return _nx_noop( 'entry', 'entries', 'post label' );
		}
	}

	static public function dump() {
		echo '<pre>';
		echo __class__ . ' v' . self::version . "\n";
		echo "\n" . 'Times used: ' . self::$count;
		echo "\n" . '$map: ' . print_r( self::$map, true );
		echo "\n" . '$labels: ' . print_r( self::$labels, true );
		echo '</pre>';
	}
}