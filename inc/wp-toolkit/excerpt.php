<?php

/**
 * Filter the except length to 20 words.
 *
 * @param int $length Excerpt length.
 *
 * @return int (Maybe) modified excerpt length.
 */
function MYTHEME_custom_excerpt_length($length)
{
  return 25;
}

add_filter('excerpt_length', __NAMESPACE__.'\\MYTHEME_custom_excerpt_length', 999);

function MYTHEME_excerpt_more($more)
{
  //    global $post;
  return '...';
  //    $more_text = '...';
  //    return '… <a href="'. get_permalink($post->ID) . '">' . $more_text . '</a>';
}

add_filter('excerpt_more', __NAMESPACE__.'\\MYTHEME_excerpt_more');

/**
 * Add Excerpt to Pages
 */

function MYTHEME_page_supports()
{
  add_post_type_support('page', ['excerpt']);
}

add_action('init', __NAMESPACE__.'\\MYTHEME_page_supports', 10);
