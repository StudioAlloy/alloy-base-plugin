<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       studio alloy.nl
 * @since      1.0.0
 *
 * @package    Alloy_Base_Plugin
 * @subpackage Alloy_Base_Plugin/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Alloy_Base_Plugin
 * @subpackage Alloy_Base_Plugin/includes
 * @author     Studio Alloy <contact@studioalloy.nl>
 */
class Alloy_Base_Plugin_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'alloy-base-plugin',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
