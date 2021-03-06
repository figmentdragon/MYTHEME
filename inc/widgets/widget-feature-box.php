<?php
/**
 * Featured Box widget
 *
 * @package MYTHEME
 */
/**
 * Adds MYTHEME_Feature Textbox widget.
 */
add_action('widgets_init', 'MYTHEME_register_feature_box_widget');

function MYTHEME_register_feature_box_widget() {
    register_widget('MYTHEME_feature_box');
}

class MYTHEME_Feature_Box extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'MYTHEME_feature_box', 'AP : Feature Textbox', array(
                'description' => __('A widget to display feature textbox', 'MYTHEME')
            )
        );
    }

    /**
     * Helper function that holds widget fields
     * Array is used in update and form functions
     */
    private function widget_fields() {
        $fields = array(
            'featurebox_title' => array(
                'MYTHEME_widgets_name' => 'featurebox_title',
                'MYTHEME_widgets_title' => __('Title', 'MYTHEME'),
                'MYTHEME_widgets_field_type' => 'textfield',
            ),
            'featurebox_image' => array(
                'MYTHEME_widgets_name' => 'featurebox_image',
                'MYTHEME_widgets_title' => __('Image', 'MYTHEME'),
                'MYTHEME_widgets_field_type' => 'upload',
            ),
            'featurebox_content' => array(
                'MYTHEME_widgets_name' => 'featurebox_content',
                'MYTHEME_widgets_title' => __('Content', 'MYTHEME'),
                'MYTHEME_widgets_field_type' => 'textarea',
            ),
            'featurebox_link' => array(
                'MYTHEME_widgets_name' => 'featurebox_link',
                'MYTHEME_widgets_title' => __('Link URL', 'MYTHEME'),
                'MYTHEME_widgets_field_type' => 'url',
            ),
            'featurebox_innewtab' => array(
                'MYTHEME_widgets_name' => 'featurebox_innewtab',
                'MYTHEME_widgets_title' => __('Open Link new tab.', 'MYTHEME'),
                'MYTHEME_widgets_field_type' => 'checkbox',
            ),
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
        
        $featurebox_title = isset($instance['featurebox_title']) ? sanitize_text_field($instance['featurebox_title']) : '';
        $featurebox_image = isset($instance['featurebox_title']) ? esc_url_raw($instance['featurebox_image']) : '';
        $featurebox_content = isset($instance['featurebox_content']) ? esc_textarea($instance['featurebox_content']) : '';
        $featurebox_link = isset($instance['featurebox_link']) ? $instance['featurebox_link'] : '';

        $featurebox_innewtab = isset($instance['featurebox_innewtab']) ? $instance['featurebox_innewtab'] : 0;
        
        $attachment_id = attachment_url_to_postid($featurebox_image);
        $image_array = wp_get_attachment_image_src($attachment_id, 'MYTHEME-featbox-image');
        
        echo wp_kses_post($before_widget);
        ?>
        <div class="feature-box-container">
            <?php if($featurebox_image) : ?>
                <?php if($featurebox_link != '') : ?>
                    <a href="<?php echo esc_url($featurebox_link); ?>" <?php if($featurebox_innewtab){echo "target='__blank'"; } ?> >
                    <?php endif; ?>
                    <figure class="feat-box-img">
                        <?php
                        //print_r($image_array);
                        if($image_array !=null) { ?> 
                            <img src="<?php echo esc_url($image_array[0]) ?>" />
                        <?php }else{
                            ?>
                            <img src="<?php echo esc_url($featurebox_image) ?>" />
                            <?php
                        } ?>
                    </figure>
                    <?php if($featurebox_link != '') : ?>
                    </a>
                <?php endif; ?>
            <?php endif; ?>

            <?php if($featurebox_title != '') : ?>
                <h3 class="feat-box-title">
                    <?php if($featurebox_link != '') : ?>
                        <a href="<?php echo esc_url_raw($featurebox_link); ?>" <?php if($featurebox_innewtab){echo "target='__blank'"; } ?> >
                        <?php endif; ?>
                        <?php echo esc_html($featurebox_title); ?>
                        <?php if($featurebox_link != '') : ?>
                        </a>
                    <?php endif; ?>
                </h3>
            <?php endif; ?>

            <?php if($featurebox_content != '') : ?>
                <p class="feat-box-content"><?php echo esc_html($featurebox_content); ?></p>
            <?php endif; ?>
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

            if(isset($new_instance[$MYTHEME_widgets_name])){
            // Use helper function to get updated field values
                $instance[$MYTHEME_widgets_name] = MYTHEME_widgets_updated_field_value($widget_field, $new_instance[$MYTHEME_widgets_name]);
            }
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
