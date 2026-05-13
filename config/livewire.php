<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Temporary File Uploads
    |--------------------------------------------------------------------------
    |
    | Livewire supports native file uploads via its "HasFileUploads" trait.
    | These files are stored in a temporary directory until they are
    | finaly stored in their permanent location.
    |
    */

    'temporary_file_upload' => [
        'disk' => 'local',        // Specify a disk for temporary file storage
        'rules' => 'file|max:102400', // 100MB - Increase this to allow larger files
        'directory' => null,
        'middleware' => null,
        'preview_mimetypes' => [
            'png', 'gif', 'jpg', 'jpeg', 'svg', 'mp4', 'mov', 'avi', 'wmv', 'mpeg', 'm4v', 'mkv', 'webm', 'ogv', 'mp3', 'wav', 'ogg', 'm4a', 'wav', 'm4r',
        ],
        'max_upload_time' => 5, // Minutes
    ],

    /*
    |--------------------------------------------------------------------------
    | Manifest Path
    |--------------------------------------------------------------------------
    |
    | This value sets the path to the Livewire manifest file.
    | The manifest file is used by Livewire to keep track of its internal state.
    |
    */

    'manifest_path' => null,

    /*
    |--------------------------------------------------------------------------
    | Back Button Cache
    |--------------------------------------------------------------------------
    |
    | This value determines whether or not Livewire will cache pages
    | in the browser's back button history.
    |
    */

    'back_button_cache' => false,

    /*
    |--------------------------------------------------------------------------
    | Render On Redirect
    |--------------------------------------------------------------------------
    |
    | This value determines whether or not Livewire will render the
    | component on the same request when a redirect is performed.
    |
    */

    'render_on_redirect' => false,

];
