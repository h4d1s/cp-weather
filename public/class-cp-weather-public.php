<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    CP_Weather
 * @subpackage CP_Weather/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    CP_Weather
 * @subpackage CP_Weather/public
 * @author     Choco Pixel
 */

if (!class_exists('CP_Weather_Public')) :
	class CP_Weather_Public
	{
		protected $options;

		/**
		 * The ID of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string    $plugin_name    The ID of this plugin.
		 */
		private $plugin_name;

		/**
		 * The version of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string    $version    The current version of this plugin.
		 */
		private $version;

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    1.0.0
		 * @param      string    $plugin_name       The name of the plugin.
		 * @param      string    $version    The version of this plugin.
		 */
		public function __construct($plugin_name, $version)
		{
			$this->plugin_name = $plugin_name;
			$this->version = $version;

			$settings = new CP_Weather_Settings();
			$this->options = $settings->get_options();
			$this->load_dependencies();
		}

		/**
		 * Register the stylesheets for the public-facing side of the site.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_styles()
		{
			wp_enqueue_style($this->plugin_name . "-public", plugin_dir_url(__FILE__) . 'css/cp-weather-public.css', array(), $this->version, 'all');
			wp_enqueue_style($this->plugin_name . "-shortcodes", plugin_dir_url(__FILE__) . 'css/cp-weather-shortcodes.css', array(), $this->version, 'all');
			wp_enqueue_style($this->plugin_name . "-widget", plugin_dir_url(__FILE__) . 'css/cp-weather-widget.css', array(), $this->version, 'all');
		}

		/**
		 * Register the JavaScript for the public-facing side of the site.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_scripts()
		{
			wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/cp-weather-public.js', array('jquery'), $this->version, false);

			if (isset($this->options["custom_css"]) && !empty($this->options["custom_css"])) {
				$handle = $this->plugin_name . "-custom";
				wp_register_style($handle, false);
				wp_enqueue_style($handle);
				wp_add_inline_style($handle, $this->options["custom_css"]);
			}
		}

		public function load_dependencies()
		{
			require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-cp-weather-shortcodes.php';
			require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-cp-weather-widget.php';
		}

		public function register_widget()
		{
			$widget = new CP_Weather_Widget($this->plugin_name, $this->version);
			register_widget($widget);
		}
	}
endif;
