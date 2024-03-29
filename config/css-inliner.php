<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Css Files
    |--------------------------------------------------------------------------
    |
    | Css file of your style for your emails
    | The content of these files will be added directly into the inliner
    | Use absolute paths, ie. public_path('css/main.css')
    |
    */

    'css-files' => [
        glob(public_path('build/assets').'/*.css')[0] ?? resource_path('css/app.css')
    ],

];
