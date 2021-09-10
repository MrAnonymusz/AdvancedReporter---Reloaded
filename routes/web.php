<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
  return redirect('auth/login');
});

/*
  >> Verify Password
*/

Route::post('verify-password', 'VerifyPasswordController@action')->middleware('user');

/*
  >> Avatar Controllers
*/

Route::prefix('avatar')->group(function() {
  // Render
  Route::get('render/{token}', 'Avatar\AvatarRenderController@render');

  // Upload Avatar
  Route::post('upload/{token}', 'Avatar\AvatarUploadController@action');

  // Update
  Route::put('update/{token}', 'Avatar\AvatarUpdateController@action');

  // Remove
  Route::delete('remove/{token}', 'Avatar\AvatarRemoveController@action');
});

/*
  >> Auth Controllers
*/

Route::prefix('auth')->middleware('guest')->group(function() {
  // Login
  Route::get('login', 'PageController@auth_login');
  Route::post('login', 'Auth\LoginController@action');
  // Register
  Route::get('register', 'PageController@auth_register');
  Route::post('register', 'Auth\RegisterController@action');
});

// Logout
Route::get('auth/logout/{token}', 'Auth\LogoutController@action')->middleware('user');

/*
  >> Page Controllers (Panel)
*/

Route::prefix('panel')->middleware('user')->group(function() {
  // Home
  Route::get('home', 'PageController@home');

  // User
  Route::prefix('user')->group(function() {
    // Account
    Route::get('account', 'PageController@user_account_settings');
    // Account Update
    Route::put('account/update', 'User\UpdateAccountController@action');
    // Clear Logs
    Route::delete('account/logs/clear', 'User\LogsController@clear_all_logs');
  });

  // Reports
  Route::prefix('report')->group(function() {
    // List
    Route::get('list', 'PageController@report_list');
    Route::get('list/my-reports', 'PageController@report_list_my_reports');
    // Create
    Route::get('create', 'PageController@report_create');
    Route::post('create', 'Report\ReportCreateController@action');
    // Resolve
    Route::get('resolve/{token}', 'PageController@report_resolve');
    Route::post('resolve/{token}', 'Report\ReportResolveController@action');
    // SpreadSheet(s)
    Route::prefix('spreadsheet')->group(function() {
      // Create
      Route::post('create', 'Report\ReportsSpreadsheetController@create');
      // Download
      Route::get('download/{token}', 'Report\ReportsSpreadsheetController@download');
    });
    // Take
    Route::post('take/{token}', 'Report\ReportTakeController@action');
    // Edit
    Route::get('edit/{token}', 'PageController@report_edit');
    Route::post('edit/{token}', 'Report\ReportEditController@action');
    // Remove
    Route::delete('remove/{token}', 'Report\ReportRemoveController@action');
  });
});

/*
  >> Admin Pages
*/

Route::prefix('admin')->middleware('user')->group(function() {
  // Site Settings
  Route::get('site-settings', 'PageController@admin_site_settings');
  Route::put('site-settings/update', 'Admin\SiteSettingsController@action');

  // User Manager
  Route::prefix('user')->group(function() {
    // Create
    Route::get('create', 'PageController@admin_user_create');
    Route::post('create', 'Admin\UserController@create_user');
    // List
    Route::get('list', 'PageController@admin_user_list');
    // Edit
    Route::get('edit/{token}', 'PageController@admin_user_edit');
    Route::put('edit/{token}', 'Admin\UserController@edit_user');
    Route::put('status', 'Admin\UserController@dea_act_user');
    // Remove
    Route::delete('remove/{token}', 'Admin\UserController@remove_user');
  });

  // Role Manager
  Route::prefix('role')->group(function() {
    // List
    Route::get('list', 'PageController@admin_role_list');
    // Create
    Route::get('create', 'PageController@admin_role_create');
    Route::post('create', 'Admin\RoleController@create_role');
    // Edit
    Route::get('edit/{token}', 'PageController@admin_role_edit');
    Route::put('edit/{token}', 'Admin\RoleController@edit_role');
    Route::put('set-as-default/{token}', 'Admin\RoleController@set_role_as_default');
    // Delete
    Route::delete('remove/{token}', 'Admin\RoleController@delete_role');
  });
});

// Deactivated Account
Route::get('panel/user/account/deactivated', 'PageController@account_deactivated_page');