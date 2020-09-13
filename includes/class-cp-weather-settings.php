<?php

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Plugin's settings.
 *
 * This class defines all code necessary to save plugins settings in database.
 *
 * @since      1.0.0
 * @package    CP_Weather
 * @subpackage CP_Weather/includes
 * @author     Choco Pixel
 */
if (!class_exists('CP_Weather_Settings')) :
    class CP_Weather_Settings
    {
        const OPTIONS_DATA = "cp_weather_data";
        const OPTIONS_ADMIN = "cp_weather_options";

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
        public function __construct()
        {
            register_setting("cp_weather", self::OPTIONS_DATA);
            register_setting("cp_weather", self::OPTIONS_ADMIN);

            $this->set_default_values();
        }

        /**
         * Set default values.
         *
         */
        private function set_default_values()
        {
            $default_options_data = get_option(self::OPTIONS_DATA);
            if ($default_options_data === false) {
                $default_options_data = $this->get_defaults_data();
                update_option(self::OPTIONS_DATA, $default_options_data);
            }

            $default_options_admin = get_option(self::OPTIONS_ADMIN);
            if ($default_options_admin === false) {
                $default_options_admin = $this->get_defaults_options();
                update_option(self::OPTIONS_ADMIN, $default_options_admin);
            }
        }

        /**
         * Save weather in settings.
         */
        public function save_weather($weather)
        {
            $defaults = $this->get_defaults_data($weather);
            update_option(self::OPTIONS_DATA, $defaults);
        }

        /**
         * Get weather data from settings.
         *
         * @return Weather
         */
        public function get_weather()
        {
            $weather_data = get_option(self::OPTIONS_DATA);
            $weather = new Weather();

            if(empty($weather_data)) {
                return null;
            }

            $weather->set(
                $weather_data["woeid"],
                $weather_data["city"],
                $weather_data["weather_state_name"],
                $weather_data["weather_state_abbr"],
                $weather_data["min_temp"],
                $weather_data["max_temp"],
                $weather_data["air_pressure"],
                $weather_data["humidity"],
                $weather_data["wind_direction"],
                $weather_data["wind_direction_compass"],
                $weather_data["wind_speed"],
                $weather_data["the_temp"],
                $weather_data["timestamp"]
            );

            return $weather;
        }

        /**
         * Provides admin options.
         *
         * @return array
         */
        public function get_options()
        {
            return get_option(self::OPTIONS_ADMIN);
        }

        /**
         * Clears settings from database.
         *
         */
        public function clear()
        {
            unregister_setting($this->plugin_name, self::OPTIONS_DATA);
            unregister_setting($this->plugin_name, self::OPTIONS_ADMIN);
        }

        /**
         * Provides default values for the display Options.
         *
         * @return array
         */
        private function get_defaults_options()
        {
            $defaults = array(
                "woeid"                         => "44418",
                "city"                          => "London",
                "scale"                         => "c",
                "show_weather_state"            => 0,
                "show_min_max_temperature"      => 0,
                "show_wind_speed"               => 0,
                "show_wind_direction_compass"   => 0,
                "show_humidity"                 => 0,
                "show_air_pressure"             => 0,
                "custom_css"                    => ""
            );

            return $defaults;
        }

        /**
         * Provides default values for the data Options.
         *
         * @return array
         */
        private function get_defaults_data($weather = null)
        {
            $weather_dict = ($weather === null ? [] : $weather->to_dictionary());

            $defaults = array(
                "woeid"                     => isset($weather_dict["woeid"]) ? $weather_dict["woeid"] : "",
                "city"                      => isset($weather_dict["city"]) ? $weather_dict["city"] : "",
                "weather_state_name"        => isset($weather_dict["weather_state_name"]) ? $weather_dict["weather_state_name"] : "",
                "weather_state_abbr"        => isset($weather_dict["weather_state_abbr"]) ? $weather_dict["weather_state_abbr"] : "",
                "min_temp"                  => isset($weather_dict["min_temp"]) ? $weather_dict["min_temp"] : 0,
                "max_temp"                  => isset($weather_dict["max_temp"]) ? $weather_dict["max_temp"] : 0,
                "air_pressure"              => isset($weather_dict["air_pressure"]) ? $weather_dict["air_pressure"] : 0,
                "humidity"                  => isset($weather_dict["humidity"]) ? $weather_dict["humidity"] : 0,
                "wind_direction"            => isset($weather_dict["wind_direction"]) ? $weather_dict["wind_direction"] : "",
                "wind_direction_compass"    => isset($weather_dict["wind_direction_compass"]) ? $weather_dict["wind_direction_compass"] : "",
                "wind_speed"                => isset($weather_dict["wind_speed"]) ? $weather_dict["wind_speed"] : 0,
                "the_temp"                  => isset($weather_dict["the_temp"]) ? $weather_dict["the_temp"] : 0,
                "timestamp"                 => isset($weather_dict["timestamp"]) ? $weather_dict["timestamp"] : 0,
            );

            return $defaults;
        }
    }
endif;
