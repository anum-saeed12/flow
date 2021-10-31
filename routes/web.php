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

/*Route::get('/', function () {
    return view('home');
})->name('landing');*/

// Auth routes
Route::get('/', '\App\Http\Controllers\AuthController@login')->name('login');
Route::post('/login', '\App\Http\Controllers\AuthController@attemptLogin')->name('auth.login');
Route::get('/logout', '\App\Http\Controllers\AuthController@logout')->name('logout');
Route::get('/register', '\App\Http\Controllers\AuthController@register_step_1')->name('register');
Route::post('/register/info', '\App\Http\Controllers\AuthController@register_step_2')->name('register.second');
Route::post('/register', '\App\Http\Controllers\AuthController@newRegistration')->name('auth.register');
Route::get('/forgot-password', '\App\Http\Controllers\AuthController@password')->name('forgot');
Route::get('/reset/request', '\App\Http\Controllers\AuthController@register')->name('reset.request');
Route::post('/reset/password', '\App\Http\Controllers\AuthController@forgotpassword')->name('reset.password');

Route::prefix('/admin')->middleware('admin')->group(function() {
    # Dashboard
    Route::get('/', '\App\Http\Controllers\DashboardController@admin')->name('dashboard.admin');

    Route::prefix('/user')->group(function() {
        Route::get('/', '\App\Http\Controllers\Admin\UserController@index')->name('user.index.admin');
        Route::get('/index', '\App\Http\Controllers\Admin\UserController@index')->name('user.list.admin');
        Route::get('/add', '\App\Http\Controllers\Admin\UserController@add')->name('user.add.admin');
        Route::post('/store', '\App\Http\Controllers\Admin\UserController@store')->name('user.store.admin');
        Route::get('/edit/{id}', '\App\Http\Controllers\Admin\UserController@edit')->name('user.edit.admin');
        Route::post('/update/{id}', '\App\Http\Controllers\Admin\UserController@update')->name('user.update.admin');
        Route::get('/delete/{id}', '\App\Http\Controllers\Admin\UserController@delete')->name('user.delete.admin');
    });
    Route::prefix('/inquiry')->group(function() {
        Route::get('/', '\App\Http\Controllers\Admin\InquiryController@index')->name('inquiry.index.admin');
        Route::get('/index', '\App\Http\Controllers\Admin\InquiryController@index')->name('inquiry.list.admin');
        Route::get('/open', '\App\Http\Controllers\Admin\InquiryController@open')->name('inquiry.open.admin');
        Route::get('/add', '\App\Http\Controllers\Admin\InquiryController@add')->name('inquiry.add.admin');
        Route::post('/store', '\App\Http\Controllers\Admin\InquiryController@store')->name('inquiry.store.admin');
        Route::get('/edit/{id}', '\App\Http\Controllers\Admin\InquiryController@edit')->name('inquiry.edit.admin');
        Route::post('/update/{id}', '\App\Http\Controllers\Admin\InquiryController@update')->name('inquiry.update.admin');
        Route::get('/delete/{id}', '\App\Http\Controllers\Admin\InquiryController@delete')->name('inquiry.delete.admin');
    });
    Route::prefix('/customer')->group(function() {
        Route::get('/', '\App\Http\Controllers\Admin\CustomerController@index')->name('customer.index.admin');
        Route::get('/index', '\App\Http\Controllers\Admin\CustomerController@index')->name('customer.list.admin');
        Route::get('/add', '\App\Http\Controllers\Admin\CustomerController@add')->name('customer.add.admin');
        Route::post('/store', '\App\Http\Controllers\Admin\CustomerController@store')->name('customer.store.admin');
        Route::get('/edit/{id}', '\App\Http\Controllers\Admin\CustomerController@edit')->name('customer.edit.admin');
        Route::post('/update/{id}', '\App\Http\Controllers\Admin\CustomerController@update')->name('customer.update.admin');
        Route::get('/delete/{id}', '\App\Http\Controllers\Admin\CustomerController@delete')->name('customer.delete.admin');
    });
    Route::prefix('/item')->group(function() {
        Route::get('/', '\App\Http\Controllers\Admin\ItemController@index')->name('item.index.admin');
        Route::get('/index', '\App\Http\Controllers\Admin\ItemController@index')->name('item.list.admin');
        Route::get('/add', '\App\Http\Controllers\Admin\ItemController@add')->name('item.add.admin');
        Route::post('/store', '\App\Http\Controllers\Admin\ItemController@store')->name('item.store.admin');
        Route::get('/edit/{id}', '\App\Http\Controllers\Admin\ItemController@edit')->name('item.edit.admin');
        Route::post('/update/{id}', '\App\Http\Controllers\Admin\ItemController@update')->name('item.update.admin');
        Route::get('/delete/{id}', '\App\Http\Controllers\Admin\ItemController@delete')->name('item.delete.admin');
        Route::get('/ajax-fetch/', '\App\Http\Controllers\Admin\ItemController@ajaxFetch')->name('item.fetch.ajax.admin');
    });
    Route::prefix('/brand')->group(function() {
        Route::get('/', '\App\Http\Controllers\Admin\BrandController@index')->name('brand.index.admin');
        Route::get('/index', '\App\Http\Controllers\Admin\BrandController@index')->name('brand.list.admin');
        Route::get('/add', '\App\Http\Controllers\Admin\BrandController@add')->name('brand.add.admin');
        Route::post('/store', '\App\Http\Controllers\Admin\BrandController@store')->name('brand.store.admin');
        Route::get('/edit/{id}', '\App\Http\Controllers\Admin\BrandController@edit')->name('brand.edit.admin');
        Route::post('/update/{id}', '\App\Http\Controllers\Admin\BrandController@update')->name('brand.update.admin');
        Route::get('/delete/{id}', '\App\Http\Controllers\Admin\BrandController@delete')->name('brand.delete.admin');
    });
    Route::prefix('/category')->group(function() {
        Route::get('/', '\App\Http\Controllers\Admin\CategoryController@index')->name('category.index.admin');
        Route::get('/index', '\App\Http\Controllers\Admin\CategoryController@index')->name('category.list.admin');
        Route::get('/add', '\App\Http\Controllers\Admin\CategoryController@add')->name('category.add.admin');
        Route::post('/store', '\App\Http\Controllers\Admin\CategoryController@store')->name('category.store.admin');
        Route::get('/edit/{id}', '\App\Http\Controllers\Admin\CategoryController@edit')->name('category.edit.admin');
        Route::post('/update/{id}', '\App\Http\Controllers\Admin\CategoryController@update')->name('category.update.admin');
        Route::get('/delete/{id}', '\App\Http\Controllers\Admin\CategoryController@delete')->name('category.delete.admin');
    });
    Route::prefix('/quotation')->group(function() {
        Route::get('/customer', '\App\Http\Controllers\Admin\QuotationController@customer')->name('customerquotation.list.admin');
        Route::get('/add', '\App\Http\Controllers\Admin\QuotationController@add')->name('quotation.add.admin');
        Route::post('/store', '\App\Http\Controllers\Admin\QuotationController@store')->name('quotation.store.admin');
        Route::get('/edit/{id}', '\App\Http\Controllers\Admin\QuotationController@edit')->name('quotation.edit.admin');
        Route::post('/update/{id}', '\App\Http\Controllers\Admin\QuotationController@update')->name('quotation.update.admin');
        Route::get('/delete/{id}', '\App\Http\Controllers\Admin\QuotationController@delete')->name('quotation.delete.admin');
        Route::get('/view/{id}', '\App\Http\Controllers\Admin\QuotationController@view')->name('quotation.view.admin');
    });
    Route::prefix('/vendor')->group(function() {
        Route::get('/', '\App\Http\Controllers\Admin\VendorController@index')->name('vendor.index.admin');
        Route::get('/index', '\App\Http\Controllers\Admin\VendorController@index')->name('vendor.list.admin');
        Route::get('/add', '\App\Http\Controllers\Admin\VendorController@add')->name('vendor.add.admin');
        Route::post('/store', '\App\Http\Controllers\Admin\VendorController@store')->name('vendor.store.admin');
        Route::get('/edit/{id}', '\App\Http\Controllers\Admin\VendorController@edit')->name('vendor.edit.admin');
        Route::post('/update/{id}', '\App\Http\Controllers\Admin\VendorController@update')->name('vendor.update.admin');
        Route::get('/delete/{id}', '\App\Http\Controllers\Admin\VendorController@delete')->name('vendor.delete.admin');
    });
    Route::prefix('/vendor/quotation')->group(function() {
        Route::get('/', '\App\Http\Controllers\Admin\VendorQuotationController@index')->name('vendorquotation.index.admin');
        Route::get('/index', '\App\Http\Controllers\Admin\VendorQuotationController@index')->name('vendorquotation.list.admin');
        Route::get('/add', '\App\Http\Controllers\Admin\VendorQuotationController@add')->name('vendorquotation.add.admin');
        Route::post('/store', '\App\Http\Controllers\Admin\VendorQuotationController@store')->name('vendorquotation.store.admin');
        Route::get('/edit/{id}', '\App\Http\Controllers\Admin\VendorQuotationController@edit')->name('vendorquotation.edit.admin');
        Route::post('/update/{id}', '\App\Http\Controllers\Admin\VendorQuotationController@update')->name('vendorquotation.update.admin');
        Route::get('/delete/{id}', '\App\Http\Controllers\Admin\VendorQuotationController@delete')->name('vendorquotation.delete.admin');
    });
});

Route::prefix('/manager')->middleware('manager')->group(function() {
    # Dashboard
    Route::get('/', '\App\Http\Controllers\DashboardController@manager')->name('dashboard.manager');

    Route::prefix('/inquiry')->group(function() {
        Route::get('/', '\App\Http\Controllers\Manager\InquiryController@index')->name('inquiry.index.manager');
        Route::get('/index', '\App\Http\Controllers\Manager\InquiryController@index')->name('inquiry.list.manager');
        Route::get('/open', '\App\Http\Controllers\Manager\InquiryController@open')->name('inquiry.open.manager');
        Route::get('/add', '\App\Http\Controllers\Manager\InquiryController@add')->name('inquiry.add.manager');
        Route::post('/store', '\App\Http\Controllers\Manager\InquiryController@store')->name('inquiry.store.manager');
        Route::get('/edit/{id}', '\App\Http\Controllers\Manager\InquiryController@edit')->name('inquiry.edit.manager');
        Route::post('/update/{id}', '\App\Http\Controllers\Manager\InquiryController@update')->name('inquiry.update.manager');
        Route::get('/delete/{id}', '\App\Http\Controllers\Manager\InquiryController@delete')->name('inquiry.delete.manager');
    });
    Route::prefix('/item')->group(function() {
        Route::get('/', '\App\Http\Controllers\Manager\ItemController@index')->name('item.index.manager');
        Route::get('/index', '\App\Http\Controllers\Manager\ItemController@index')->name('item.list.manager');
        Route::get('/add', '\App\Http\Controllers\Manager\ItemController@add')->name('item.add.manager');
        Route::post('/store', '\App\Http\Controllers\Manager\ItemController@store')->name('item.store.manager');
        Route::get('/edit/{id}', '\App\Http\Controllers\Manager\ItemController@edit')->name('item.edit.manager');
        Route::post('/update/{id}', '\App\Http\Controllers\Manager\ItemController@update')->name('item.update.manager');
        Route::get('/delete/{id}', '\App\Http\Controllers\Manager\ItemController@delete')->name('item.delete.manager');
        Route::get('/ajax-fetch/', '\App\Http\Controllers\Manager\ItemController@ajaxFetch')->name('item.fetch.ajax.manager');
    });
    Route::prefix('/quotation')->group(function() {
        Route::get('/customer', '\App\Http\Controllers\Manager\QuotationController@customer')->name('customerquotation.list.manager');
        Route::get('/add', '\App\Http\Controllers\Manager\QuotationController@add')->name('quotation.add.manager');
        Route::post('/store', '\App\Http\Controllers\Manager\QuotationController@store')->name('quotation.store.manager');
        Route::get('/edit/{id}', '\App\Http\Controllers\Manager\QuotationController@edit')->name('quotation.edit.manager');
        Route::post('/update/{id}', '\App\Http\Controllers\Manager\QuotationController@update')->name('quotation.update.manager');
        Route::get('/delete/{id}', '\App\Http\Controllers\Manager\QuotationController@delete')->name('quotation.delete.manager');
        Route::get('/view/{id}', '\App\Http\Controllers\Manager\QuotationController@view')->name('quotation.view.manager');
    });
    Route::prefix('/vendor/quotation')->group(function() {
        Route::get('/', '\App\Http\Controllers\Manager\VendorQuotationController@index')->name('vendorquotation.index.manger');
        Route::get('/index', '\App\Http\Controllers\Manager\VendorQuotationController@index')->name('vendorquotation.list.manger');
        Route::get('/add', '\App\Http\Controllers\Manager\VendorQuotationController@add')->name('vendorquotation.add.manger');
        Route::post('/store', '\App\Http\Controllers\Manager\VendorQuotationController@store')->name('vendorquotation.store.manger');
        Route::get('/edit/{id}', '\App\Http\Controllers\Manager\VendorQuotationController@edit')->name('vendorquotation.edit.manger');
        Route::post('/update/{id}', '\App\Http\Controllers\Manager\VendorQuotationController@update')->name('vendorquotation.update.manger');
        Route::get('/delete/{id}', '\App\Http\Controllers\Manager\VendorQuotationController@delete')->name('vendorquotation.delete.manger');
    });
});

