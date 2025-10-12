<?php

return [
    'secret' => env('JWT_SECRET', 'megasecretkey'),
    'access_exp' => env('JWT_ACCESS_EXP', 3600),
    'refresh_exp' => env('JWT_REFRESH_EXP', 3600 * 24 * 30),
];
