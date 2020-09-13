<?php

/**
 * Adds CP_Weather_Widget widget.
 */

if (!class_exists('CP_Weather_Widget')) :
    class CP_Weather_Widget extends WP_Widget
    {

        protected $api_client;
        protected $settings;

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
            parent::__construct(
                $this->plugin_name . '_widget', // Base ID
                esc_html__('WP Weather Widget', 'cp_weather'), // Name
                array('description' => esc_html__('A Weather Widget', 'cp_weather'),) // Args
            );

            $this->api_client = new CP_Weather_API_Client();
            $this->settings = new CP_Weather_Settings();
        }

        /**
         * Front-end display of widget.
         *
         * @see CP_Widget::widget()
         *
         * @param array $args     Widget arguments.
         * @param array $instance Saved values from database.
         */
        public function widget($args, $instance)
        {
            echo $args['before_widget'];

            if (!empty($instance['title'])) {
                echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
            }

            $options = $this->settings->get_options();
            $weather = $this->settings->get_weather();
            $last_saved = $weather->timestamp + (30 * 60); // 30 min

            if (is_null($weather) && $weather->timestamp == 0 && $last_saved < time()) {
                $weather = $this->api_client->get_weather($options["woeid"]);
                $this->settings->save_weather($weather);
            }
            $this->output($weather);

            echo $args['after_widget'];
        }

        private function output($weather)
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
            <div class="cpw-widget">
                <div class="cpw-row">
                    <div class="cpw-col cpw-col--3">
                        <img src="<?php echo esc_url("https://www.metaweather.com/static/img/weather/{$weather->weather_state_abbr}.svg"); ?>" class="cpw-widget__icon" alt="<?php echo esc_attr($weather->weather_state_name); ?>" />
                    </div>
                    <div class="cpw-col cpw-col--9">
                        <span class="cpw-d-block cpw-widget__city">
                            <?php echo esc_html($weather->city); ?>
                        </span>

                        <span class="cpw-d-block">
                            <?php echo esc_html(round($weather->the_temp)); ?>&deg;<?php echo esc_html($scale); ?>
                        </span>
                    </div>
                </div>
            </div>
        <?php
            echo ob_get_clean();
        }

        /**
         * Back-end widget form.
         *
         * @see CP_Widget::form()
         *
         * @param array $instance Previously saved values from database.
         */
        public function form($instance)
        {
            $title = !empty($instance['title']) ? $instance['title'] : esc_html__('New title', 'cp_weather');
        ?>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_attr_e('Title:', 'text_domain'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
            </p>
<?php
        }

        /**
         * Sanitize widget form values as they are saved.
         *
         * @see CP_Widget::update()
         *
         * @param array $new_instance Values just sent to be saved.
         * @param array $old_instance Previously saved values from database.
         *
         * @return array Updated safe values to be saved.
         */
        public function update($new_instance, $old_instance)
        {
            $instance = array();
            $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';

            return $instance;
        }
    }
endif;
