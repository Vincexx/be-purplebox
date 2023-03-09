<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Authentication
Route::post("/register", "AuthController@register");
Route::post("/login", "AuthController@login");
Route::middleware('auth:sanctum')->post("/logout", "AuthController@logout");

// User Management
Route::resource("/users", "UserController");

// Product Management
Route::resource("/products", "ProductController");

// Order Management
Route::middleware('auth:sanctum')->get("/orders", "OrderController@index");
Route::middleware('auth:sanctum')->post("/orders", "OrderController@store");
Route::middleware('auth:sanctum')->get("/orders/get/{id}", "OrderController@show");
Route::middleware('auth:sanctum')->put("/orders/{id}", "OrderController@update");
Route::middleware('auth:sanctum')->put("/orders/cart/{id}", "OrderController@updateAddToCart");
Route::middleware('auth:sanctum')->delete("/orders/{id}", "OrderController@delete");
Route::middleware('auth:sanctum')->get("/orders/cart", "OrderController@getUserCart");
Route::middleware('auth:sanctum')->get("/orders/get-total-price", "OrderController@getTotalOfAllItems");
Route::middleware('auth:sanctum')->get("/orders/quantity", "OrderController@getQtyEachOrder");
Route::middleware('auth:sanctum')->get("/user/orders/quantity", "OrderController@getQtyEachUserOrder");

//Customize Cakes
Route::middleware('auth:sanctum')->get("/custom-cakes", "CustomCakeController@index");
Route::middleware('auth:sanctum')->post("/custom-cakes", "CustomCakeController@store");
Route::middleware('auth:sanctum')->get("/custom-cakes/get/{id}", "CustomCakeController@show");
Route::middleware('auth:sanctum')->put("/custom-cakes/{id}", "CustomCakeController@update");
Route::middleware('auth:sanctum')->delete("/custom-cakes/{id}", "CustomCakeController@delete");

Route::middleware('auth:sanctum')->get("/user/custom-cakes", "CustomCakeController@getUsersCake");
