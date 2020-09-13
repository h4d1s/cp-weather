<?php

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 *
 * @package    CP_WEATHER
 * @subpackage CP_WEATHER/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    CP_WEATHER
 * @subpackage CP_WEATHER/includes
 * @author     Choco Pixel
 */
if (!class_exists('CP_Weather_i18n')) :
	class CP_Weather_i18n
	{
		/**
		 * Load the plugin text domain for translation.
		 *
		 * @since    1.0.0
		 */
		public function load_plugin_textdomain()
		{
			load_plugin_textdomain(
				'wp-weather',
				false,
				dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
			);
		}
	}
endif;
