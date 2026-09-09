<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Social login (Laravel Socialite)
    |--------------------------------------------------------------------------
    |
    | When false, OAuth routes return 404 and login/register hide social buttons.
    | Set SOCIAL_LOGIN_ENABLED=true in .env when you need Google/Facebook/Apple sign-in.
    |
    */

    'social_login_enabled' => filter_var(env('SOCIAL_LOGIN_ENABLED', false), FILTER_VALIDATE_BOOL),

];
