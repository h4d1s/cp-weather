<?php

// Exit if accessed directly
defined('ABSPATH') || exit;

/**
 * Register shortcodes for the plugin
 *
 * @since      1.0.0
 *
 * @package    CP_Weather
 * @subpackage CP_Weather/includes
 */

/**
 * Register shortcodes for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @package    CP_Weather
 * @subpackage CP_Weather/includes
 * @author     Choco Pixel
 */

if (!class_exists('CP_Weather_Shortcodes')) :
    class CP_Weather_Shortcodes
    {
        protected $api_client;
        protected $settings;

        /**
         * Initialize the class and set its properties.
         *
         * @since    1.0.0
         * @param      string    $plugin_name       The name of the plugin.
         * @param      string    $version    The version of this plugin.
         */
        public function __construct()
        {
            $this->api_client = new CP_Weather_API_Client();
            $this->settings = new CP_Weather_Settings();
        }

        /**
         * Get current weather
         *
         * @param object $atts
         *
         * @return object
         */
        public function weather($atts)
        {
            // Attributes
            extract(shortcode_atts(array(), $atts));

            $options = $this->settings->get_options();
            $weather = $this->settings->get_weather();
            $last_saved = $weather->timestamp + (30 * 60); // 30 min

            if (!is_null($weather) && $weather->timestamp != 0 && $last_saved > time()) {
                return $this->output($weather);
            }

            $weather = $this->api_client->get_weather($options["woeid"]);
            $this->settings->save_weather($weather);
            return $this->output($weather);
        }

        public function output($weather)
        {
            $options = $this->settings->get_options();
            $weather = $this->settings->get_weather();

            $scale = "";
            if ($options["scale"] == "c") {
                $scale = __("C", "cp_weather");
            } else {
                $scale = __("F", "cp_weather");
            }

            ob_start();
?>
            <div class="cpw-sc">
                <div class="cpw-container">
                    <div class="cpw-row">
                        <div class="cpw-col cpw-col--6">
                            <div class="cpw-main">
                                <span class="cpw-main__city">
                                    <?php echo esc_html($weather->city); ?>
                                </span>

                                <img src="<?php echo esc_url("https://www.metaweather.com/static/img/weather/{$weather->weather_state_abbr}.svg"); ?>" class="cpw-main__icon" alt="<?php echo esc_attr($weather->weather_state_name); ?>" />

                                <span class="cpw-main__temp">
                                    <?php echo esc_html(round($weather->the_temp)); ?>&deg;<?php echo esc_html($scale); ?>
                                </span>
                            </div>
                        </div>
                        <div class="cpw-col cpw-col--6">
                            <div class="cpw-detail">
                                <?php if (isset($options["show_min_max_temperature"]) && $options["show_min_max_temperature"]) : ?>
                                    <div class="cpw-detail__details">
                                        <span class="cpw-detail__details__description">
                                            <?php esc_html_e("Min/Max:", "cp_weather"); ?>
                                        </span>
                                        <span class="cpw-detail__details__content">
                                            <?php echo esc_html(round($weather->min_temp)); ?> &deg;<?php echo esc_html($scale); ?> /
                                            <?php echo esc_html(round($weather->max_temp)); ?> &deg;<?php echo esc_html($scale); ?>
                                        </span>
                                    </div>
                                <?php endif; ?>

                                <?php if (isset($options["show_wind_speed"]) && $options["show_wind_speed"]) : ?>
                                    <div class="cpw-detail__details">
                                        <span class="cpw-detail__details__description">
                                            <?php esc_html_e("Wind:", "cp_weather"); ?>
                                        </span>
                                        <span class="cpw-detail__details__content">
                                            <?php echo esc_html(round($weather->wind_speed)); ?> <?php echo esc_html("mph", "cp_weather"); ?>
                                        </span>
                                    </div>
                                <?php endif; ?>

                                <?php if (isset($options["show_humidity"]) && $options["show_humidity"]) : ?>
                                    <div class="cpw-detail__details">
                                        <span class="cpw-detail__details__description">
                                            <?php esc_html_e("Humidity:", "cp_weather"); ?>
                                        </span>
                                        <span class="cpw-detail__details__content">
                                            <?php echo esc_html(round($weather->humidity)); ?>%
                                        </span>
                                    </div>
                                <?php endif; ?>

                                <?php if (isset($options["show_air_pressure"]) && $options["show_air_pressure"]) : ?>
                                    <div class="cpw-detail__details">
                                        <span class="cpw-detail__details__description">
                                            <?php esc_html_e("Air pressure:", "cp_weather"); ?>
                                        </span>
                                        <span class="cpw-detail__details__content">
                                            <?php echo esc_html(round($weather->air_pressure)); ?> <?php esc_html_e("hPa", "cp_weather"); ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<?php
            return ob_get_clean();
        }
    }
endif;
