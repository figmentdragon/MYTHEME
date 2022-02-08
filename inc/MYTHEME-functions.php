<?php
/**
 * This is the file for all of the theme specific, or  a page, category, etc, functions
 */

function MYTHEME_functions() {
   add_action( 'widgets_init', 'MYTHEME_register_sidebars' );
}

function MYTHEME_register_sidebars() {
 	register_sidebar(array(				// Start a series of sidebars to register
 		'id' => 'sidebar', 					// Make an ID
 		'name' => 'Sidebar',				// Name it
 		'description' => 'Take it on the side...', // Dumb description for the admin side
 		'before_widget' => '<div>',	// What to display before each widget
 		'after_widget' => '</div>',	// What to display following each widget
 		'before_title' => '<h3 class="side-title">',	// What to display before each widget's title
 		'after_title' => '</h3>',		// What to display following each widget's title
 		'empty_title'=> '',					// What to display in the case of no title defined for a widget
 		// Copy and paste the lines above right here if you want to make another sidebar,
 		// just change the values of id and name to another word/name
 	));
}

function MYTHEME_rss_version() { return ''; }

function MYTHEME_remove_wp_ver_css_js( $src ) {
	if ( strpos( $src, 'ver=' ) )
		$src = remove_query_arg( 'ver', $src );
	return $src;
}

function MYTHEME_post_comments_feed_link() {
    return;
}

function MYTHEME_remove_wp_widget_recent_comments_style() {
	if ( has_filter( 'wp_head', 'wp_widget_recent_comments_style' ) ) {
		remove_filter( 'wp_head', 'wp_widget_recent_comments_style' );
	}
}

function MYTHEME_filter_ptags_on_images($content){
  return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}

function MYTHEME_excerpt_more($more) {
  global $post;
  // edit here if you like
  return '...  <a class="excerpt-read-more" href="'. get_permalink( $post->ID ) . '" title="'. __( 'Read ', 'MYTHEME' ) . esc_attr( get_the_title( $post->ID ) ).'">'. __( 'Read more &raquo;', 'MYTHEME' ) .'</a>';
}
