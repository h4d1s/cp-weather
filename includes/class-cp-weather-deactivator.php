<?php

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    CP_Weather
 * @subpackage CP_Weather/includes
 * @author     Choco Pixel
 */
if (!class_exists('CP_Weather_Deactivator')) :
	class CP_Weather_Deactivator
	{
		/**
		 * Deactivate
		 *
		 * Long Description.
		 *
		 * @since    1.0.0
		 */
		public static function deactivate()
		{
		}
	}
endif;
