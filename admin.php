<?php

class Nighthawk_Admin {

	static public function init() {
		add_action( 'admin_menu', array( __class__, 'menu' ) );
		add_action( 'admin_init', array( __class__, 'sections' ) );
	}

	static public function menu() {
		add_theme_page(
			'Nighthawk',
			'Nighthawk Theme',
			'read',
			'nighthawk',
			array( __class__, 'template' )
		);
	}

	static public function sections() {
		add_settings_section(
			'nighthawk',
			'Welcome',
			array( __class__, 'intro' ),
			'nighthawk'
		);
		add_settings_section(
			'nighthawk',
			'Supported Plugins',
			array( __class__, 'plugins' ),
			'nighthawk'
		);
	}

	static public function template() {
		print "\n" . '<div class="wrap">';
		screen_icon();

		print "\n" . '<h2>Nighthawk</h2>';
		print "\n" . '<div id="nighthawk">';

		do_settings_sections( 'nighthawk' );

		print "\n" . '</div></div>';
	}

	static public function intro() {
		print '<p>This is some stuff about Nighthawk!</p>';
	}

	static public function plugins() {
		$testListTable = new Nighthawk_Supported_Plugins();
		$testListTable->prepare_items();
		$testListTable->display();
	}
}


require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );

class Nighthawk_Supported_Plugins extends WP_List_Table {

    var $items = array(
		array(
			'name'   => 'Long Description for Image Attachments',
			'slug'   => 'long-description-for-image-attachments',
			'author' => 'Michael Fields',
		),
		array(
			'name'   => 'Mfields Bookmarks',
			'author' => 'Michael Fields',
		),
		array(
			'name'   => 'Subscribe to Comments',
			'slug'   => 'subscribe-to-comments',
			'author' => 'Mark Jaquith',
		),
		array(
			'name'   => 'SyntaxHighlighter Evolved',
			'slug'   => 'syntaxhighlighter',
			'author' => 'Viper007Bond',
		),
		array(
			'name'   => 'Taxonomy Images',
			'slug'   => 'taxonomy-images',
			'author' => 'Michael Fields',
		),
	);

	function __construct() {
		parent::__construct( array(
			'singular' => 'plugin',
			'plural'   => 'plugins',
			'ajax'     => false
		) );
	}

	function column_name( $item ) {
		return $item['name'];
	}

	function column_author( $item ) {
		return $item['author'];
	}

	function column_link( $item ) {
		if ( isset( $item['slug'] ) && ! empty( $item['slug'] ) ) {
			$url = 'http://wordpress.org/extend/plugins/' . $item['slug'] . '/';
			$title = sprintf( __( 'Read more about the %1$s plugin.' ), $item['name'] );
			return '<a href="' . esc_url( $url ) . '" title="' . esc_attr( $title ) . '">wordpress.org</a>';
		}
	}

	function get_columns() {
		return array(
			'name'   => __( 'Plugin Name', 'nighthawk' ),
			'link'   => __( 'Plugin Page', 'nighthawk' ),
			'author' => __( 'Author', 'nighthawk' ),
		);
	}

	function prepare_items() {
		$this->_column_headers = array(
			$this->get_columns(),
			array(),
			$this->get_sortable_columns(),
		);

		$this->set_pagination_args( array(
			'total_items' => count( $this->items ),
			'per_page'    => 999,
			'total_pages' => 1
		) );
	}
}