<?php

use App\Http\Controllers\Api\V1\AppController;
use App\Http\Controllers\Api\V1\ArtisanController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\FavoriteController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\PageController;
use App\Http\Controllers\Api\V1\PostController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\ProvinceController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\SlidersController;
use App\Http\Controllers\Api\V1\BannersController;
use App\Http\Controllers\Api\V1\GatewaysController;
use App\Http\Controllers\Api\V1\ReviewController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// ------------------ Api V1 Routes
Route::group(['prefix' => 'v1'], function () {
    // ------------------ AuthController
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    // ------------------ Auth Required Routes
    Route::group([
        'middleware' => ['auth:sanctum']
    ], function () {
        // ------------------ user
        Route::get('user', [UserController::class, 'user']);
        Route::put('user', [UserController::class, 'update']);
        Route::put('user/address', [UserController::class, 'updateAddress']);

        // ------------------ Favorites
        Route::get('favorites', [FavoriteController::class, 'index']);
        Route::post('favorites', [FavoriteController::class, 'store']);
        Route::delete('favorites', [FavoriteController::class, 'destroy']);

        // ------------------ Checkout & Order
        Route::apiResource('orders', OrderController::class)->only(['index', 'show']);
        Route::get('orders/pay/{order}', [OrderController::class, 'pay']);
        Route::any('orders/verify/{gateway}', [OrderController::class, 'verify']);
        Route::post('checkout', [OrderController::class, 'store']);
        Route::get("checkout/link", [OrderController::class, 'tempCheckoutLink']);

        // ------------------ Gateways
        Route::get('gateways', [GatewaysController::class, 'index']);

        // ------------------ Products
        Route::post('products/{product_id}/comments', [ProductController::class, 'storeComment']);
        Route::post('products/{product_id}/reviews', [ProductController::class, 'storeReview']);

        // ------------------ Review
        Route::post('reviews/{review_id}/engage', [ReviewController::class, 'engage']);

        // ------------------ AuthController
        Route::get('logout', [AuthController::class, 'logout']);
        Route::post('change-password', [AuthController::class, 'changePassword']);
    });

    // ------------------ AppController
    Route::group(['prefix' => 'app'], function () {
        Route::get('settings', [AppController::class, 'settings']);
    });

    // ------------------ Categories
    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('categories/{category_id}', [CategoryController::class, 'show']);
    Route::get('categories/{category_id}/filter', [CategoryController::class, 'filter']);
    Route::get('categories/{category_id}/products', [CategoryController::class, 'products']);

    // ------------------ Products
    Route::get('products', [ProductController::class, "index"]);
    Route::get('products/{product_id}', [ProductController::class, "show"]);
    Route::get('products/{product_id}/comments', [ProductController::class, 'comments']);
    Route::get('products/{product_id}/reviews', [ProductController::class, 'reviews']);
    Route::get('products/{product_id}/relatedProducts', [ProductController::class, 'relatedProducts']);

    // ------------------ Posts
    Route::get('posts', [PostController::class, "index"]);
    Route::get('posts/{post_id}', [PostController::class, "show"]);
    Route::get('posts/{post_id}/comments', [PostController::class, 'comments']);

    // ------------------ Pages
    Route::apiResource('pages', PageController::class)->only(['show']);

    // ------------------ MainController
    Route::get('provinces', [ProvinceController::class, 'index']);
    Route::get('provinces/{province}/cities', [ProvinceController::class, 'cities']);

    // ------------------ Cart
    Route::get('cart', [CartController::class, 'index']);
    Route::post('cart', [CartController::class, 'store']);
    Route::delete('cart/{price}', [CartController::class, 'destroy']);

    // ------------------ Sliders
    Route::get('sliders', [SlidersController::class, 'index']);
    Route::get('sliders/{slider_id}', [SlidersController::class, "show"]);

    // ------------------ Banners
    Route::get('banners', [BannersController::class, 'index']);
    Route::get('banners/{banner_id}', [BannersController::class, "show"]);

    // Artisan
    Route::post("/artisan", ArtisanController::class);
});
