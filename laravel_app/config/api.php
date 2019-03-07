<?php

return array(
    /* API URL */
    'url' => env('API_URL'),

    /* API TIMEOUT */
    'timeout' => env('API_TIMEOUT'),
    
    /* API Path */
    'login' => '/login.json',
    'users' => '/users.json',
    'capData' => '/capture_data.json',
);