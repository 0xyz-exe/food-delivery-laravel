<?php
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FoodController;
use Illuminate\Support\Facades\Route;
Route::get('/',[FoodController::class,'index'])->name('food.home');
Route::get('/restaurants/{restaurant:slug}',[FoodController::class,'restaurant'])->name('food.restaurant');
Route::middleware('auth')->group(function(){
 Route::get('/dashboard',fn()=>redirect()->route('food.home'))->name('dashboard');
 Route::get('/cart',[FoodController::class,'cart'])->name('food.cart');Route::post('/restaurants/{restaurant}/items/{item}/cart',[FoodController::class,'add'])->name('food.cart.add');
 Route::get('/addresses',[FoodController::class,'address'])->name('food.addresses');Route::post('/addresses',[FoodController::class,'saveAddress'])->name('food.addresses.store');Route::patch('/addresses/{id}',[FoodController::class,'updateAddress'])->whereNumber('id')->name('food.addresses.update');Route::delete('/addresses/{id}',[FoodController::class,'deleteAddress'])->whereNumber('id')->name('food.addresses.delete');
 Route::post('/checkout',[FoodController::class,'checkout'])->middleware('throttle:10,1')->name('food.checkout');
 Route::get('/orders',[FoodController::class,'orders'])->name('food.orders');Route::get('/orders/{id}',[FoodController::class,'order'])->whereNumber('id')->name('food.orders.show');
 Route::get('/restaurant-dashboard',[FoodController::class,'restaurantDashboard'])->name('food.restaurant.dashboard');
 Route::get('/admin/restaurants/create',[FoodController::class,'createRestaurant'])->name('food.restaurants.create');Route::post('/admin/restaurants',[FoodController::class,'storeRestaurant'])->name('food.restaurants.store');
 Route::patch('/restaurants/{restaurant}/settings',[FoodController::class,'updateRestaurant'])->name('food.restaurants.update');Route::post('/restaurants/{restaurant}/categories',[FoodController::class,'addCategory'])->name('food.categories.store');Route::post('/restaurants/{restaurant}/items',[FoodController::class,'addItem'])->name('food.items.store');Route::patch('/restaurants/{restaurant}/items/{item}',[FoodController::class,'updateItem'])->whereNumber('item')->name('food.items.update');
 Route::patch('/orders/{id}/assignment',[FoodController::class,'assignment'])->whereNumber('id')->name('food.orders.assignment');Route::patch('/orders/{id}/status',[FoodController::class,'transition'])->whereNumber('id')->name('food.orders.transition');
 Route::get('/admin/reports',[FoodController::class,'report'])->name('food.report');Route::post('/admin/promotions',[FoodController::class,'storePromotion'])->name('food.promotions.store');
 Route::get('/profile',[ProfileController::class,'edit'])->name('profile.edit');Route::patch('/profile',[ProfileController::class,'update'])->name('profile.update');Route::delete('/profile',[ProfileController::class,'destroy'])->name('profile.destroy');
});
require __DIR__.'/auth.php';
