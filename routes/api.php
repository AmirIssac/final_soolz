<?php

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



use Mcamara\LaravelLocalization\Facades\LaravelLocalization;




Route::prefix('driver')->group(function () {
    Route::post('login', 'API\Driver\UserAPIController@login');
    Route::post('register', 'API\Driver\UserAPIController@register');
    Route::post('send_reset_link_email', 'API\UserAPIController@sendResetLinkEmail');
    Route::get('user', 'API\Driver\UserAPIController@user');
    Route::get('logout', 'API\Driver\UserAPIController@logout');
    Route::get('settings', 'API\Driver\UserAPIController@settings');
});

Route::prefix('cashiers')->group(function () {
    Route::post('login', 'API\cashier\UserAPIController@login');
    Route::post('register', 'API\cashier\UserAPIController@register');
    Route::post('send_reset_link_email', 'API\UserAPIController@sendResetLinkEmail');
    Route::get('user', 'API\cashier\UserAPIController@user');
    Route::get('logout', 'API\cashier\UserAPIController@logout');
    Route::get('settings', 'API\cashier\UserAPIController@settings');
    Route::post('get_orders', 'API\cashier\UserAPIController@getOrder');
    Route::post('get_order', 'API\cashier\UserAPIController@getOrderById');
    Route::post('order_action', 'API\cashier\UserAPIController@order_action');
    Route::resource('orders', 'API\OrderAPIController');
    Route::resource('notifications', 'API\NotificationAPIController');
});


Route::post('login', 'API\UserAPIController@login');
Route::post('checkuser', 'API\UserAPIController@checkuser');
Route::post('register', 'API\UserAPIController@register');
Route::post('send_reset_link_email', 'API\UserAPIController@sendResetLinkEmail');
Route::get('user', 'API\UserAPIController@user');
Route::get('logout', 'API\UserAPIController@logout');
Route::get('settings', 'API\UserAPIController@settings');
Route::resource('restaurants', 'API\RestaurantAPIController');
Route::resource('categories', 'API\CategoryAPIController');
Route::resource('faq_categories', 'API\FaqCategoryAPIController');
Route::resource('foods', 'API\FoodAPIController');
Route::get('getfoodsbypoint', 'API\FoodAPIController@getfoodsbypoint');
Route::resource('galleries', 'API\GalleryAPIController');
Route::resource('food_reviews', 'API\FoodReviewAPIController');
Route::resource('nutrition', 'API\NutritionAPIController');
Route::resource('extras', 'API\ExtraAPIController');
Route::resource('faqs', 'API\FaqAPIController');
Route::resource('restaurant_reviews', 'API\RestaurantReviewAPIController');
Route::resource('currencies', 'API\CurrencyAPIController');
Route::post('update_order', 'API\OrderAPIController@updateOrderStatus');
Route::post('update_rest_status', 'API\OrderAPIController@updateRestaurantStatus');
Route::post('update_pay_status', 'API\OrderAPIController@updateOrderPaymentStatus');
Route::get('getdrivers/{id}', 'API\OrderAPIController@restaurantDrivers');
Route::post('update_order/{id}', 'API\OrderAPIController@updateOrder');

Route::middleware('auth:api')->group(function () {
    Route::group(['middleware' => ['role:driver']], function () {
        Route::prefix('driver')->group(function () {
            Route::resource('orders', 'API\OrderAPIController');
            Route::resource('notifications', 'API\NotificationAPIController');
        });
    });
    
    Route::group(['middleware' => ['role:manager']], function () {
        Route::prefix('manager')->group(function () {
            
            Route::resource('drivers', 'API\DriverAPIController');

            Route::resource('earnings', 'API\EarningAPIController');

            Route::resource('driversPayouts', 'API\DriversPayoutAPIController');

            Route::resource('restaurantsPayouts', 'API\RestaurantsPayoutAPIController');
        });
    });
    Route::post('users/{id}', 'API\UserAPIController@update');

    Route::resource('order_statuses', 'API\OrderStatusAPIController');

    Route::get('payments/byMonth', 'API\PaymentAPIController@byMonth')->name('payments.byMonth');
    Route::resource('payments', 'API\PaymentAPIController');

    Route::get('favorites/exist', 'API\FavoriteAPIController@exist');
    Route::resource('favorites', 'API\FavoriteAPIController');

    Route::resource('orders', 'API\OrderAPIController');

    Route::resource('food_orders', 'API\FoodOrderAPIController');

    Route::resource('notification_types', 'API\NotificationTypeAPIController');

    Route::resource('notifications', 'API\NotificationAPIController');

    Route::get('carts/count', 'API\CartAPIController@count')->name('carts.count');
    Route::resource('carts', 'API\CartAPIController');

    Route::resource('delivery_addresses', 'API\DeliveryAddressAPIController');

});

Route::get('coupons/{id}', 'API\CartAPIController@coupons');


 //mcamara package
// test api mcamara package (mcamara not work fine in api so we do it staticly)
Route::get('getfoodbyid/{food_id}/{lang}','API\FoodOrderAPIController@getFoodOrderById'); //mcamara
//Route::get('change/app/language/to/{lang}','API\FoodOrderAPIController@changeLangTo');