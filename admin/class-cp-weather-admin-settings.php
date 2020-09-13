<?php
// Exit if accessed directly
defined("ABSPATH") || exit;

/**
 * Admin Settings.
 *
 * This class defines all code necessary to for the plugin's settings.
 *
 * @since      1.0.0
 * @package    CP_Weather
 * @subpackage CP_Weather/includes
 * @author     Choco Pixel
 */

if (!class_exists('CP_Weather_Admin_Settings')) :

    class CP_Weather_Admin_Settings
    {
        private $options;

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
         * @since      1.0.0
         * @param      string    $plugin_name       The name of this plugin.
         * @param      string    $version    The version of this plugin.
         */
        public function __construct($plugin_name, $version)
        {
            $this->plugin_name = $plugin_name;
            $this->version = $version;

            $settings = new CP_Weather_Settings();
            $this->options = $settings->get_options();
        }

        function settings_init()
        {
            add_settings_section(
                "cp_weather_section",
                __("Settings", "cp_weather"),
                "",
                "cp_weather"
            );

            add_settings_field(
                "cp_weather_settings_field_city",
                __("City", "cp_weather"),
                array($this, "cp_weather_settings_field_city_cb"),
                "cp_weather",
                "cp_weather_section"
            );

            add_settings_field(
                "cp_weather_settings_field_scale",
                __("Temperature scale", "cp_weather"),
                array($this, "cp_weather_settings_field_scale_cb"),
                "cp_weather",
                "cp_weather_section"
            );

            add_settings_field(
                "cp_weather_settings_field_options",
                __("Show/hide", "cp_weather"),
                array($this, "cp_weather_settings_field_options_cb"),
                "cp_weather",
                "cp_weather_section"
            );

            add_settings_field(
                "cp_weather_settings_field_custom_css",
                __("Custom CSS", "cp_weather"),
                array($this, "cp_weather_settings_field_custom_css_cb"),
                "cp_weather",
                "cp_weather_section"
            );
        }

        /**
         * Callback functions
         */

        function cp_weather_settings_field_city_cb($args)
        {
            $city = "";
            if (isset($this->options["city"])) {
                $city = $this->options["city"];
            }

            $woeid = "";
            if (isset($this->options["woeid"])) {
                $woeid = $this->options["woeid"];
            }

            $option_admin = CP_Weather_Settings::OPTIONS_ADMIN;

            ob_start();
        ?>
            <input type="text" name="<?php echo esc_attr($option_admin); ?>[city]" id="input-city" value="<?php echo esc_attr($city); ?>" placeholder="<?php esc_attr_e("Type city name...", "cp_weather"); ?>" class="regular-text" />
            <input type="hidden" id="woeid" name="<?php echo esc_attr($option_admin); ?>[woeid]" value="<?php echo esc_attr($woeid); ?>">
            <p class="error-message"></p>
            <p class="description" id="input-city-description">
                <?php esc_html_e("Type and search a city form dropdown.", "cp_weather"); ?>
                <strong><?php esc_html_e("The new city will not become active until confirmed.", "cp_weather"); ?></strong>
            </p>
        <?php
            echo ob_get_clean();
        }

        function cp_weather_settings_field_scale_cb($args)
        {
            $option_admin = CP_Weather_Settings::OPTIONS_ADMIN;
            ob_start();
        ?>
            <select id="scale" name="<?php echo esc_attr($option_admin); ?>[scale]">
                <option value="c" <?php isset($this->options["scale"]) ? selected($this->options["scale"], "c", false) : "selected" ?>><?php esc_html_e("Celsius", "cp_weather"); ?></option>
                <option value="f" <?php isset($this->options["scale"]) ? selected($this->options["scale"], "f", false) : "" ?>><?php esc_html_e("Fahrenheit", "cp_weather"); ?></option>
            </select>
        <?php
            echo ob_get_clean();
        }

        function cp_weather_settings_field_options_cb($args)
        {
            $option_admin = CP_Weather_Settings::OPTIONS_ADMIN;
            ob_start();
        ?>
            <fieldset>
                <legend class="screen-reader-text"><span><?php esc_html_e("Options", "cp_weather"); ?></span></legend>
                <input type="checkbox" name="<?php echo esc_attr($option_admin); ?>[show_weather_state]" id="weather-state" value="1" <?php echo checked(1, isset($this->options['show_weather_state']) ? $this->options['show_weather_state'] : 0, false); ?> /> <label for="weather-state"><?php esc_html_e("Weather state", "cp_weather"); ?></label><br />
                <input type="checkbox" name="<?php echo esc_attr($option_admin); ?>[show_min_max_temperature]" id="min-max-temperature" value="1" <?php echo checked(1, isset($this->options['show_min_max_temperature']) ? $this->options['show_min_max_temperature'] : 0, false); ?> /> <label for="min-max-temperature"><?php esc_html_e("Min/Max temperature", "cp_weather"); ?></label><br />
                <input type="checkbox" name="<?php echo esc_attr($option_admin); ?>[show_wind_speed]" id="wind-speed" value="1" <?php echo checked(1, isset($this->options['show_wind_speed']) ? $this->options['show_wind_speed'] : 0, false); ?> /> <label for="wind-speed"><?php esc_html_e("Wind speed", "cp_weather"); ?></label><br />
                <input type="checkbox" name="<?php echo esc_attr($option_admin); ?>[show_wind_direction_compass]" id="wind-direction" value="1" <?php echo checked(1, isset($this->options['show_wind_direction_compass']) ? $this->options['show_wind_direction_compass'] : 0, false); ?> /> <label for="wind-direction-compass"><?php esc_html_e("Wind direction", "cp_weather"); ?></label><br />
                <input type="checkbox" name="<?php echo esc_attr($option_admin); ?>[show_humidity]" id="humidity" value="1" <?php echo checked(1, isset($this->options['show_humidity']) ? $this->options['show_humidity'] : 0, false); ?> /> <label for="humidity"><?php esc_html_e("Humidity", "cp_weather"); ?></label><br />
                <input type="checkbox" name="<?php echo esc_attr($option_admin); ?>[show_air_pressure]" id="air-pressure" value="1" <?php echo checked(1, isset($this->options['show_air_pressure']) ? $this->options['show_air_pressure'] : 0, false); ?> /> <label for="air-pressure"><?php esc_html_e("Air pressure", "cp_weather"); ?></label><br />
            </fieldset>
        <?php
            echo ob_get_clean();
        }

        function cp_weather_settings_field_custom_css_cb($args)
        {
            $option_admin = CP_Weather_Settings::OPTIONS_ADMIN;
            ob_start();
        ?>
            <textarea id="custom_css" name="<?php echo esc_attr($option_admin); ?>[custom_css]" rows="5" cols="50">
                <?php echo esc_textarea($this->options["custom_css"]); ?>
            </textarea>
        <?php
            echo ob_get_clean();
        }

        /**
         * This function introduces the theme options into the "Appearance" menu and into a top-level menu.
         */
        public function setup_admin_menu()
        {
            add_menu_page(
                __("CP Weather", "cp-weather"),
                __("CP Weather", "cp-weather"),
                "manage_options",
                "cp_weather",
                array($this, "options_page_html")
            );
        }

        /**
         * Display a custom menu page
         */
        function options_page_html()
        {
            // check user capabilities
            if (!current_user_can("manage_options")) {
                return;
            }

            ob_start();
        ?>
            <div class="wrap">
                <h2><?php echo esc_html(get_admin_page_title()); ?></h2>
                <?php settings_errors(); ?>

                <form method="post" action="<?php echo esc_attr(admin_url("options.php")); ?>">
                    <?php
                    settings_fields("cp_weather");
                    do_settings_sections("cp_weather");
                    submit_button(__("Save settings", "cp_weather"));
                    ?>
                </form>
            </div><!-- /.wrap -->
        <?php
            echo ob_get_clean();
        }
    }

endif;
