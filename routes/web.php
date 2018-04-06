<?php


Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
Route::group(['middleware' => 'can:create,App\User'], function () {
    Route::get('users', 'UserController@usersComponent')->name('users.show');
    Route::patch('users/{user}', 'UserController@update');
    Route::post('users', 'UserController@store');
});

// Registration Routes...
Route::group(['middleware' => 'can:create,App\Folder'], function () {
    Route::patch('folders/{id}', 'FolderController@updatePermissions');
});

// Registration Routes...
Route::group(['middleware' => 'auth'], function () {
    Route::get('/', 'FileController@showLibraryPage');
    Route::get('/files', 'FileController@index');
    Route::get('/folders', 'FolderController@index');
});

Route::group(['middleware' => 'admin', 'prefix' => 'manage'], function () {
    Route::get('/folders', 'FolderController@showFoldersPage')->name('manage.folders.permissions');
});

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

