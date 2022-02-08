<?php
	/*-----------------------------------------------------------------------------------*/
	/* This file will be referenced every time a template/page loads on your Wordpress site
	/* This is the place to define custom fxns and specialty code
	/*-----------------------------------------------------------------------------------*/

// Define the version so we can easily replace it throughout the theme
define( 'MYTHEME_VERSION', 1.0 );

require_once( 'inc/MYTHEME-functions.php' );
require_once( 'inc/MYTHEME-support.php' );
require_once( 'inc/customizer.php' );

require get_template_directory() . '/inc/classes/nav-walker-bs5.php';
require get_template_directory() . '/inc/classes/class-wp-bootstrap-navwalker.php';
require get_template_directory() . '/inc/classes/class-MYTHEME-Menu-Attribute-Walker.php';

function MYTHEME_setup() {

	load_theme_textdomain( 'MYTHEME', get_template_directory() . '/languages' );
	if ( ! isset( $content_width ) ) $content_width = 900;

	add_action( 'wp_enqueue_scripts', 'MYTHEME_scripts' );

}
add_action( 'after_setup_theme', 'MYTHEME_setup' );

register_nav_menus( array(
	'primary-menu'    => esc_html__( 'Primary', 'MYTHEME' ),
	'social-menu'     => esc_html__( 'Floating Social Menu', 'MYTHEME' ),
) );



function MYTHEME_scripts()  {

	// get the theme directory style.css and link to it in the header
	wp_enqueue_style('style.css', get_stylesheet_directory_uri() . '/style.css');

	// add fitvid
	wp_enqueue_script( 'MYTHEME-fitvid', get_template_directory() . '/assets/scripts/js/jquery.fitvids.js', array( 'jquery' ), MYTHEME_VERSION, true );

	// add theme scripts
	wp_enqueue_script( 'MYTHEME', get_template_directory() . '/assets/scripts/js/theme.min.js', array(), MYTHEME_VERSION, true );

	wp_register_script( 'MYTHEME', get_template_directory() . '/assets/scripts/js/MYTHEME.js' );

	wp_enqueue_script( 'MYTHEME ' );

}
 // Register this fxn and allow Wordpress to call it automatcally in the header
