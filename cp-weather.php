<?php

/**
 * Plugin Name:     CP Weather
 * Version:         1.0.0
 * Author           Choco Pixel
 * Author URI       http://choco-pixel.com
 * Text Domain:     cp_weather
 * Domain Path:     /languages
 * Description:     This plugin shows weather in your WordPress site.
 */


// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-cp-weather-activator.php
 */
if (!function_exists('activate_cp_weather')) {
    function activate_cp_weather()
    {
        require_once plugin_dir_path(__FILE__) . 'includes/class-cp-weather-activator.php';
        CP_Weather_Activator::activate();
    }
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
if (!function_exists('deactivate_cp_weather')) {
    function deactivate_cp_weather()
    {
        require_once plugin_dir_path(__FILE__) . 'includes/class-cp-weather-deactivator.php';
        CP_Weather_Deactivator::deactivate();
    }
}

register_activation_hook(__FILE__, 'activate_cp_weather');
register_deactivation_hook(__FILE__, 'deactivate_cp_weather');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-cp-weather.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
if (!function_exists('run_plugin_name')) {
    function run_plugin_name()
    {
        $plugin = new CP_Weather();
        $plugin->run();
    }
    run_plugin_name();
}
