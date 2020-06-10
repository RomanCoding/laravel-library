<?php

// Auth Routes
Route::group(['middleware' => 'guest'], function () {
    Route::get('/', 'Auth\LoginController@welcome');
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'Auth\LoginController@login');
});

Route::post('logout', 'Auth\LoginController@logout')->name('logout');


Route::group(['middleware' => 'can:create,App\User', 'prefix' => 'users'], function () {
    Route::get('/', 'UserController@usersComponent')->name('users.show');
    Route::patch('/{user}', 'UserController@update');
    Route::post('/', 'UserController@store');
    Route::delete('/{user}', 'UserController@destroy');
});

Route::group(['middleware' => 'can:create,App\Folder'], function () {
    Route::patch('folders/{id}', 'FolderController@updatePermissions');
    Route::delete('folders/{id}', 'FolderController@destroy');
    Route::delete('files/{id}', 'FileController@destroy');
    Route::post('files/', 'FileController@store');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/api/partners', 'PartnerController@index');
    Route::post('/api/partners/{partner}/logo', 'PartnerController@updateLogo');
    Route::post('/api/partners', 'PartnerController@store');
    Route::patch('/api/partners/{partner}', 'PartnerController@update');
    Route::get('/events', 'EventController@index');
    Route::get('/user', 'Auth\LoginController@user');
    Route::get('/library', 'FileController@showLibraryPage');
    Route::get('/files', 'FileController@index');
    Route::get('/network/users', 'UserController@network');
    Route::get('/folders', 'FolderController@index');
    Route::get('folders/root', 'FolderController@root');
    Route::get('folders/{folder}', 'FolderController@show');

    Route::get('/videos', 'VideoController@index');

    Route::group(['prefix' => 'downloads'], function () {
        Route::get('/files/{file}', 'DownloadController@file')->name('downloads.file');
    });

    Route::get('/profile', 'ProfileController@index');
    Route::post('/profile', 'ProfileController@update');
    Route::post('/profile/logo', 'ProfileController@store');

});

Route::group(['middleware' => 'admin'], function () {
    Route::get('/settings', 'SettingsController@index');
    Route::post('/settings', 'SettingsController@store');
    Route::post('/events', 'EventController@store');
    Route::delete('/events/{event}', 'EventController@destroy');
    Route::post('/videos', 'VideoController@store');
    Route::patch('/videos/{video}', 'VideoController@update');
    Route::delete('/videos/{video}', 'VideoController@destroy');
    Route::delete('/api/partners/{partner}', 'PartnerController@destroy');
});

Route::group(['middleware' => 'admin', 'prefix' => 'manage'], function () {
    Route::get('/folders', 'FolderController@showFoldersPage')->name('manage.folders.permissions');
    Route::post('/folders', 'FolderController@store')->name('manage.folders.create');
    Route::get('/email', 'SettingsController@emailSettings')->name('manage.email');
    Route::get('/extensions', 'ExtensionController@index')->name('manage.extensions');
    Route::post('/extensions', 'ExtensionController@store');
    Route::delete('/extensions/{id}', 'ExtensionController@destroy');
    Route::get('/uploads', 'UploadController@index')->name('manage.uploads');
});

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

Route::get('/{vue?}', 'FileController@showLibraryPage')->where('vue', '[\/\w\.-]*')->middleware('auth');

