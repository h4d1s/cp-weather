<?php
// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Ajax Admin Handler.
 *
 * This class defines all code necessary to run ajax for the admin.
 *
 * @since      1.0.0
 * @package    CP_Weather
 * @subpackage CP_Weather/includes
 * @author     Choco Pixel
 */
if (!class_exists('CP_Weather_Ajax_Admin_Handler')) :

    class CP_Weather_Ajax_Admin_Handler
    {
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
         * @param      string    $plugin_name       The name of this plugin.
         * @param      string    $version    The version of this plugin.
         */
        public function __construct($plugin_name, $version)
        {
            $this->plugin_name = $plugin_name;
            $this->version = $version;
        }

        /**
         * Action hook used by the AJAX class.
         *
         * @var string
         */
        const ACTION = 'search_city';

        /**
         * Action argument used by the nonce validating the AJAX request.
         *
         * @var string
         */
        const NONCE = 'cp_plugin_ajax';

        /**
         * Handles the AJAX request for my plugin.
         */
        public function ajax_handler()
        {
            check_ajax_referer(self::NONCE);

            $query = sanitize_key($_POST["city"]);

            $api = new CP_Weather_API_Client();
            $response = $api->search($query);

            if(!is_array($response)) {
                $error = new WP_Error('0001', $response);
                wp_send_json_error($error);
            } else {
                wp_send_json_success($response);
            }
        }

        /**
         * Register our AJAX JavaScript.
         */
        public function enqueue_scripts()
        {
            wp_localize_script($this->plugin_name, 'cp_ajax_data', $this->get_ajax_data());
            wp_enqueue_script($this->plugin_name);
        }

        /**
         * Get the AJAX data that WordPress needs to output.
         *
         * @return array
         */
        private function get_ajax_data()
        {
            return array(
                'ajax_url'  => admin_url('admin-ajax.php'),
                'action'    => self::ACTION,
                'nonce'     => wp_create_nonce(self::NONCE)
            );
        }
    }

endif;
