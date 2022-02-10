<?php

use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\DashboardController;
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

Route::get('/show', function(){
    return view('mail.marketing2.ar');
});

Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect']], function()
{
    Route::get('/mailable', function () {

        return new App\Mail\MarketingMail(1,3,1,'en');
    });
Route::group(['middleware'=>'guest'], function () {
    Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('admin.get_login');
    Route::post('login', [AdminLoginController::class, 'login'])->name('admin.post_login');
});
Route::group(['middleware'=>'auth'], function () {
    Route::get('/assure_login', [AdminLoginController::class, 'assure_login'])->name('admin.get_assure_login');
    Route::post('logout', [AdminLoginController::class, 'logout'])->name('admin.post_logout');
    Route::get('/', [DashboardController::class, 'index'])->name('admin.get_dashboard');
    Route::post('/', [DashboardController::class, 'add_to_queue'])->name('admin.add_to_queue');
});
});
