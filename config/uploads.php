<?php

return [

  'documents' => [
    'max_kb' => (int) env('UPLOAD_DOCUMENT_MAX_KB', 5120),
    'allowed_mimes' => [
      'application/pdf',
      'application/msword',
      'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
      'image/jpeg',
      'image/png',
    ],
    'allowed_extensions' => ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'],
  ],

  'images' => [
    'max_kb' => (int) env('UPLOAD_IMAGE_MAX_KB', 2048),
    'allowed_mimes' => [
      'image/jpeg',
      'image/png',
      'image/gif',
      'image/webp',
    ],
    'allowed_extensions' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
  ],

];
