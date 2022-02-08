<?php

/**
 * Call to Action Widget
 *
 * @package MYTHEME
 */

add_action('widgets_init', 'MYTHEME_register_cta_simple_widget');

function MYTHEME_register_cta_simple_widget() {
    register_widget('MYTHEME_Cta_Simple');
}

class MYTHEME_Cta_Simple extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'MYTHEME_Cta_Simple', 'AP :  Call to Action', array(
                'description' => __('A widget that shows Simple Call to Action', 'MYTHEME')
            )
        );
    }

    /**
     * Helper function that holds widget fields
     * Array is used in update and form functions
     */
    private function widget_fields() {
        $fields = array(
            'cta_simple_title' => array(
                'MYTHEME_widgets_name' => 'cta_simple_title',
                'MYTHEME_widgets_title' => __('Title', 'MYTHEME'),
                'MYTHEME_widgets_field_type' => 'text',
            ),
            'cta_simple_btn_text' => array(
                'MYTHEME_widgets_name' => 'cta_simple_btn_text',
                'MYTHEME_widgets_title' => __('Button Text', 'MYTHEME'),
                'MYTHEME_widgets_field_type' => 'text',
            ),
            'cta_simple_btn_url' => array(
                'MYTHEME_widgets_name' => 'cta_simple_btn_url',
                'MYTHEME_widgets_title' => __('Button Url', 'MYTHEME'),
                'MYTHEME_widgets_field_type' => 'text'

            )

        );

        return $fields;
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance) {
        extract($args);
        $cta_simple_title = $instance['cta_simple_title'];
        $cta_simple_btn_text = $instance['cta_simple_btn_text'];
        $cta_simple_btn_url = $instance['cta_simple_btn_url'];
        echo wp_kses_post($before_widget);
        ?>
        <div class="cta-banner clearfix">

            <div class="cta-title_simple main-title"><?php echo esc_html($cta_simple_title);?></div>

            <?php if(!empty($cta_simple_btn_text)){?>
                <div class="banner-btn">
                    <a class="btn" href="<?php echo esc_url($cta_simple_btn_url); ?>"><?php echo esc_html($cta_simple_btn_text); ?></a>
                </div>
            <?php } ?>

        </div>
        <?php
        echo wp_kses_post($after_widget);
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param	array	$new_instance	Values just sent to be saved.
     * @param	array	$old_instance	Previously saved values from database.
     *
     * @uses	MYTHEME_widgets_updated_field_value()		defined in widget-fields.php
     *
     * @return	array Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance) {
        $instance = $old_instance;

        $widget_fields = $this->widget_fields();

        // Loop through fields
        foreach ($widget_fields as $widget_field) {

            extract($widget_field);

            // Use helper function to get updated field values
            $instance[$MYTHEME_widgets_name] = MYTHEME_widgets_updated_field_value($widget_field, $new_instance[$MYTHEME_widgets_name]);
        }

        return $instance;
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param	array $instance Previously saved values from database.
     *
     * @uses	MYTHEME_widgets_show_widget_field()		defined in widget-fields.php
     */
    public function form($instance) {
        $widget_fields = $this->widget_fields();

        // Loop through fields
        foreach ($widget_fields as $widget_field) {

            // Make array elements available as variables
            extract($widget_field);
            $MYTHEME_widgets_field_value = !empty($instance[$MYTHEME_widgets_name]) ? esc_attr($instance[$MYTHEME_widgets_name]) : '';
            MYTHEME_widgets_show_widget_field($this, $widget_field, $MYTHEME_widgets_field_value);
        }
    }

}
