<?php

// Exit if accessed directly
defined("ABSPATH") || exit;

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @since      1.0.0
 *
 * @package    CP_Weather
 * @subpackage CP_Weather/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    CP_Weather
 * @subpackage CP_Weather/includes
 * @author     Choco Pixel
 */
if (!class_exists('CP_Weather')) :
	class CP_Weather
	{

		/**
		 * The loader that"s responsible for maintaining and registering all hooks that power
		 * the plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      Plugin_Name_Loader    $loader    Maintains and registers all hooks for the plugin.
		 */
		protected $loader;

		/**
		 * The unique identifier of this plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
		 */
		protected $plugin_name;

		/**
		 * The current version of the plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string    $version    The current version of the plugin.
		 */
		protected $version;

		/**
		 * Define the core functionality of the plugin.
		 *
		 * Set the plugin name and the plugin version that can be used throughout the plugin.
		 * Load the dependencies, define the locale, and set the hooks for the admin area and
		 * the public-facing side of the site.
		 *
		 * @since    1.0.0
		 */
		public function __construct()
		{
			if (defined("PLUGIN_NAME_VERSION")) {
				$this->version = PLUGIN_NAME_VERSION;
			} else {
				$this->version = "1.0.0";
			}
			$this->plugin_name = "cp-weather";

			$this->load_dependencies();
			$this->set_locale();
			$this->define_admin_hooks();
			$this->define_public_hooks();
		}

		/**
		 * Load the required dependencies for this plugin.
		 *
		 * Include the following files that make up the plugin:
		 *
		 * - CP_Weather_Loader. Orchestrates the hooks of the plugin.
		 * - CP_Weather_i18n. Defines internationalization functionality.
		 * - CP_Weather_Admin. Defines all hooks for the admin area.
		 * - CP_Weather_Public. Defines all hooks for the public side of the site.
		 *
		 * Create an instance of the loader which will be used to register the hooks
		 * with WordPress.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function load_dependencies()
		{
			/**
			 * The Weather class.
			 */
			require_once plugin_dir_path(dirname(__FILE__)) . "includes/Weather.php";

			/**
			 * The class responsible for API.
			 */
			require_once plugin_dir_path(dirname(__FILE__)) . "includes/class-cp-weather-api-client.php";

			/**
			 * The class responsible for settings.
			 */
			require_once plugin_dir_path(dirname(__FILE__)) . "includes/class-cp-weather-settings.php";

			/**
			 * The class responsible for orchestrating the actions and filters of the
			 * core plugin.
			 */
			require_once plugin_dir_path(dirname(__FILE__)) . "includes/class-cp-weather-loader.php";

			/**
			 * The class responsible for defining internationalization functionality
			 * of the plugin.
			 */
			require_once plugin_dir_path(dirname(__FILE__)) . "includes/class-cp-weather-i18n.php";

			/**
			 * The class responsible for defining all actions that occur in the admin area.
			 */
			require_once plugin_dir_path(dirname(__FILE__)) . "admin/class-cp-weather-admin.php";

			/**
			 * The class responsible for defining all actions that occur in the public-facing
			 * side of the site.
			 */
			require_once plugin_dir_path(dirname(__FILE__)) . "public/class-cp-weather-public.php";

			$this->loader = new CP_Weather_Loader();
		}

		/**
		 * Define the locale for this plugin for internationalization.
		 *
		 * Uses the CP_Weather_i18n class in order to set the domain and to register the hook
		 * with WordPress.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function set_locale()
		{
			$plugin_i18n = new CP_Weather_i18n();

			$this->loader->add_action("plugins_loaded", $plugin_i18n, "load_plugin_textdomain");
		}

		/**
		 * Register all of the hooks related to the admin area functionality
		 * of the plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function define_admin_hooks()
		{
			$plugin_admin = new CP_Weather_Admin($this->get_plugin_name(), $this->get_version());
			$plugin_admin_ajax = new CP_Weather_Ajax_Admin_Handler($this->get_plugin_name(), $this->get_version());
			$plugin_admin_settings = new CP_Weather_Admin_Settings($this->get_plugin_name(), $this->get_version());

			$this->loader->add_action("admin_enqueue_scripts", $plugin_admin, "enqueue_styles");
			$this->loader->add_action("admin_enqueue_scripts", $plugin_admin, "enqueue_scripts");

			$this->loader->add_action("admin_enqueue_scripts", $plugin_admin_ajax, "enqueue_scripts");
			$this->loader->add_action("wp_ajax_" . $plugin_admin_ajax::ACTION, $plugin_admin_ajax, "ajax_handler");
			$this->loader->add_action("wp_ajax_nopriv_" . $plugin_admin_ajax::ACTION, $plugin_admin_ajax, "ajax_handler");

			$this->loader->add_action("admin_menu", $plugin_admin_settings, "setup_admin_menu");
			$this->loader->add_action("admin_init", $plugin_admin_settings, "settings_init");
		}

		/**
		 * Register all of the hooks related to the public-facing functionality
		 * of the plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function define_public_hooks()
		{
			$plugin_public = new CP_Weather_Public($this->get_plugin_name(), $this->get_version());
			$plugin_public_shortcodes = new CP_Weather_Shortcodes($this->get_plugin_name(), $this->get_version());

			$this->loader->add_action("wp_enqueue_scripts", $plugin_public, "enqueue_styles");
			$this->loader->add_action("wp_enqueue_scripts", $plugin_public, "enqueue_scripts");
			$this->loader->add_action("widgets_init", $plugin_public, "register_widget");

			if (!shortcode_exists("cp-weather")) {
				add_shortcode("cp-weather", array($plugin_public_shortcodes, "weather"));
			}
		}

		/**
		 * Run the loader to execute all of the hooks with WordPress.
		 *
		 * @since    1.0.0
		 */
		public function run()
		{
			$this->loader->run();
		}

		/**
		 * The name of the plugin used to uniquely identify it within the context of
		 * WordPress and to define internationalization functionality.
		 *
		 * @since     1.0.0
		 * @return    string    The name of the plugin.
		 */
		public function get_plugin_name()
		{
			return $this->plugin_name;
		}

		/**
		 * The reference to the class that orchestrates the hooks with the plugin.
		 *
		 * @since     1.0.0
		 * @return    Plugin_Name_Loader    Orchestrates the hooks of the plugin.
		 */
		public function get_loader()
		{
			return $this->loader;
		}

		/**
		 * Retrieve the version number of the plugin.
		 *
		 * @since     1.0.0
		 * @return    string    The version number of the plugin.
		 */
		public function get_version()
		{
			return $this->version;
		}
	}
endif;
