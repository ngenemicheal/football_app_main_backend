<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [AuthenticatedSessionController::class, 'create'])->name('login');

/*
|--------------------------------------------------------------------------
| Dashboard & Team Routes
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [TeamController::class, 'showTeamDetails'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/buy-ticket', [TeamController::class, 'buyTickets'])
    ->middleware(['auth', 'verified'])->name('buy-ticket');

/*
|--------------------------------------------------------------------------
| User Profile Routes (Authenticated Users)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Blog Management Routes
|--------------------------------------------------------------------------
*/
Route::resource('/blogs', BlogController::class)
    ->only(['index', 'store', 'edit', 'update', 'destroy'])
    ->middleware(['auth', 'verified']);

/*
|--------------------------------------------------------------------------
| Product Management Routes
|--------------------------------------------------------------------------
*/
Route::resource('/products', ProductController::class)
    ->only(['index', 'store', 'edit', 'update', 'destroy'])
    ->middleware(['auth', 'verified']);

/*
|--------------------------------------------------------------------------
| Admin Management Routes
|--------------------------------------------------------------------------
*/
Route::resource('/admins', UserController::class)
    ->only(['index', 'store', 'edit', 'update', 'destroy'])
    ->parameters(['admins' => 'user'])
    ->middleware(['auth', 'verified']);

/*
|--------------------------------------------------------------------------
| Ticket Routes
|--------------------------------------------------------------------------
*/
Route::resource('/tickets', TicketController::class)
    ->only(['index', 'store', 'show'])
    ->middleware(['auth', 'verified']);

Route::get('/museum-tickets', [TicketController::class, 'showMuseumTickets'])
    ->name('museum.tickets')
    ->middleware(['auth', 'verified']);

Route::get('/season-ticket', [TicketController::class, 'showSeasonDetails'])
    ->name('season.ticket')
    ->middleware(['auth', 'verified']);

Route::post('/season-ticket', [TicketController::class, 'updateSeasonDetails'])
    ->name('season.tickets.update')
    ->middleware(['auth', 'verified']);

/*
|--------------------------------------------------------------------------
| Order Routes
|--------------------------------------------------------------------------
*/
Route::get('/orders/products', [OrderController::class, 'index'])->name('orders.product');

/*
|--------------------------------------------------------------------------
| Print Routes
|--------------------------------------------------------------------------
*/
Route::post('/print-ticket', [PrintController::class, 'printTicket']);
Route::post('/print-tickets', [PrintController::class, 'printTickets']);
Route::get('/payment/failure', [PrintController::class, 'paymentError'])->name('failurePage');

/*
|--------------------------------------------------------------------------
| Bulk Ticket Payment Callback
|--------------------------------------------------------------------------
*/
Route::get('/bulkTicket-payment-callback', [TicketController::class, 'verifyBulkTicketPayment']);

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
