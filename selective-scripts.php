<?php
/*
Plugin Name: Selective Scripts
Plugin URI:  http://wpjoburg.co.za
Description: Allows you to add scripts to the header / footer of your WordPress website. You can also remove the pages they appear on and add scripts to specific pages.
Version:     0.5
Author:      WP Joburg
Author URI:  http://wpjoburg.co.za
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: selective-scripts

Selective Scripts is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
Selective Scripts is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with Selective Scripts. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

class SelectiveScripts{

	protected static $_instance = null;

    public static function instance() {
		if ( is_null( self::$_instance ) ) {
		self::$_instance = new self();
		}
		return self::$_instance;
	}

	function __construct(){
		
		add_action('admin_menu', array( $this, 'add_admin_page' ) );
		add_action('admin_init', array( $this, 'init_settings' ) );

	}

	private function add_admin_page() {
		add_options_page( 'Selective Scripts Settings', 'Selective Scripts', 'manage_options', 'selective-scripts', 'render_admin_page' );
	}

	private function render_admin_page() {
		?>
		<div class="wrap">
			<h2>Selective Scripts</h2>
			Set scripts and which pages you'd like them not to appear on.
			<form action="options.php" method="post">
				<?php settings_fields( 'selective-scripts' ); ?>
				<?php do_settings_sections( 'selective_header_scripts' ); ?>
				<?php do_settings_sections( 'selective_footer_scripts' ); ?>
				 
				<input name="Submit" type="submit" value="<?php esc_attr_e( 'Save Changes'); ?>" />
			</form>
		</div> 
		<?php
	}

	private function render_adming_page() {
		?>
		<div class="wrap">
			<h2>Selective Scripts</h2>
			Set scripts and which pages you'd like them not to appear on.
			<form action="options.php" method="post">
				<?php settings_fields( 'selective_scripts' ); ?>
				<?php do_settings_sections( 'selective_header_scripts' ); ?>
				<?php do_settings_sections( 'selective_footer_scripts' ); ?>
				 
				<input name="Submit" type="submit" value="<?php esc_attr_e( 'Save Changes'); ?>" />
			</form>
		</div> 
		<?php
	}

	private function init_settings(){
		register_setting( 'selective_scripts', 'selective_scripts', 'validate_options' );
		
		add_settings_section('header_section', 'Header Scripts', 'header_section_render', 'selective-scripts');
		add_settings_field('header_scripts', 'Header Script', 'header_scripts_textarea', 'selective-scripts', 'header_section');

		add_settings_section('footer_section', 'Header Scripts', 'header_section_render', 'selective-scripts');
		add_settings_field('footer_scripts', 'Footer Script', 'footer_scripts_textarea', 'selective_footer_scripts', 'footer_section');

	}

	private function header_section_render() {
		echo '<p>Scripts that will go in the <head> of your website. Please include <script> tags.</p>';
	}

	private function footer_section_render() {
		echo '<p>Scripts that will go before the </body> of your website. Please include <script> tags.</p>';
	}

	private function header_scripts_textarea() {
		$options = get_option('selective_scripts');
		echo "<textarea id='header_scripts' name='selective_scripts[header_scripts]'>{$options['header_scripts']}'></textarea>";
	}

	private function footer_scripts_textarea() {
		$options = get_option('selective_scripts');
		echo "<textarea id='footer_scripts' name='selective_scripts[footer_scripts]'>{$options['footer_scripts']}'></textarea>";
	}

	private function validate_options($input) {
		return $input;
	}
}

SelectiveScripts::instance();