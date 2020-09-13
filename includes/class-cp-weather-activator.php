<?php

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    CP_Weather
 * @subpackage CP_Weather/includes
 * @author     Choco Pixel
 */

if (!class_exists('CP_Weather_Activator')) :
	class CP_Weather_Activator
	{

		/**
		 * Activate 
		 *
		 * Long Description.
		 *
		 * @since    1.0.0
		 */
		public static function activate()
		{
			$settings = new CP_Weather_Settings();
		}
	}
endif;
