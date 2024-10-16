<?php

use App\Http\Controllers\Api\V1\FrontendController;
use App\Http\Controllers\Api\V1\FanController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

// Sends blogs and products to frontend
Route::get('/blogs', [FrontendController::class, 'getBlogs'])->name('blogs.index');
Route::get('/blogs/{id}', [FrontendController::class, 'getBlog'])->name('blogs.show');
Route::get('/products', [FrontendController::class, 'getProducts'])->name('products.index');
Route::get('/products/{id}', [FrontendController::class, 'getProduct'])->name('products.show');

// Fans Registration
Route::post('/fans/register', [FanController::class, 'register']);
Route::post('/fans/login', [FanController::class, 'login']);
Route::post('/fans/logout', [FanController::class, 'logout'])->middleware('auth:sanctum');

// Send Fan Order History
Route::get('/fans/{fan}/orders', [FanController::class, 'getFanOrders'])->middleware('auth:sanctum');

// Endpoint to create order after successful payment
Route::post('/create-order', [OrderController::class, 'store'])->name('orders.create');

Route::get('/get-team-details', [FrontendController::class, 'getTeamDetails'])->name('team-details');
Route::get('/get-vendors', [FrontendController::class, 'getVendors']);