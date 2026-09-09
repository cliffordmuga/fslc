<?php

return [

  'disk' => env('IMAGE_DISK', 'public_uploads'),
  'max_size' => (int) env('IMAGE_SERVICE_MAX_SIZE', 2048),
  'default_quality' => (int) env('IMAGE_SERVICE_QUALITY', 80),
  'resize_mode' => 'crop',
  'bg_color' => '#FFFFFF',
  'blur_width' => 20,

  'variants' => [
    'main' => [800, 400, 'crop'],
    'small' => [400, 200, 'crop'],
    'mobile' => [360, 180, 'crop'],
    'mobile_retina' => [720, 360, 'crop'],
    'thumbnail' => [150, 150, 'fit'],
  ],

];
