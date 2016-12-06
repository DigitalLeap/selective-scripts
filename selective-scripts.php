<?php
/*
Plugin Name: Selective Scripts
Plugin URI:  https://leapsandbounds.io
Description: Allows you to add scripts to the header / footer of your WordPress website. You can also remove the pages they appear on and add scripts to specific pages.
Version:     0.5
Author:      Seags
Author URI:  https://leapsandbounds.io
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

	protected $options = null;

    public static function instance() {
		if ( is_null( self::$_instance ) ) {
		self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct(){
		
		add_action('admin_menu', array( $this, 'add_admin_page' ) );
		add_action('admin_init', array( $this, 'init_settings' ) );

		add_action( 'wp_head', array( $this, 'output_header_scripts' ), 50 );
		add_action( 'wp_footer', array( $this, 'output_footer_scripts' ), 50 );

	}

	public function add_admin_page() {
		add_options_page( 'Selective Scripts Settings', 'Selective Scripts', 'manage_options', 'selective-scripts', array( $this, 'render_admin_page' ) );
	}

	public function render_admin_page() {

		$this->options = get_option( 'selective_scripts' );

		?>
		<div class="wrap">
			<h2>Selective Scripts</h2>
			<p>Set scripts and which pages you'd like them not to appear on.</p>
			<form action="options.php" method="post">
				<?php settings_fields( 'selective_scripts' ); ?>
				<?php do_settings_sections( 'selective-scripts' ); ?>
				 
	        	<?php submit_button(); ?>
			</form>
		</div> 
		<?php
	}

	public function init_settings(){
		register_setting( 'selective_scripts', 'selective_scripts', array( $this, 'validate_options' ) );
		
		add_settings_section('header_section', 'Header Scripts', array( $this, 'header_section_render' ), 'selective-scripts');
		add_settings_field('header_scripts', 'Header Script', array( $this, 'header_scripts_textarea' ), 'selective-scripts', 'header_section');

		add_settings_section('footer_section', 'Footer Scripts', array( $this, 'footer_section_render' ), 'selective-scripts');
		add_settings_field('footer_scripts', 'Footer Script', array( $this, 'footer_scripts_textarea' ), 'selective-scripts', 'footer_section');

	}

	public function header_section_render() {
		echo '<p>Scripts that will go in the ' . esc_html( '<head>' ) . ' of your website. Please include ' . esc_html( '<script>' ) . ' tags.</p>';
	}

	public function footer_section_render() {
		echo '<p>Scripts that will go before the ' . esc_html( '</body>' ) . ' of your website. Please include ' . esc_html( '<script>' ) . ' tags.</p>';
	}

	public function header_scripts_textarea() {
		printf(
			'<textarea id="header_scripts" name="selective_scripts[header_scripts]">%s</textarea>',
			isset( $this->options['header_scripts'] ) ? esc_attr( $this->options['header_scripts'] ) : ''
		);
	}

	public function footer_scripts_textarea() {
		printf(
			'<textarea id="header_scripts" name="selective_scripts[footer_scripts]">%s</textarea>',
			isset( $this->options['footer_scripts'] ) ? esc_attr( $this->options['footer_scripts'] ) : ''
		);
	}

	public function validate_options($input) {
		return $input;
	}

	public function output_header_scripts() {
		echo $this->get_scripts('header_scripts');
	}

	public function output_footer_scripts() {
		echo $this->get_scripts('footer_scripts');
	}

	public function get_scripts( $type ){
		$scripts = get_option( 'selective_scripts' );
		if( isset($scripts[$type]) ):
			return $scripts[$type];
		else:
			return '';
		endif;
	}
}

SelectiveScripts::instance();