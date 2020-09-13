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
if (!class_exists('CP_Weather_API_Client')) :
    class CP_Weather_API_Client
    {
        /**
         * Base URL for the WordPress site that the client is connecting to.
         *
         * @var string
         */
        const BASE_URL = "https://www.metaweather.com/api/";

        /**
         * Constructor.
         *
         */
        public function __construct()
        {
        }

        /**
         * Get weather with location ID.
         *
         * @param string  $location_id
         * @return Weather
         */
        public function get_weather($location_id)
        {
            $response = wp_remote_get(self::BASE_URL . "/location/{$location_id}/");

            if (is_wp_error($response)) {
                return __("Unable to get data.", "cp_weather");
            }

            $body = wp_remote_retrieve_body($response);
            $json_data = json_decode($body, true);

            if (empty($json_data)) {
                return __("No data from server.", "cp_weather");
            }

            $weather = new Weather();
            $weather->set_with($json_data);

            return $weather;
        }

        /**
         * Search with query.
         *
         * @param string  $query
         * @return array
         */
        public function search($query)
        {
            $response = wp_remote_get(self::BASE_URL . "/location/search/?query={$query}");

            if (is_wp_error($response)) {
                return __("Unable to get data.", "cp_weather");
            }

            $body = wp_remote_retrieve_body($response);
            $json_data = json_decode($body, true);

            $cities = [];
            foreach ($json_data as $city) {
                if ($city["location_type"] == "City") {
                    $cities[$city["woeid"]] = $city["title"];
                }
            }

            return $cities;
        }
    }
endif;
