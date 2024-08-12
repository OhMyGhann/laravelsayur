<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DownloadPdfController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/update-quantity', [CartController::class, 'updateCartQuantity'])->name('cart.updateQuantity');
Route::post('/cart/remove', [CartController::class, 'removeFromCart'])->name('cart.remove');

Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

//Shop routes

Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');

//Contact Routes

Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');


// Order routes
Route::get('/order', [OrderController::class, 'index'])->name('order.index');
Route::post('/order', [OrderController::class, 'store'])->name('order.store');



// Payment routes
Route::get('/payment/{order_id}', [PaymentController::class, 'showPaymentPage'])->name('showPaymentPage');
Route::post('/processPayment', [PaymentController::class, 'processPayment'])->name('processPayment');

// admin panel
Route::get('/login', function () {
    return redirect(route('filament.admin.auth.login'));
})->name('login');

Route::get('/{record}/pdf', [DownloadPdfController::class, 'download'])->name('order.pdf.download');
