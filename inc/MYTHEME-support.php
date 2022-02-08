<?php

/* ____________________________________________ THEME SUPPORT */
function MYTHEME_theme_support() {
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'align-wide' );
	// Add support for Block Styles.
	add_theme_support( 'wp-block-styles' );
	// Add support for full and wide align images.
	add_theme_support( 'align-wide' );
	// Add support for responsive embedded content.
	add_theme_support( 'responsive-embeds' );
	// Add support for custom line height controls.
	add_theme_support( 'custom-line-height' );
	// Add support for experimental link color control.
	add_theme_support( 'experimental-link-color' );
	// Add support for experimental cover block spacing.
	add_theme_support( 'custom-spacing' );
	// Add support for custom units.
	add_theme_support( 'custom-units' );
	// Add support for essential widget image
	add_theme_support( 'ew-newsletter-image' );
	// Add support for editor styles.
	add_theme_support( 'editor-styles' );
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );


	add_theme_support('custom-header',
	array(
		'default-image'          => get_template_directory_uri() . '/img/headers/default.jpg',
		'header-text'            => false,
		'default-text-color'     => '000',
		'width'                  => 1000,
		'height'                 => 198,
		'random-default'         => false
	));


	add_theme_support( 'post-thumbnails' );
		add_image_size( 'large', 700, '', true ); // Large Thumbnail.
		add_image_size( 'medium', 250, '', true ); // Medium Thumbnail.
		add_image_size( 'small', 120, '', true ); // Small Thumbnail.
		add_image_size( 'custom-size', 700, 200, true ); // Custom Thumbnail Size call using the_post_thumbnail('custom-size');
		add_image_size( 'MYTHEME-thumb-600', 600, 150, true );
		add_image_size( 'MYTHEME-thumb-300', 300, 100, true );
		add_image_size( 'MYTHEME-fullscreen', 1980, 9999 );
		add_image_size( 'MYTHEME-grid-large', 750, 750, true ); // Grid image crop
		add_image_size( 'MYTHEME-post-image', 850, 300, true ); // Post Image
		add_image_size( 'MYTHEME-bpost-image', 380, 250, true ); // Blog  Post Image
		add_image_size( 'MYTHEME-featbox-image', 580, 350, true ); // Feature Box Thumbnail
		add_image_size( 'MYTHEME-block-image', 606, 404, true ); // Ratio 3:2
		// Used in single post page
		add_image_size( 'MYTHEME-single-post-page', 1920, 440, true );
		// Used in featured sliders
		add_image_size( 'MYTHEME-slider', 1920, 1080, true ); // Ratio 16:9
		// Used in Portfolio
		add_image_size( 'MYTHEME-portfolio', 1920, 9999, true ); // Flexible Height


	set_post_thumbnail_size(125, 125, true);
	// wp custom background (thx to @bransonwerner for update)

	add_theme_support( 'custom-background',
	    array(
	    'default-image' => get_template_directory_uri() . '/assets/images/backgrounds/comppaper.png',    // background image default
			'default-color' => 'FFF',
	    'wp-head-callback' => '_custom_background_cb',
	    'admin-head-callback' => '',
	    'admin-preview-callback' => ''
	    )
	);

	$logo_width                                   = 512;
	$logo_height                                  = 512;
	// If the retina setting is active, double the recommended width and height.
	if ( get_theme_mod( 'retina_logo', false ) ) {
		$logo_width  = floor( $logo_width * 2 );
		$logo_height = floor( $logo_height * 2 );
	}

	add_theme_support(
		'custom-logo',
		array(
			'height'                                  => $logo_height,
			'width'                                   => $logo_width,
			'flex-width'                              => true,
			'flex-height'                             => true,
			'unlink-homepage-logo'                    => true,
		)
	);


	// adding post format support
	add_theme_support( 'post-formats',
		array(
			'aside',             // title less blurb
			'gallery',           // gallery of images
			'link',              // quick link to other site
			'image',             // an image
			'quote',             // a quick quote
			'status',            // a Facebook like status update
			'video',             // video
			'audio',             // audio
			'chat'               // chat transcript
		)
	);

	// wp menus
	add_theme_support( 'menus' );

// Enable support for HTML5 markup.
	add_theme_support(
		'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
				'navigation-widgets',
			)
		);
	}

/* end MYTHEME theme support */

?>
