<?php

// Exit if accessed directly
defined('ABSPATH') || exit;

if (!class_exists('Weather')) :

    class Weather
    {
        // Properties

        public $woeid;
        public $city;
        public $weather_state_name;
        public $weather_state_abbr;
        public $min_temp;
        public $max_temp;
        public $air_pressure;
        public $humidity;
        public $wind_direction;
        public $wind_direction_compass;
        public $wind_speed;
        public $the_temp;
        public $timestamp;

        /**
         * Constructor
         *
         * Undocumented function long description
         *
         * @param Type $json_data JSON data from API
         **/
        public function __construct()
        {
        }

        public function set($woeid, $city, $weather_state_name, $weather_state_abbr, $min_temp, $max_temp, $air_pressure, $humidity, $wind_direction, $wind_direction_compass, $wind_speed, $the_temp, $timestamp)
        {
            $this->woeid = $woeid;
            $this->city = $city;
            $this->weather_state_name = $weather_state_name;
            $this->weather_state_abbr = $weather_state_abbr;
            $this->min_temp = $min_temp;
            $this->max_temp = $max_temp;
            $this->air_pressure = $air_pressure;
            $this->humidity = $humidity;
            $this->wind_direction = $wind_direction;
            $this->wind_direction_compass = $wind_direction_compass;
            $this->wind_speed = $wind_speed;
            $this->the_temp = $the_temp;
            $this->timestamp = $timestamp;
        }

        public function set_with($json_data)
        {
            $this->city = $json_data["title"];
            $this->woeid = $json_data["woeid"];
            $current_weather = end($json_data["consolidated_weather"]);
            $this->weather_state_name = $current_weather["weather_state_name"];
            $this->weather_state_abbr = $current_weather["weather_state_abbr"];
            $this->min_temp = $current_weather["min_temp"];
            $this->max_temp = $current_weather["max_temp"];
            $this->air_pressure = $current_weather["air_pressure"];
            $this->humidity = $current_weather["humidity"];
            $this->wind_direction = $current_weather["wind_direction"];
            $this->wind_direction_compass = $current_weather["wind_direction_compass"];
            $this->wind_speed = $current_weather["wind_speed"];
            $this->the_temp = $current_weather["the_temp"];
            $this->timestamp = time();
        }

        public function to_dictionary()
        {
            return [
                "woeid"                     => isset($this->woeid) ? $this->woeid : "",
                "city"                      => isset($this->city) ? $this->city : "",
                "weather_state_name"        => isset($this->weather_state_name) ? $this->weather_state_name : "",
                "weather_state_abbr"        => isset($this->weather_state_abbr) ? $this->weather_state_abbr : "",
                "min_temp"                  => isset($this->min_temp) ? $this->min_temp : 0,
                "max_temp"                  => isset($this->max_temp) ? $this->max_temp : 0,
                "air_pressure"              => isset($this->air_pressure) ? $this->air_pressure : 0,
                "humidity"                  => isset($this->humidity) ? $this->humidity : 0,
                "wind_direction"            => isset($this->wind_direction) ? $this->wind_direction : "",
                "wind_direction_compass"    => isset($this->wind_direction_compass) ? $this->wind_direction_compass : "",
                "wind_speed"                => isset($this->wind_speed) ? $this->wind_speed : 0,
                "the_temp"                  => isset($this->the_temp) ? $this->the_temp : 0,
                "timestamp"                 => isset($this->timestamp) ? $this->timestamp : 0,
            ];
        }
    }

endif;
