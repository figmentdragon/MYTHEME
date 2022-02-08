<?php

if (!function_exists('MYTHEME_mime_types')) {
  function MYTHEME_mime_types($mimes)
  {
    $mimes['svg'] = 'image/svg+xml';

    return $mimes;
  }
}
add_filter('upload_mimes', 'MYTHEME_mime_types', 99, 1);
