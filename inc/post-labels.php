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

class NighthawkPostLabel {

	public static $labels = null;

	public static function init() {
		self::$labels = array(
			'page' => array(
				'standard' => _nx_noop( 'page',            'pages',            'Post Label' ),
			),
			'post' => array(
				'standard' => _nx_noop( 'post',            'posts',            'Post Label' ),
				'aside'    => _nx_noop( 'aside',           'asides',           'Post Label' ),
				'audio'    => _nx_noop( 'audio file',      'audio files',      'Post Label' ),
				'chat'     => _nx_noop( 'chat transcript', 'chat transcripts', 'Post Label' ),
				'gallery'  => _nx_noop( 'gallery',         'galleries',        'Post Label' ),
				'image'    => _nx_noop( 'image',           'images',           'Post Label' ),
				'link'     => _nx_noop( 'link',            'links',            'Post Label' ),
				'quote'    => _nx_noop( 'quote',           'quotes',           'Post Label' ),
				'status'   => _nx_noop( 'status update',   'status updates',   'Post Label' ),
				'video'    => _nx_noop( 'video',           'videos',           'Post Label' )
			),
			'attachment' => array(
				'standard'    => _nx_noop( 'file',        'files',             'Post Label' ),
				'image'       => _nx_noop( 'image',       'images',            'Post Label' ),
				'icon'        => _nx_noop( 'icon',        'icons',             'Post Label' ),
				'zip'         => _nx_noop( 'zip archive', 'zip archives',      'Post Label' ),
				'doc'         => _nx_noop( 'document',    'documents',         'Post Label' ),
				'pdf'         => _nx_noop( 'PDF',         'PDFs',              'Post Label' ),
				'spreadsheet' => _nx_noop( 'spreadsheet', 'spreadsheets',      'Post Label' ),
				'video'       => _nx_noop( 'video',       'videos',            'Post Label' ),
			),
		);

		$post_types = get_post_types( array( 'public' => true, '_builtin' => false ), 'objects' );
		if ( ! empty( $post_types ) && is_array( $post_types ) ) {
			foreach ( (array) $post_types as $name => $post_type ) {
				self::$labels[$post_type]['standard'] = array(
					0          => $post_type->labels->singular_name,
					1          => $post_type->labels->name,
					2          => 'Post Label',
					'singular' => $post_type->labels->singular_name,
					'plural'   => $post_type->labels->name,
					'context'  => 'Post Label',
				);
			}
		}
	}

	public static function get( $type = 'singular' ) {
		$count = ( 'singular' == $type ) ? 1 : 2;
		return translate_nooped_plural( self::get_noop(), $count, 'nighthawk' );
	}

	private static function get_noop() {
		$key = 'standard';

		$post_format = get_post_format();
		if ( ! empty( $post_format ) )
			$key = $post_format;

		$post_type = get_post_type();
		if ( isset( self::$labels[$post_type][$key] ) )
			return self::$labels[$post_type][$key];

		return _nx_noop( 'entry', 'entries', 'post label' );
	}

	private static function get_attachment_key() {
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
				$key = 'video';
				break;
			case 'image/x-icon' :
				$key = 'icon';
				break;
			case 'application/pdf' :
				$key = 'pdf';
				break;
			case 'application/zip' :
				$key = 'zip';
				break;
			case 'image/x-icon' :
				$key = 'icon';
				break;
			case 'application/vnd.ms-excel' :
			case 'application/vnd.oasis.opendocument.spreadsheet' :
				$key = 'doc';
				break;
			case 'application/msword' :
			case 'application/vnd.oasis.opendocument.text' :
				$key = 'doc';
				break;
			default :
				$key = 'standard';
				break;
		}

		return $type;
	}
}