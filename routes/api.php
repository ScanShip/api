<?php

use Illuminate\Http\Request;

Route::group(['namespace' => 'Auth'], function () {
    Route::post('login',    'AuthController@login');
    Route::post('register', 'AuthController@register');
    // Forget and reset password
    Route::post('password/reset',  'ResetPasswordController@reset');
    Route::post('password/forget', 'ForgotPasswordController@sendResetLinkEmail');
});
