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
}
