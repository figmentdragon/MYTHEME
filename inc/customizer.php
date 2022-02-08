<?php
/**
 * MYTHEME Theme Customizer
 *
 * @package MYTHEME
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param $wp_customize_Manager $wp_customize Theme Customizer object.
 */
function MYTHEME_customize_register( $wp_customize ) {

	/**
	 * Adds Category dropdown support to theme customizer
	 */
	class MYTHEME_Category_Dropdown_Control extends wp_customize_Control {
		private $cats = false;

		public function __construct( $manager, $id, $args = array(), $options = array() ) {
			$this->cats = get_categories( $options );
			parent::__construct( $manager, $id, $args );
		}

		public function render_content() {
			if( !empty( $this->cats ) ) {

?>
				<label>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
					<select <?php $this->link(); ?>>
						<option value="0">&mdash;Select&mdash;</option>
					<?php
					foreach( $this->cats as $cat ) {
						printf( '<option value="%s" %s>%s</option>', esc_attr($cat->term_id), selected( $this->value(), esc_attr($cat->term_id), false) , esc_attr($cat->name) );
					}

					?>
					</select>
				</label>
				<?php
			}

		}

	} // end of class

	/**
	 * Adds info content
	 */
	class MYTHEME_Customize_Info_Control extends wp_customize_Control {

		public function render_content() {
			?>
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span>
				<p><?php echo esc_html( $this->value() ); ?></p>
			</label>
		<?php
		}
	}


	$wp_customize->get_setting( 'header_image' );
	$wp_customize->get_control( 'background_color' )->section   = 'background_image';

	$wp_customize->get_section( 'background_image' )->title     = esc_html__( 'Page Background', 'storytime' );

	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector' => '.site-title a',
			'container_inclusive' => false,
			'render_callback' => 'MYTHEME_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector' => '.site-description',
			'container_inclusive' => false,
			'render_callback' => 'MYTHEME_customize_partial_blogdescription',
		) );
	}

	// Custom Display Site Title Option.
	$wp_customize->add_setting( 'MYTHEME_site_title', array(
		'default'						=> '1',
		'sanitize_callback'	=> 'MYTHEME_sanitize_checkbox',
		'transport'					=> 'postMessage',
	) );
	$wp_customize->add_control( new wp_customize_Control( $wp_customize, 'MYTHEME_site_title', array(
		'label'			=> esc_html__( 'Display Site Title', 'MYTHEME' ),
		'type'			=> 'checkbox',
		'section'		=> 'title_tagline',
		'settings'	=> 'MYTHEME_site_title',
		'priority'	=> 10,
	) ) );

    /** Moving some WordPress default section to 'General Settings' Panel **/
		$wp_customize->add_section(
				'title_tagline',
				array(
						'title'=>__('Site Identity', 'MYTHEME'), 'panel' => 'MYTHEME_panel_general_settings',
						'default'						=> '1',
						'sanitize_callback'	=> 'MYTHEME_sanitize_checkbox',
						'transport'					=> 'postMessage',
				)
		);
		$wp_customize->add_control( new wp_customize_Control( $wp_customize, 'MYTHEME_site_tagline', array(
			'label'			=> esc_html__( 'Display Site Tagline', 'MYTHEME' ),
			'type'			=> 'checkbox',
			'section'		=> 'title_tagline',
			'settings'	=> 'MYTHEME_site_tagline',
			'priority'	=> 15,
		) ) );

		$wp_customize->add_section(
        'colors',
        array(
            'title'=>__('Colors', 'MYTHEME'), 'panel' => 'MYTHEME_panel_general_settings'
        )
    );

    $wp_customize->add_section(
        'static_front_page',
        array(
            'title'=>__('Static Front Page', 'MYTHEME'), 'panel' => 'MYTHEME_panel_general_settings'
        )
    );

		$wp_customize->add_section(
			'MYTHEME_theme_options',
			array(
				'title'    => esc_html__( 'Theme Options', 'MYTHEME' ),
				'priority' => 125,
			)
		);

		$wp_customize->add_setting(
			'copyright_text',
			array(
				'default'           => '',
				'sanitize_callback' => 'wp_kses_post',
				'transport'         => 'postMessage',
			)
		);

		$wp_customize->add_control(
			'copyright_text',
			array(
				'label'   => esc_html__( 'Add copyright text in the footer.', 'MYTHEME' ),
				'section' => 'MYTHEME_theme_options',
				'type'    => 'textarea',
			)
		);

		$wp_customize->add_setting(
			'text-color',
			array(
				'default'           => '#eaeaea',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new wp_customize_Color_Control(
				$wp_customize,
				'text-color',
				array(
					'label'    => esc_html__( 'General text color', 'MYTHEME' ),
					'section'  => 'colors',
					'settings' => 'text-color',
					'priority' => 8,
				)
			)
		);

		$wp_customize->add_section(
			'layout_effects',
			array(
				'title' => __( 'MYTHEME Effects', 'MYTHEME' ),
				'priority' => 24,
			)
		);


		// Borders
		$wp_customize->add_setting(
			'borders',
			array(
				'default' => 'enable',
				'type' => 'option',
				'sanitize_callback' => 'sanitize_choices'
			)
		);

		$wp_customize->add_control(
			'borders',
			array(
				'type' => 'select',
				'label' => __( 'Borders', 'MYTHEME' ),
				'choices' => array(
					'enable' => __( 'Enable', 'MYTHEME' ),
					'disable' => __( 'Disable', 'MYTHEME' )
				),
				'settings' => 'borders',
				'section' => 'layout_effects',
				'priority' => 1
			)
		);


		// Blog img border
		$wp_customize->add_setting(
			'blog_img_border',
			array(
				'default' => 'enable',
				'type' => 'option',
				'sanitize_callback' => 'sanitize_choices'
			)
		);

		$wp_customize->add_control(
			'blog_img_border',
			array(
				'type' => 'select',
				'label' => __( 'Blog img border', 'MYTHEME' ),
				'choices' => array(
					'enable' => __( 'Enable', 'MYTHEME' ),
					'disable' => __( 'Disable', 'MYTHEME' )
				),
				'settings' => 'blog_img_border',
				'section' => 'layout_effects',
				'priority' => 2
			)
		);

		$wp_customize->add_setting(
			'border_color', array(
				'default' => '#000000',
				'type' => 'option',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_setting(
			'unique_scrollbar',
			array(
				'default' => 'enable',
				'type' => 'option',
				'sanitize_callback' => 'sanitize_choices'
			)
		);

		$wp_customize->add_control(
			'unique_scrollbar',
			array(
				'type' => 'select',
				'label' => __( 'Unique scrollbar', 'MYTHEME' ),
				'choices' => array(
					'enable' => __( 'Enable', 'MYTHEME' ),
					'disable' => __( 'Disable', 'MYTHEME' )
				),
				'settings' => 'unique_scrollbar',
				'section' => 'layout_effects',
				'priority' => 4
			)
		);

		// Cursor image
		$wp_customize->add_setting(
			'cursor_image',
			array(
				'default' => 'enable',
				'type' => 'option',
				'sanitize_callback' => 'sanitize_choices'
			)
		);

		$wp_customize->add_control(
			'cursor_image',
			array(
				'type' => 'select',
				'label' => __( 'Cursor image', 'MYTHEME' ),
				'choices' => array(
					'enable' => __( 'Enable', 'MYTHEME' ),
					'disable' => __( 'Disable', 'MYTHEME' )
				),
				'settings' => 'cursor_image',
				'section' => 'layout_effects',
				'priority' => 9
			)
		);

		$wp_customize->add_setting(
			'def_cursor_image',
			array(
				'default' => '',
				'type' => 'option',
				'sanitize_callback' => 'esc_url_raw'
			)
		);

		$wp_customize->add_control(
			new wp_customize_Image_Control(
				$wp_customize,
				'def_cursor_image',
				array(
					'label' => __( 'Default cursor image', 'MYTHEME' ),
					'section' => 'layout_effects',
					'priority' => 10,
					'settings' => 'def_cursor_image',
					'description' => __( 'Recommended size: 32*32px. Big image won`t work.', 'MYTHEME' )
				)
			)
		);

		$wp_customize->add_setting(
			'pointer_cursor_image',
			array(
				'default' => '',
				'type' => 'option',
				'sanitize_callback' => 'esc_url_raw'
			)
		);

		$wp_customize->add_control(
			new wp_customize_Image_Control(
				$wp_customize,
				'pointer_cursor_image',
				array(
					'label' => __( 'Pointer cursor image', 'MYTHEME' ),
					'section' => 'layout_effects',
					'priority' => 11,
					'settings' => 'pointer_cursor_image',
					'description' => __( 'Recommended size: 32*32px. Big image won`t work.', 'MYTHEME' )
				)
			)
		);

			$wp_customize->add_setting(
				'menu-links',
				array(
					'default'           => '#ffffff',
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new wp_customize_Color_Control(
					$wp_customize,
					'menu-links',
					array(
						'label'    => esc_html__( 'Menu links', 'MYTHEME' ),
						'section'  => 'colors',
						'settings' => 'menu-links',
						'priority' => 10,
					)
				)
			);

			$wp_customize->add_setting(
				'secondary-color',
				array(
					'default'           => '#f44336',
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new wp_customize_Color_Control(
					$wp_customize,
					'secondary-color',
					array(
						'label'    => esc_html__( 'Change the theme red color throughout', 'MYTHEME' ),
						'section'  => 'colors',
						'settings' => 'secondary-color',
						'priority' => 12,
					)
				)
			);

			$wp_customize->add_setting(
				'title-color',
				array(
					'default'           => '#ffffff',
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new wp_customize_Color_Control(
					$wp_customize,
					'title-color',
					array(
						'label'    => esc_html__( 'Titles color', 'MYTHEME' ),
						'section'  => 'colors',
						'settings' => 'title-color',
						'priority' => 14,
					)
				)
			);

    $wp_customize->add_setting( 'MYTHEME_tpl_color', array( 'sanitize_callback' => 'sanitize_hex_color', 'default' => '#df2c45' ) );
		$wp_customize->add_control( new wp_customize_Color_Control( $wp_customize, 'MYTHEME_tpl_color', array(
    		'label'      => esc_html__( 'Template Color', 'MYTHEME' ),
            'description' => esc_html__( 'Set te template color for the site', 'MYTHEME' ),
    		'section'    => 'colors',
    		'settings'   => 'MYTHEME_tpl_color',
    ) ) );

    /** Necesary Variables **/
    $pr_layout = array(
        'services' => __('Service', 'MYTHEME'),
        'portfolio' => __('Portfolio', 'MYTHEME'),
        'clients' => __('Clients', 'MYTHEME'),
        'contact' => __('Contact', 'MYTHEME'),
        'blog' => __('Blog', 'MYTHEME'),
    );

	// Logo & Favicon
	$wp_customize->add_section(
		'MYTHEME_log_favicon',
		array(
			'title' => __( 'Site Logo', 'MYTHEME' ),
            'panel' => 'MYTHEME_panel_general_settings'
		)
	);

	//Home Logo
	$wp_customize->add_setting(
		'MYTHEME_home_logo',
		array(
			'sanitize_callback' => 'esc_url_raw'
		)
	);

	$wp_customize->add_control( new wp_customize_Image_Control($wp_customize, 'MYTHEME_home_logo',array(
				'label' => __( 'Home Page Logo', 'MYTHEME' ),
				'section' => 'MYTHEME_log_favicon',
				'settings' => 'MYTHEME_home_logo',
				'priority' => 1,
				'description' => 'Shows on the home page above slider'
			)
		)
	);

	// Header Logo
	$wp_customize->add_setting(
		'MYTHEME_logo',
		array(
			'sanitize_callback' => 'esc_url_raw'
		)
	);

	$wp_customize->add_control( new wp_customize_Image_Control( $wp_customize, 'MYTHEME_logo', array(
				'label' => __( 'Header Logo', 'MYTHEME' ),
				'section' => 'MYTHEME_log_favicon',
				'settings' => 'MYTHEME_logo',
				'priority' => 5,
				'description' => 'Shows on the header'
			)

		)

	);

	// General Settings
	$wp_customize->add_panel(
		'MYTHEME_panel_general_settings',
		array(
			'title' => __( 'General Settings', 'MYTHEME' ),
			'priority' => 30
		)
	);

	$wp_customize->add_panel(
		'widget_images',
		array(
			'priority'       => 70,
			'theme_supports' => '',
			'title'          => esc_html__( 'Widget Images', 'MYTHEME' ),
			'description'    => esc_html__( 'Set background images for certain widgets.', 'MYTHEME' ),
		)
	);

	$wp_customize->add_section(
		'contact_background',
		array(
			'title'    => esc_html__( 'Contact Background', 'MYTHEME' ),
			'panel'    => 'widget_images',
			'priority' => 20,
		)
	);

	$wp_customize->add_setting(
		'contact_bg',
		array(
			'flex-width'  => true,
			'width'       => 1500,
			'flex-height' => true,
			'height'      => 900,
		)
	);

	$wp_customize->add_control(
		new wp_customize_Image_Control(
			$wp_customize,
			'contact_background_image',
			array(
				'label'    => esc_html__( 'Add Contact Background Here, the width should be approx 1500px', 'MYTHEME' ),
				'section'  => 'contact_background',
				'settings' => 'contact_bg',
			)
		)
	);

	$wp_customize->add_section(
		'sociallinks',
		array(
			'title'       => __( 'Social Links', 'MYTHEME' ),
			'description' => __( 'Add Your Social Links Here.', 'MYTHEME' ),
			'priority'    => '900',
		)
	);

	$wp_customize->add_setting(
		'MYTHEME_facebooklink',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'MYTHEME_facebooklink',
		array(
			'label'   => __( 'Facebook URL', 'MYTHEME' ),
			'section' => 'sociallinks',
		)
	);
	$wp_customize->add_setting(
		'MYTHEME_twitterlink',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'MYTHEME_twitterlink',
		array(
			'label'   => __( 'Twitter URL', 'MYTHEME' ),
			'section' => 'sociallinks',
		)
	);
	$wp_customize->add_setting(
		'MYTHEME_pinterestlink',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'MYTHEME_pinterestlink',
		array(
			'label'   => __( 'Pinterest URL', 'MYTHEME' ),
			'section' => 'sociallinks',
		)
	);
	$wp_customize->add_setting(
		'MYTHEME_instagramlink',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'MYTHEME_instagramlink',
		array(
			'label'   => __( 'Instagram URL', 'MYTHEME' ),
			'section' => 'sociallinks',
		)
	);
	$wp_customize->add_setting(
		'MYTHEME_linkedinlink',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'MYTHEME_linkedinlink',
		array(
			'label'   => __( 'LinkedIn URL', 'MYTHEME' ),
			'section' => 'sociallinks',
		)
	);
	$wp_customize->add_setting(
		'MYTHEME_youtubelink',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'MYTHEME_youtubelink',
		array(
			'label'   => __( 'YouTube URL', 'MYTHEME' ),
			'section' => 'sociallinks',
		)
	);
	$wp_customize->add_setting(
		'MYTHEME_vimeo',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'MYTHEME_vimeo',
		array(
			'label'   => __( 'Vimeo URL', 'MYTHEME' ),
			'section' => 'sociallinks',
		)
	);
	$wp_customize->add_setting(
		'MYTHEME_tumblrlink',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'MYTHEME_tumblrlink',
		array(
			'label'   => __( 'Tumblr URL', 'MYTHEME' ),
			'section' => 'sociallinks',
		)
	);
	$wp_customize->add_setting(
		'MYTHEME_flickrlink',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'MYTHEME_flickrlink',
		array(
			'label'   => __( 'Flickr URL', 'MYTHEME' ),
			'section' => 'sociallinks',
		)
	);



	// General Section
	$wp_customize->add_section(
		'MYTHEME_section_preloader',
		array(
			'title' => __( 'Preloader', 'MYTHEME' ),
			'priority' => 10,
			'panel' => 'MYTHEME_panel_general_settings'
		)
	);

	// Preloader
	$wp_customize->add_setting(
		'MYTHEME_preloader',
		array(
			'sanitize_callback' => 'MYTHEME_sanitize_checkbox'
		)
	);

	$wp_customize->add_control(
		'MYTHEME_preloader',
		array(
			'type' => 'checkbox',
			'label' => __( 'Disable Preloader', 'MYTHEME' ),
			'section' => 'MYTHEME_section_preloader',
			'settings' => 'MYTHEME_preloader',
			'priority' => 1
		)
	);

	$wp_customize->add_setting(
		'MYTHEME_blog_page',
		array(
			'default' => '0',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'MYTHEME_sanitize_integer'

		)
	);

	$wp_customize->add_control( new MYTHEME_Category_Dropdown_Control( $wp_customize, 'MYTHEME_blog_page', array(
				'label' => __( 'Choose Category for Blog', 'MYTHEME' ),
				'section' => 'MYTHEME_section_general_settings',
				'settings' => 'MYTHEME_blog_page'
			)

		)
	);

	$wp_customize->add_setting(
		'MYTHEME_blog_page',
		array(
			'default' => '0',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'MYTHEME_sanitize_integer'

		)
	);

	$wp_customize->add_control( new MYTHEME_Category_Dropdown_Control( $wp_customize, 'MYTHEME_blog_page', array(
				'label' => __( 'Choose Category for Blog', 'MYTHEME' ),
				'section' => 'MYTHEME_section_general_settings',
				'settings' => 'MYTHEME_blog_page'
			)

		)
	);

	$wp_customize->add_panel(
		'MYTHEME_panel_scroll_page_sections',
		array(
			'title' => __( 'Horizontal Scroll Page Sections', 'MYTHEME' ),
			'priority' => 40
		)
	);

	// Scroll Section Home
	$wp_customize->add_section(
		'MYTHEME_section_section_home',
		array(
			'title' => __('Scroll Section - Slider', 'MYTHEME' ),
			'priority' => 5,
			'capability' => 'edit_theme_options',
			'panel' => 'MYTHEME_panel_scroll_page_sections'
		)
	);


	// Home ID for Navigation
	$wp_customize->add_setting(
		'MYTHEME_section_home',
		array(
			'default' => 'home',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'MYTHEME_sanitize_text'
		)
	);

	$wp_customize->add_control( new MYTHEME_Customize_Info_Control( $wp_customize, 'MYTHEME_section_home', array(
			'label'	=> __( 'ID for Navigation', 'MYTHEME' ),
			'section' => 'MYTHEME_section_section_home',
			'settings' => 'MYTHEME_section_home',
			/* translators: %s : documentation link */
			'description' => sprintf(__('Use this ID to Create Scrolling Menu. <a target="_blank" href="%s">How to create Menu?</a>', 'MYTHEME'), esc_url('http://accesspressthemes.com/documentation/MYTHEME/#!/scrollable_menu'))
			)
		)
	);

	$wp_customize->add_setting(
		'MYTHEME_slider_category',
		array(
			'default' => '0',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'absint'

		)
	);

	$wp_customize->add_control( new MYTHEME_Category_Dropdown_Control( $wp_customize, 'MYTHEME_slider_category', array(
				'label' => __( 'Choose Category for Slider', 'MYTHEME' ),
				'section' => 'MYTHEME_section_section_home',
				'settings' => 'MYTHEME_slider_category'
			)

		)
	);

	// Slider Pause
	$wp_customize->add_setting( 'MYTHEME_slider_pause',array( 'default' => '4000', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'MYTHEME_sanitize_integer'));
	$wp_customize->add_control(
		'MYTHEME_slider_pause',
		array(
			'label'	=> __( 'Slider Pause Duration', 'MYTHEME' ),
			'type' => 'text',
			'settings' => 'MYTHEME_slider_pause',
			'section' => 'MYTHEME_section_section_home'
		)
	);

	// Slider Caption
	$wp_customize->add_setting( 'MYTHEME_slider_caption', array( 'default' => 'yes', 'sanitize_callback' => 'MYTHEME_sanitize_slider_settings'));
	$wp_customize->add_control(
		'MYTHEME_slider_caption',
		array(
			'label' => __( 'Show Slider Caption', 'MYTHEME' ),
			'type' => 'radio',
			'settings' => 'MYTHEME_slider_caption',
			'section' => 'MYTHEME_section_section_home',
			'priority' => 20,
			'choices' => array(
				'yes' => __( 'Yes', 'MYTHEME' ),
				'no' => __( 'No', 'MYTHEME' )
			)
		)
	);

	// Display Caption in Mobile Devices
	$wp_customize->add_setting( 'MYTHEME_disp_caption_in_mobile', array( 'default' => 0, 'sanitize_callback' => 'absint'));
	$wp_customize->add_control(
		'MYTHEME_disp_caption_in_mobile',
		array(
			'label' => __( 'Display Caption Description Text in Mobile', 'MYTHEME' ),
			'type' => 'checkbox',
			'section' => 'MYTHEME_section_section_home',
			'priority' => 21,
		)
	);

	// Section 1
	$wp_customize->add_section(
		'MYTHEME_sec_1',
		array(
			'title' => __('Scroll Section 1', 'MYTHEME' ),
			'priority' => 10,
			'capability' => 'edit_theme_options',
			'panel' => 'MYTHEME_panel_scroll_page_sections'
		)
	);

	$wp_customize->add_setting( 'MYTHEME_section_1_disable', array( 'default' => '', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'absint' ));
	$wp_customize->add_control(
		'MYTHEME_section_1_disable',
		array(
			'label'	=> __( 'Disable Section', 'MYTHEME' ),
			'type' => 'checkbox',
			'section' => 'MYTHEME_sec_1',
			'settings' => 'MYTHEME_section_1_disable'
		)
	);

	$wp_customize->add_setting( 'MYTHEME_section_1', array( 'default' => 'section-1', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'sanitize_title_with_dashes' ));
	$wp_customize->add_control(
		'MYTHEME_section_1',
		array(
			'label'	=> __( 'ID for Navigation', 'MYTHEME' ),
			'type' => 'text',
			'section' => 'MYTHEME_sec_1',
			'settings' => 'MYTHEME_section_1',
			/* translators: %s : documentation link */
			'description' => sprintf(__('Use this ID to Create Scrolling Menu. <a target="_blank" href="%s">How to create Menu?</a>', 'MYTHEME'), esc_url('http://accesspressthemes.com/documentation/MYTHEME/#!/scrollable_menu'))
		)
	);

    $wp_customize->add_setting( 'MYTHEME_section_1_type', array( 'default' => 'page', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'sanitize_text_field' ));
	$wp_customize->add_control(
		'MYTHEME_section_1_type',
		array(
			'label'	=> __( 'Section Layout', 'MYTHEME' ),
			'type' => 'select',
			'section' => 'MYTHEME_sec_1',
			'choices' => array(
                'page' => __('Page Layout', 'MYTHEME'),
                'prlayout' => __('Predefined Layout', 'MYTHEME'),
            ),
            'description' => __( 'Choose either to display Page or Predefined Layout', 'MYTHEME' )
		)
	);

	$wp_customize->add_setting( 'MYTHEME_section_page_1', array( 'default' => '0', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'absint' ));
	$wp_customize->add_control(
		'MYTHEME_section_page_1',
		array(
			'label'	=> __( 'Choose a Page for Section', 'MYTHEME' ),
			'type' => 'dropdown-pages',
			'section' => 'MYTHEME_sec_1',
			'settings' => 'MYTHEME_section_page_1',
            'active_callback' => 'MYTHEME_section1_pg_layout',
		)
	);

    $wp_customize->add_setting( 'MYTHEME_section_layout1', array( 'default' => 'services', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'sanitize_text_field' ));
	$wp_customize->add_control(
		'MYTHEME_section_layout1',
		array(
			'label'	=> __( 'Layout', 'MYTHEME' ),
			'type' => 'select',
			'section' => 'MYTHEME_sec_1',
            'choices' => $pr_layout,
            'active_callback' => 'MYTHEME_section1_pr_layout',
            'description' => __( 'Navigate back and Configure the Predefined Layout', 'MYTHEME' )
		)
	);

	// Section 2
	$wp_customize->add_section(
		'MYTHEME_sec_2',
		array(
			'title' => __('Scroll Section 2', 'MYTHEME' ),
			'priority' => 10,
			'capability' => 'edit_theme_options',
			'panel' => 'MYTHEME_panel_scroll_page_sections'
		)
	);

	$wp_customize->add_setting( 'MYTHEME_section_2_disable', array( 'default' => '', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'absint' ));
	$wp_customize->add_control(
		'MYTHEME_section_2_disable',
		array(
			'label'	=> __( 'Disable Section', 'MYTHEME' ),
			'type' => 'checkbox',
			'section' => 'MYTHEME_sec_2',
			'settings' => 'MYTHEME_section_2_disable'
		)
	);

	$wp_customize->add_setting( 'MYTHEME_section_2', array( 'default' => 'section-2', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'sanitize_title_with_dashes' ));
	$wp_customize->add_control(
		'MYTHEME_section_2',
		array(
			'label'	=> __( 'ID for Navigation', 'MYTHEME' ),
			'type' => 'text',
			'section' => 'MYTHEME_sec_2',
			'settings' => 'MYTHEME_section_2',
			/* translators: %s : documentation link */
			'description' => sprintf(__('Use this ID to Create Scrolling Menu. <a target="_blank" href="%s">How to create Menu?</a>', 'MYTHEME'), esc_url('http://accesspressthemes.com/documentation/MYTHEME/#!/scrollable_menu'))
		)
	);

    $wp_customize->add_setting( 'MYTHEME_section_2_type', array( 'default' => 'page', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'sanitize_text_field' ));
	$wp_customize->add_control(
		'MYTHEME_section_2_type',
		array(
			'label'	=> __( 'Section Layout', 'MYTHEME' ),
			'type' => 'select',
			'section' => 'MYTHEME_sec_2',
			'choices' => array(
                'page' => __('Page Layout', 'MYTHEME'),
                'prlayout' => __('Predefined Layout', 'MYTHEME'),
            ),
            'description' => __( 'Choose either to display Page or Predefined Layout', 'MYTHEME' )
		)
	);

	$wp_customize->add_setting( 'MYTHEME_section_page_2', array( 'default' => '0', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'absint' ));
	$wp_customize->add_control(
		'MYTHEME_section_page_2',
		array(
			'label'	=> __( 'Choose a Page for Section', 'MYTHEME' ),
			'type' => 'dropdown-pages',
			'section' => 'MYTHEME_sec_2',
			'settings' => 'MYTHEME_section_page_2',
            'active_callback' => 'MYTHEME_section2_pg_layout'
		)
	);

    $wp_customize->add_setting( 'MYTHEME_section_layout2', array( 'default' => 'services', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'sanitize_text_field' ));
	$wp_customize->add_control(
		'MYTHEME_section_layout2',
		array(
			'label'	=> __( 'Choose Layout', 'MYTHEME' ),
			'type' => 'select',
			'section' => 'MYTHEME_sec_2',
            'choices' => $pr_layout,
            'active_callback' => 'MYTHEME_section2_pr_layout',
            'description' => __( 'Navigate back and Configure the Predefined Layout', 'MYTHEME' )
		)
	);

	// Section 3
	$wp_customize->add_section(
		'MYTHEME_sec_3',
		array(
			'title' => __('Scroll Section 3', 'MYTHEME' ),
			'priority' => 10,
			'capability' => 'edit_theme_options',
			'panel' => 'MYTHEME_panel_scroll_page_sections'
		)
	);

	$wp_customize->add_setting( 'MYTHEME_section_3_disable', array( 'default' => '', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'absint' ));
	$wp_customize->add_control(
		'MYTHEME_section_3_disable',
		array(
			'label'	=> __( 'Disable Section', 'MYTHEME' ),
			'type' => 'checkbox',
			'section' => 'MYTHEME_sec_3',
			'settings' => 'MYTHEME_section_3_disable'
		)
	);

	$wp_customize->add_setting( 'MYTHEME_section_3', array( 'default' => 'section-3', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'sanitize_title_with_dashes' ));
	$wp_customize->add_control(
		'MYTHEME_section_3',
		array(
			'label'	=> __( 'ID for Navigation', 'MYTHEME' ),
			'type' => 'text',
			'section' => 'MYTHEME_sec_3',
			'settings' => 'MYTHEME_section_3',
			/* translators: %s : documentation link */
			'description' => sprintf(__('Use this ID to Create Scrolling Menu. <a target="_blank" href="%s">How to create Menu?</a>', 'MYTHEME'), esc_url('http://accesspressthemes.com/documentation/MYTHEME/#!/scrollable_menu'))
		)
	);

    $wp_customize->add_setting( 'MYTHEME_section_3_type', array( 'default' => 'page', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'sanitize_text_field' ));
	$wp_customize->add_control(
		'MYTHEME_section_3_type',
		array(
			'label'	=> __( 'Section Layout', 'MYTHEME' ),
			'type' => 'select',
			'section' => 'MYTHEME_sec_3',
			'choices' => array(
                'page' => __('Page Layout', 'MYTHEME'),
                'prlayout' => __('Predefined Layout', 'MYTHEME'),
            ),
            'description' => __( 'Choose either to display Page or Predefined Layout', 'MYTHEME' )
		)
	);

	$wp_customize->add_setting( 'MYTHEME_section_page_3', array( 'default' => '0', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'absint' ));
	$wp_customize->add_control(
		'MYTHEME_section_page_3',
		array(
			'label'	=> __( 'Choose a Page for Section', 'MYTHEME' ),
			'type' => 'dropdown-pages',
			'section' => 'MYTHEME_sec_3',
			'settings' => 'MYTHEME_section_page_3',
            'active_callback' => 'MYTHEME_section3_pg_layout',
		)
	);

    $wp_customize->add_setting( 'MYTHEME_section_layout3', array( 'default' => 'services', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'sanitize_text_field' ));
	$wp_customize->add_control(
		'MYTHEME_section_layout3',
		array(
			'label'	=> __( 'Choose Layout', 'MYTHEME' ),
			'type' => 'select',
			'section' => 'MYTHEME_sec_3',
            'choices' => $pr_layout,
            'active_callback' => 'MYTHEME_section3_pr_layout',
            'description' => __( 'Navigate back and Configure the Predefined Layout', 'MYTHEME' )
		)
	);

	// Section 4
	$wp_customize->add_section(
		'MYTHEME_sec_4',
		array(
			'title' => __('Scroll Section 4', 'MYTHEME' ),
			'priority' => 10,
			'capability' => 'edit_theme_options',
			'panel' => 'MYTHEME_panel_scroll_page_sections'
		)
	);

	$wp_customize->add_setting( 'MYTHEME_section_4_disable', array( 'default' => '', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'absint' ));
	$wp_customize->add_control(
		'MYTHEME_section_4_disable',
		array(
			'label'	=> __( 'Disable Section', 'MYTHEME' ),
			'type' => 'checkbox',
			'section' => 'MYTHEME_sec_4',
			'settings' => 'MYTHEME_section_4_disable'
		)
	);

	$wp_customize->add_setting( 'MYTHEME_section_4', array( 'default' => 'section-4', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'sanitize_title' ));
	$wp_customize->add_control(
		'MYTHEME_section_4',
		array(
			'label'	=> __( 'ID for Navigation', 'MYTHEME' ),
			'type' => 'text',
			'section' => 'MYTHEME_sec_4',
			'settings' => 'MYTHEME_section_4',
			/* translators: %s : documentation link */
			'description' => sprintf(__('Use this ID to Create Scrolling Menu. <a target="_blank" href="%s">How to create Menu?</a>', 'MYTHEME'), esc_url('http://accesspressthemes.com/documentation/MYTHEME/#!/scrollable_menu'))
		)
	);

    $wp_customize->add_setting( 'MYTHEME_section_4_type', array( 'default' => 'page', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'sanitize_text_field' ));
	$wp_customize->add_control(
		'MYTHEME_section_4_type',
		array(
			'label'	=> __( 'Section Layout', 'MYTHEME' ),
			'type' => 'select',
			'section' => 'MYTHEME_sec_4',
			'choices' => array(
                'page' => __('Page Layout', 'MYTHEME'),
                'prlayout' => __('Predefined Layout', 'MYTHEME'),
            ),
            'description' => __( 'Choose either to display Page or Predefined Layout', 'MYTHEME' )
		)
	);

	$wp_customize->add_setting( 'MYTHEME_section_page_4', array( 'default' => '0', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'absint' ));
	$wp_customize->add_control(
		'MYTHEME_section_page_4',
		array(
			'label'	=> __( 'Choose a Page for Section', 'MYTHEME' ),
			'type' => 'dropdown-pages',
			'section' => 'MYTHEME_sec_4',
			'settings' => 'MYTHEME_section_page_4',
            'active_callback' => 'MYTHEME_section4_pg_layout',
		)
	);

    $wp_customize->add_setting( 'MYTHEME_section_layout4', array( 'default' => 'services', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'sanitize_text_field' ));
	$wp_customize->add_control(
		'MYTHEME_section_layout4',
		array(
			'label'	=> __( 'Choose Layout', 'MYTHEME' ),
			'type' => 'select',
			'section' => 'MYTHEME_sec_4',
            'choices' => $pr_layout,
            'active_callback' => 'MYTHEME_section4_pr_layout',
            'description' => __( 'Navigate back and Configure the Predefined Layout', 'MYTHEME' )
		)
	);

	// Section 5
	$wp_customize->add_section(
		'MYTHEME_sec_5',
		array(
			'title' => __('Scroll Section 5', 'MYTHEME' ),
			'priority' => 10,
			'capability' => 'edit_theme_options',
			'panel' => 'MYTHEME_panel_scroll_page_sections'
		)
	);

	$wp_customize->add_setting( 'MYTHEME_section_5_disable', array( 'default' => '', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'absint' ));
	$wp_customize->add_control(
		'MYTHEME_section_5_disable',
		array(
			'label'	=> __( 'Disable Section', 'MYTHEME' ),
			'type' => 'checkbox',
			'section' => 'MYTHEME_sec_5',
			'settings' => 'MYTHEME_section_5_disable'
		)
	);

	$wp_customize->add_setting( 'MYTHEME_section_5', array( 'default' => 'section-5', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'sanitize_title_with_dashes' ));
	$wp_customize->add_control(
		'MYTHEME_section_5',
		array(
			'label'	=> __( 'ID for Navigation', 'MYTHEME' ),
			'type' => 'text',
			'section' => 'MYTHEME_sec_5',
			'settings' => 'MYTHEME_section_5',
			/* translators: %s : documentation link */
			'description' => sprintf(__('Use this ID to Create Scrolling Menu. <a target="_blank" href="%s">How to create Menu?</a>', 'MYTHEME'), esc_url('http://accesspressthemes.com/documentation/MYTHEME/#!/scrollable_menu'))
		)
	);

    $wp_customize->add_setting( 'MYTHEME_section_5_type', array( 'default' => 'page', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'sanitize_text_field' ));
	$wp_customize->add_control(
		'MYTHEME_section_5_type',
		array(
			'label'	=> __( 'Section Layout', 'MYTHEME' ),
			'type' => 'select',
			'section' => 'MYTHEME_sec_5',
			'choices' => array(
                'page' => __('Page Layout', 'MYTHEME'),
                'prlayout' => __('Predefined Layout', 'MYTHEME'),
            ),
            'description' => __( 'Choose either to display Page or Predefined Layout', 'MYTHEME' )
		)
	);

	$wp_customize->add_setting( 'MYTHEME_section_page_5', array( 'default' => '0', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'absint' ));
	$wp_customize->add_control(
		'MYTHEME_section_page_5',
		array(
			'label'	=> __( 'Choose a Page for Section', 'MYTHEME' ),
			'type' => 'dropdown-pages',
			'section' => 'MYTHEME_sec_5',
			'settings' => 'MYTHEME_section_page_5',
            'active_callback' => 'MYTHEME_section5_pg_layout',
		)
	);

    $wp_customize->add_setting( 'MYTHEME_section_layout5', array( 'default' => 'services', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'sanitize_text_field' ));
	$wp_customize->add_control(
		'MYTHEME_section_layout5',
		array(
			'label'	=> __( 'Choose Layout', 'MYTHEME' ),
			'type' => 'select',
			'section' => 'MYTHEME_sec_5',
            'choices' => $pr_layout,
            'active_callback' => 'MYTHEME_section5_pr_layout',
            'description' => __( 'Navigate back and Configure the Predefined Layout', 'MYTHEME' )
		)
	);

	// Section 6
	$wp_customize->add_section(
		'MYTHEME_sec_6',
		array(
			'title' => __('Scroll Section 6', 'MYTHEME' ),
			'priority' => 10,
			'capability' => 'edit_theme_options',
			'panel' => 'MYTHEME_panel_scroll_page_sections'
		)
	);

	$wp_customize->add_setting( 'MYTHEME_section_6_disable', array( 'default' => '', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'absint' ));
	$wp_customize->add_control(
		'MYTHEME_section_6_disable',
		array(
			'label'	=> __( 'Disable Section', 'MYTHEME' ),
			'type' => 'checkbox',
			'section' => 'MYTHEME_sec_6',
			'settings' => 'MYTHEME_section_6_disable'
		)
	);

	$wp_customize->add_setting( 'MYTHEME_section_6', array( 'default' => 'section-6', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'sanitize_title' ));
	$wp_customize->add_control(
		'MYTHEME_section_6',
		array(
			'label'	=> __( 'ID for Navigation', 'MYTHEME' ),
			'type' => 'text',
			'section' => 'MYTHEME_sec_6',
			'settings' => 'MYTHEME_section_6',
			/* translators: %s : documentation link */
			'description' => sprintf(__('Use this ID to Create Scrolling Menu. <a target="_blank" href="%s">How to create Menu?</a>', 'MYTHEME'), esc_url('http://accesspressthemes.com/documentation/MYTHEME/#!/scrollable_menu') )
		)
	);

    $wp_customize->add_setting( 'MYTHEME_section_6_type', array( 'default' => 'page', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'sanitize_text_field' ));
	$wp_customize->add_control(
		'MYTHEME_section_6_type',
		array(
			'label'	=> __( 'Section Layout', 'MYTHEME' ),
			'type' => 'select',
			'section' => 'MYTHEME_sec_6',
			'choices' => array(
                'page' => __('Page Layout', 'MYTHEME'),
                'prlayout' => __('Predefined Layout', 'MYTHEME'),
            ),
			'description' => __( 'Choose either to display Page or Predefined Layout', 'MYTHEME' )
		)
	);

	$wp_customize->add_setting( 'MYTHEME_section_page_6', array( 'default' => '0', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'absint' ));
	$wp_customize->add_control(
		'MYTHEME_section_page_6',
		array(
			'label'	=> __( 'Choose a Page for Section', 'MYTHEME' ),
			'type' => 'dropdown-pages',
			'section' => 'MYTHEME_sec_6',
			'settings' => 'MYTHEME_section_page_6',
            'active_callback' => 'MYTHEME_section6_pg_layout',
		)
	);

    $wp_customize->add_setting( 'MYTHEME_section_layout6', array( 'default' => 'services', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'sanitize_text_field' ));
	$wp_customize->add_control(
		'MYTHEME_section_layout6',
		array(
			'label'	=> __( 'Choose Layout', 'MYTHEME' ),
			'type' => 'select',
			'section' => 'MYTHEME_sec_6',
            'choices' => $pr_layout,
            'active_callback' => 'MYTHEME_section6_pr_layout',
            'description' => __( 'Navigate back and Configure the Predefined Layout', 'MYTHEME' )
		)
	);

    // Section 7
	$wp_customize->add_section(
		'MYTHEME_sec_7',
		array(
			'title' => __('Scroll Section 7', 'MYTHEME' ),
			'priority' => 10,
			'capability' => 'edit_theme_options',
			'panel' => 'MYTHEME_panel_scroll_page_sections'
		)
	);

	$wp_customize->add_setting( 'MYTHEME_section_7_disable', array( 'default' => '', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'absint' ));
	$wp_customize->add_control(
		'MYTHEME_section_7_disable',
		array(
			'label'	=> __( 'Disable Section', 'MYTHEME' ),
			'type' => 'checkbox',
			'section' => 'MYTHEME_sec_7',
			'settings' => 'MYTHEME_section_7_disable'
		)
	);

	$wp_customize->add_setting( 'MYTHEME_section_7', array( 'default' => 'section-7', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'sanitize_title_with_dashes' ));
	$wp_customize->add_control(
		'MYTHEME_section_7',
		array(
			'label'	=> __( 'ID for Navigation', 'MYTHEME' ),
			'type' => 'text',
			'section' => 'MYTHEME_sec_7',
			/* translators: %s : documentation link */
			'description' => sprintf(__('Use this ID to Create Scrolling Menu. <a target="_blank" href="%s">How to create Menu?</a>', 'MYTHEME'), esc_url('http://accesspressthemes.com/documentation/MYTHEME/#!/scrollable_menu') )
		)
	);

    $wp_customize->add_setting( 'MYTHEME_section_7_type', array( 'default' => 'page', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'sanitize_text_field' ));
	$wp_customize->add_control(
		'MYTHEME_section_7_type',
		array(
			'label'	=> __( 'Section Layout', 'MYTHEME' ),
			'type' => 'select',
			'section' => 'MYTHEME_sec_7',
			'choices' => array(
                'page' => __('Page Layout', 'MYTHEME'),
                'prlayout' => __('Predefined Layout', 'MYTHEME'),
            ),
            'description' => __( 'Choose either to display Page or Predefined Layout', 'MYTHEME' )
		)
	);

	$wp_customize->add_setting( 'MYTHEME_section_page_7', array( 'default' => '0', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'absint' ));
	$wp_customize->add_control(
		'MYTHEME_section_page_7',
		array(
			'label'	=> __( 'Choose a Page for Section', 'MYTHEME' ),
			'type' => 'dropdown-pages',
			'section' => 'MYTHEME_sec_7',
			'settings' => 'MYTHEME_section_page_7',
            'active_callback' => 'MYTHEME_section7_pg_layout',
		)
	);

    $wp_customize->add_setting( 'MYTHEME_section_layout7', array( 'default' => 'services', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'sanitize_text_field' ));
	$wp_customize->add_control(
		'MYTHEME_section_layout7',
		array(
			'label'	=> __( 'Choose Layout', 'MYTHEME' ),
			'type' => 'select',
			'section' => 'MYTHEME_sec_7',
            'choices' => $pr_layout,
            'active_callback' => 'MYTHEME_section7_pr_layout',
            'description' => __( 'Navigate back and Configure the Predefined Layout', 'MYTHEME' )
		)
	);

	/** Service Section Settings **/
    $wp_customize->add_section(
		'MYTHEME_service_settings',
		array(
			'title' => __('Service Settings', 'MYTHEME' ),
			'priority' => 51,
			'capability' => 'edit_theme_options',
		)
	);

    // Section Title
	$wp_customize->add_setting( 'MYTHEME_service_title', array( 'default' => 'Sample Title', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'MYTHEME_allow_span' ));
	$wp_customize->add_control(
		'MYTHEME_service_title',
		array(
			'label'	=> __( 'Section Title', 'MYTHEME' ),
			'type' => 'text',
			'section' => 'MYTHEME_service_settings',
		)
	);

	// Service 1 Page
	$wp_customize->add_setting( 'MYTHEME_service_block_1_page', array( 'default' => '0', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'absint' ));
	$wp_customize->add_control(
		'MYTHEME_service_block_1_page',
		array(
			'label'	=> __( 'Service 1', 'MYTHEME' ),
			'type' => 'dropdown-pages',
			'section' => 'MYTHEME_service_settings',
		)
	);

	// Service 2 Page
	$wp_customize->add_setting( 'MYTHEME_service_block_2_page', array( 'default' => '0', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'absint' ));
	$wp_customize->add_control(
		'MYTHEME_service_block_2_page',
		array(
			'label'	=> __( 'Service 2', 'MYTHEME' ),
			'type' => 'dropdown-pages',
			'section' => 'MYTHEME_service_settings',
		)
	);

	// Service 3 Page
	$wp_customize->add_setting( 'MYTHEME_service_block_3_page', array( 'default' => '0', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'absint' ));
	$wp_customize->add_control(
		'MYTHEME_service_block_3_page',
		array(
			'label'	=> __( 'Service 3', 'MYTHEME' ),
			'type' => 'dropdown-pages',
			'section' => 'MYTHEME_service_settings',
		)
	);

	// Service 4 Page
	$wp_customize->add_setting( 'MYTHEME_service_block_4_page', array( 'default' => '0', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'absint' ));
	$wp_customize->add_control(
		'MYTHEME_service_block_4_page',
		array(
			'label'	=> __( 'Service 4', 'MYTHEME' ),
			'type' => 'dropdown-pages',
			'section' => 'MYTHEME_service_settings',
		)
	);

	$wp_customize->add_control(
		'MYTHEME_open_service_newtab',
		array(
			'type' => 'checkbox',
			'label' => __( 'Open Service Link in new tab', 'MYTHEME' ),
			'section' => 'MYTHEME_service_settings',
			'settings' => 'MYTHEME_open_service_newtab',
		)
	);

    /** Portfolio Settings **/
    $wp_customize->add_section(
		'MYTHEME_portfolio_settings',
		array(
			'title' => __('Portfolio Settings', 'MYTHEME' ),
			'priority' => 52,
			'capability' => 'edit_theme_options',
		)
	);

    $wp_customize->add_setting( 'MYTHEME_portfolio_title', array( 'default' => 'What we have done - <span>Our Works</span>', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'MYTHEME_allow_span' ));
    $wp_customize->add_control(
		'MYTHEME_portfolio_title',
		array(
			'label'	=> __( 'Section Title', 'MYTHEME' ),
			'type' => 'text',
			'section' => 'MYTHEME_portfolio_settings',
		)
	);

    $wp_customize->add_setting( 'MYTHEME_portfolio_page', array( 'default' => '0', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'MYTHEME_sanitize_integer' ));
	$wp_customize->add_control( new MYTHEME_Category_Dropdown_Control( $wp_customize,
        'MYTHEME_portfolio_page',
        array(
				'label' => __( 'Choose Category for Portfolio', 'MYTHEME' ),
				'section' => 'MYTHEME_portfolio_settings',
				'settings' => 'MYTHEME_portfolio_page'
			)
		)
	);

    /** Clients Settings **/
    $wp_customize->add_section(
		'MYTHEME_clients_settings',
		array(
			'title' => __('Clients Settings', 'MYTHEME' ),
			'priority' => 52,
			'capability' => 'edit_theme_options',
		)
	);

    $wp_customize->add_setting( 'MYTHEME_client_title', array( 'default' => 'We Have Some - <span>Great Clients</span>', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'MYTHEME_allow_span' ));
    $wp_customize->add_control(
		'MYTHEME_client_title',
		array(
			'label'	=> __( 'Section Title', 'MYTHEME' ),
			'type' => 'text',
			'section' => 'MYTHEME_clients_settings',
		)
	);

    $wp_customize->add_setting( 'MYTHEME_clients_category', array( 'default' => '0', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'absint' ));
	$wp_customize->add_control( new MYTHEME_Category_Dropdown_Control( $wp_customize,
        'MYTHEME_clients_category',
        array(
				'label' => __( 'Choose Category for Clients', 'MYTHEME' ),
				'section' => 'MYTHEME_clients_settings',
				'settings' => 'MYTHEME_clients_category',
			)

		)
	);

    $wp_customize->add_setting( 'MYTHEME_linkto_inpage', array( 'default' => 1, 'capability' => 'edit_theme_options', 'sanitize_callback' => 'MYTHEME_sanitize_checkbox' ));
	$wp_customize->add_control(
		'MYTHEME_linkto_inpage',
		array(
			'type' => 'checkbox',
			'label' => __( 'Link to Inner Page', 'MYTHEME' ),
			'section' => 'MYTHEME_clients_settings',
		)
	);

    /** Contact Settings **/
    $wp_customize->add_section(
		'MYTHEME_contact_settings',
		array(
			'title' => __('Contact Settings', 'MYTHEME' ),
			'priority' => 52,
			'capability' => 'edit_theme_options',
		)
	);

    $wp_customize->add_setting( 'MYTHEME_contact_title', array( 'default' => "We'd Love to - <span>Hear From You</span>", 'capability' => 'edit_theme_options', 'sanitize_callback' => 'MYTHEME_allow_span' ));
    $wp_customize->add_control(
		'MYTHEME_contact_title',
		array(
			'label'	=> __( 'Section Title', 'MYTHEME' ),
			'type' => 'text',
			'section' => 'MYTHEME_contact_settings',
		)
	);

    $wp_customize->add_setting( 'MYTHEME_contact_page', array( 'default' => '0', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'absint' ));
	$wp_customize->add_control(
		'MYTHEME_contact_page',
		array(
			'label'	=> __( 'Select Page', 'MYTHEME' ),
			'type' => 'dropdown-pages',
			'section' => 'MYTHEME_contact_settings',
		)
	);

    $wp_customize->add_setting( 'MYTHEME_map_info', array( 'sanitize_callback' => 'sanitize_text_field' ));
    $wp_customize->add_control( new wp_customize_Help_Control( $wp_customize, 'MYTHEME_map_info', array(
            'section' => 'MYTHEME_contact_settings',
            'settings' => 'MYTHEME_map_info',
            'input_attrs' => array(
                'info' => '<p>Add the <span style="text-decoration: underline;">Text</span> widget to the <a href="'.admin_url('widgets.php').'" target="_blank" >Google Map</a> widget area and paste the google map iframe code there.</p>',
            )
        )
    ) );

    /** Blog Settings **/
    $wp_customize->add_section(
		'MYTHEME_blog_settings',
		array(
			'title' => __('Blog Settings', 'MYTHEME' ),
			'priority' => 52,
			'capability' => 'edit_theme_options',
		)
	);

    $wp_customize->add_setting( 'MYTHEME_blog_title', array( 'default' => "Know - <span>What we are Upto</span>", 'capability' => 'edit_theme_options', 'sanitize_callback' => 'MYTHEME_allow_span' ));
    $wp_customize->add_control(
		'MYTHEME_blog_title',
		array(
			'label'	=> __( 'Section Title', 'MYTHEME' ),
			'type' => 'text',
			'section' => 'MYTHEME_blog_settings',
		)
	);

    $wp_customize->add_setting( 'MYTHEME_blog_cat', array( 'default' => '0', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'MYTHEME_sanitize_integer' ));
	$wp_customize->add_control( new MYTHEME_Category_Dropdown_Control( $wp_customize,
        'MYTHEME_blog_cat',
        array(
				'label' => __( 'Choose Category for Blog', 'MYTHEME' ),
				'section' => 'MYTHEME_blog_settings',
				'settings' => 'MYTHEME_blog_cat'
			)
		)
	);


    $wp_customize->add_setting( 'MYTHEME_blog_readmore_txt', array( 'default' => "Read More", 'capability' => 'edit_theme_options', 'sanitize_callback' => 'sanitize_text_field' ));
    $wp_customize->add_control(
		'MYTHEME_blog_readmore_txt',
		array(
			'label'	=> __( 'Readmore Text', 'MYTHEME' ),
			'type' => 'text',
			'section' => 'MYTHEME_blog_settings',
		)
	);

	// Social Links
	$wp_customize->add_section(
		'MYTHEME_social_links',
		array(
			'title' => __( 'Social Links', 'MYTHEME' ),
			'priority' => 170
		)
	);

	// Social Icon Shortcode
    // Social Icons Help Info
    $wp_customize->add_setting( 'MYTHEME_sicon_info', array( 'sanitize_callback' => 'sanitize_text_field' ));
    $wp_customize->add_control( new wp_customize_Help_Control( $wp_customize, 'MYTHEME_sicon_info', array(
            'section' => 'MYTHEME_social_links',
            'settings' => 'MYTHEME_sicon_info',
            'input_attrs' => array(
                'info' => '<p>Make Sure You have installed <a href="https://wordpress.org/plugins/accesspress-social-icons/" target="_blank">AccessPres Social Icons plugin</a>. Then create a social icon set.</p><p>Add the <span style="text-decoration: underline;">AccessPres Social Icons</span> widget to the <a href="'.admin_url('widgets.php').'" target="_blank" >Social Link (Header)</a> widget area.</p>',
            )
        )
    ) );

   	//post settings
    $wp_customize->add_section(
		'MYTHEME_post_settings',
		array(
			'title' => __('Post Settings', 'MYTHEME' ),
			'priority' => 53,
			'capability' => 'edit_theme_options',
		)
	);

    //featured image
	$wp_customize->add_setting( 'MYTHEME_feat_img_disable', array( 'default' => 1, 'capability' => 'edit_theme_options', 'sanitize_callback' => 'absint' ));
	$wp_customize->add_control(
		'MYTHEME_feat_img_disable',
		array(
			'label'	=> __( 'Enable/Disable featured Image', 'MYTHEME' ),
			'type' => 'checkbox',
			'section' => 'MYTHEME_post_settings',
			'settings' => 'MYTHEME_feat_img_disable'
		)
	);

	$wp_customize->add_setting( 'MYTHEME_metadata_disable', array( 'default' => 1, 'capability' => 'edit_theme_options', 'sanitize_callback' => 'absint' ));
	$wp_customize->add_control(
		'MYTHEME_metadata_disable',
		array(
			'label'	=> __( 'Enable/Disable MetaData', 'MYTHEME' ),
			'type' => 'checkbox',
			'section' => 'MYTHEME_post_settings',
			'settings' => 'MYTHEME_metadata_disable'
		)
	);


}

/** Extra Controls **/
if(class_exists('wp_customize_Control')) {
    class wp_customize_Help_Control extends wp_customize_Control{
        public function render_content() {
            $input_attrs = $this->input_attrs;
            $info = null !==($input_attrs['info'] ? $input_attrs['info'] : '');
            ?>
            <div class="help-info">
                <h4><?php esc_html_e('Instruction', 'MYTHEME'); ?></h4>
                <div style="font-weight: bold;">
                    <?php echo wp_kses_post($info); ?>
                </div>
            </div>
            <?php
        }
    }
}

add_action( 'customize_register', 'MYTHEME_customize_register' );

function MYTHEME_customizer_css() {
	?>
	<style type="text/css">
		body {
			color: <?php echo esc_html( get_theme_mod( 'text-color', '#eaeaea' ) ); ?>;
		}
		.mainmenu ul li a {
			color: <?php echo esc_html( get_theme_mod( 'menu-links', '#ffffff' ) ); ?>;
		}
		.mainmenu ul li a:after, .error404 #searchform input#searchsubmit, .pagination a:hover, .pagination span.current, .wp-block-search .wp-block-search__button, .wpcf7 input.wpcf7-submit, #submit {
			background: <?php echo esc_html( get_theme_mod( 'secondary-color', '#f44336' ) ); ?>;
		}

		.wp-block-button__link {
			background-color: <?php echo esc_html( get_theme_mod( 'secondary-color', '#f44336' ) ); ?>;
		}
		.wpcf7 label span.required {
			color: <?php echo esc_html( get_theme_mod( 'secondary-color', '#f44336' ) ); ?>;
		}
		h1, h2, h3, h4, h5, h6, h1.page-title, h1.entry-title, h2.entry-title, h2.entry-title a, #respond h3, #comments h2 {
			color: <?php echo esc_html( get_theme_mod( 'title-color', '#ffffff' ) ); ?>;
		}
	</style>
	<?php
}
add_action( 'wp_head', 'MYTHEME_customizer_css' );


/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function MYTHEME_customize_preview_js() {
	wp_enqueue_script( 'MYTHEME_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'MYTHEME_customize_preview_js' );

function MYTHEME_customize_scripts() {
	wp_enqueue_style( 'MYTHEME_custom_css', get_template_directory_uri() . '/inc/admin/css/admin.css' );
}
add_action( 'customize_controls_enqueue_scripts', 'MYTHEME_customize_scripts' );


function MYTHEME_sanitize_text( $input ) {
	return wp_kses_post( force_balance_tags( $input ) );
}

function MYTHEME_sanitize_integer( $input ) {
	if( is_numeric( $input ) ) {
		return intval( $input );
	}
}

function MYTHEME_sanitize_checkbox( $input ) {
	if( $input == 1 ) {
		return 1;
	}else {
		return '';
	}
}

function MYTHEME_sanitize_float( $input ) {

		return floatval( $input );

}

function MYTHEME_sanitize_filter_html( $input ) {
	return wp_filter_nohtml_kses( $input );
}

function MYTHEME_sanitize_slider_settings( $input ) {
	$options = array(
		'yes' => __( 'Yes', 'MYTHEME' ),
		'no' => __( 'No', 'MYTHEME' ),
		'horizontal' => __( 'Slider', 'MYTHEME' ),
		'fade' => __( 'Fade', 'MYTHEME' ),

	);
	if( array_key_exists( $input, $options ) ) {
		return $input;
	}else {
		return '';
	}
}

function storytime_customize_partial_blogname() {
	bloginfo( 'name' );
}


function storytime_customize_partial_blogdescription() {
	bloginfo( 'description' );
}




    /** Active Callbacks **/
    /** Section Page Layout **/
        function MYTHEME_section1_pg_layout( $control ) {
            if ( $control->manager->get_setting('MYTHEME_section_1_type')->value() == 'page' ) {
                return true;
            } else {
                return false;
            }
        }

        function MYTHEME_section2_pg_layout( $control ) {
            if ( $control->manager->get_setting('MYTHEME_section_2_type')->value() == 'page' ) {
                return true;
            } else {
                return false;
            }
        }

        function MYTHEME_section3_pg_layout( $control ) {
            if ( $control->manager->get_setting('MYTHEME_section_3_type')->value() == 'page' ) {
                return true;
            } else {
                return false;
            }
        }

        function MYTHEME_section4_pg_layout( $control ) {
            if ( $control->manager->get_setting('MYTHEME_section_4_type')->value() == 'page' ) {
                return true;
            } else {
                return false;
            }
        }

        function MYTHEME_section5_pg_layout( $control ) {
            if ( $control->manager->get_setting('MYTHEME_section_5_type')->value() == 'page' ) {
                return true;
            } else {
                return false;
            }
        }

        function MYTHEME_section6_pg_layout( $control ) {
            if ( $control->manager->get_setting('MYTHEME_section_6_type')->value() == 'page' ) {
                return true;
            } else {
                return false;
            }
        }

        function MYTHEME_section7_pg_layout( $control ) {
            if ( $control->manager->get_setting('MYTHEME_section_7_type')->value() == 'page' ) {
                return true;
            } else {
                return false;
            }
        }

    /** Section Predefined layout **/
        function MYTHEME_section1_pr_layout( $control ) {
            if ( $control->manager->get_setting('MYTHEME_section_1_type')->value() == 'prlayout' ) {
                return true;
            } else {
                return false;
            }
        }

        function MYTHEME_section2_pr_layout( $control ) {
            if ( $control->manager->get_setting('MYTHEME_section_2_type')->value() == 'prlayout' ) {
                return true;
            } else {
                return false;
            }
        }

        function MYTHEME_section3_pr_layout( $control ) {
            if ( $control->manager->get_setting('MYTHEME_section_3_type')->value() == 'prlayout' ) {
                return true;
            } else {
                return false;
            }
        }

        function MYTHEME_section4_pr_layout( $control ) {
            if ( $control->manager->get_setting('MYTHEME_section_4_type')->value() == 'prlayout' ) {
                return true;
            } else {
                return false;
            }
        }

        function MYTHEME_section5_pr_layout( $control ) {
            if ( $control->manager->get_setting('MYTHEME_section_5_type')->value() == 'prlayout' ) {
                return true;
            } else {
                return false;
            }
        }

        function MYTHEME_section6_pr_layout( $control ) {
            if ( $control->manager->get_setting('MYTHEME_section_6_type')->value() == 'prlayout' ) {
                return true;
            } else {
                return false;
            }
        }

        function MYTHEME_section7_pr_layout( $control ) {
            if ( $control->manager->get_setting('MYTHEME_section_7_type')->value() == 'prlayout' ) {
                return true;
            } else {
                return false;
            }
        }
    /** Sanitization **/
    function MYTHEME_allow_span($input) {
        $cus_allowed_tags = array(
            'span' => array()
        );

        $input_fil = wp_kses($input, $cus_allowed_tags);

        return $input_fil;
    }
