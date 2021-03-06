<?php

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



use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Auth::routes();


// mcamara
/*Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]
    ], function(){*/




Route::get('payments/paypal', 'PayPalController@index')->name('paypal.index');
Route::get('payments/paypal/express-checkout-success', 'PayPalController@getExpressCheckoutSuccess');
Route::get('payments/paypal/express-checkout', 'PayPalController@getExpressCheckout')->name('paypal.express-checkout');

Route::get('firebase/sw-js','AppSettingController@initFirebase');
Route::get('testcoord',function(){
    \DB::table('coord')->insert(
            ['coord' => 'jo555']
        );
});

Route::get('login/{service}', 'Auth\LoginController@redirectToProvider');
Route::get('login/{service}/callback', 'Auth\LoginController@handleProviderCallback');

Route::get('storage/app/public/{id}/{conversion}/{filename?}', 'UploadController@storage');
Route::middleware('auth')->group(function () {
    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
    Route::get('/', 'UserController@profile')->name('users.profile');

    Route::get('users/profile', 'UserController@profile')->name('users.profile');
    Route::get('dashboard', 'DashboardController@index')->name('dashboard');

    Route::group(['middleware' => ['permission:medias']], function () {
        Route::post('uploads/store', 'UploadController@store')->name('medias.create');
        Route::get('uploads/all/{collection?}', 'UploadController@all');
        Route::get('uploads/collectionsNames', 'UploadController@collectionsNames');
        Route::post('uploads/clear', 'UploadController@clear')->name('medias.delete');
        Route::get('medias', 'UploadController@index')->name('medias');
        Route::get('uploads/clear-all', 'UploadController@clearAll');
    });

    Route::group(['middleware' => ['permission:permissions.edit']], function () {
        Route::get('permissions/role-has-permission', 'PermissionController@roleHasPermission');
        Route::get('permissions/refresh-permissions', 'PermissionController@refreshPermissions');
    });
    Route::group(['middleware' => ['permission:permissions.update']], function () {
        Route::post('permissions/give-permission-to-role', 'PermissionController@givePermissionToRole');
        Route::post('permissions/revoke-permission-to-role', 'PermissionController@revokePermissionToRole');
    });
    Route::group(['middleware' => ['permission:app-settings']], function () {
        Route::prefix('settings')->group(function () {
            Route::resource('permissions', 'PermissionController');
            Route::resource('roles', 'RoleController');
            Route::resource('customFields', 'CustomFieldController');
            Route::post('users/remove-media', 'UserController@removeMedia');
            Route::get('users/login-as-user/{id}', 'UserController@loginAsUser')->name('users.login-as-user');
            Route::resource('users', 'UserController');
            Route::patch('update', 'AppSettingController@update');
            Route::patch('translate', 'AppSettingController@translate');
            Route::get('sync-translation', 'AppSettingController@syncTranslation');
            // disable special character and number in route params
            Route::get('/{type?}/{tab?}', 'AppSettingController@index')
                ->where('type', '[A-Za-z]*')->where('tab', '[A-Za-z]*')->name('app-settings');
        });
    });

    Route::post('restaurants/remove-media', 'RestaurantController@removeMedia');
    Route::get('restaurants/clone/{id}', 'RestaurantController@clone')->name('restaurants.clone');
    Route::resource('restaurants', 'RestaurantController')->except([
        'show'
    ]);

    Route::post('categories/remove-media', 'CategoryController@removeMedia');
    Route::resource('categories', 'CategoryController')->except([
        'show'
    ]);

    Route::resource('faqCategories', 'FaqCategoryController')->except([
        'show'
    ]);

    Route::resource('orderStatuses', 'OrderStatusController')->except([
        'create', 'store', 'destroy'
    ]);;

    Route::post('foods/remove-media', 'FoodController@removeMedia');
    Route::resource('foods', 'FoodController')->except([
        'show','storeExtrasForFood'
    ]);

    Route::post('galleries/remove-media', 'GalleryController@removeMedia');
    Route::resource('galleries', 'GalleryController')->except([
        'show'
    ]);

    Route::resource('foodReviews', 'FoodReviewController')->except([
        'show'
    ]);


    Route::resource('nutrition', 'NutritionController')->except([
        'show'
    ]);

    Route::post('extras/remove-media', 'ExtraController@removeMedia');
    Route::resource('extras', 'ExtraController');

    Route::resource('payments', 'PaymentController')->except([
        'create', 'store','edit', 'destroy'
    ]);;

    Route::resource('faqs', 'FaqController')->except([
        'show'
    ]);
    
    Route::resource('restaurantReviews', 'RestaurantReviewController')->except([
        'show'
    ]);
    
    Route::resource('restaurantCouppons', 'RestaurantCoupponController')->except([
        'show'
    ]);

    Route::resource('favorites', 'FavoriteController')->except([
        'show'
    ]);

    Route::resource('orders', 'OrderController');

    Route::resource('notifications', 'NotificationController')->except([
        'create', 'store', 'update','edit',
    ]);;

    Route::resource('carts', 'CartController')->except([
        'show','store','create'
    ]);
    Route::resource('currencies', 'CurrencyController')->except([
        'show'
    ]);
    Route::resource('deliveryAddresses', 'DeliveryAddressController')->except([
        'show'
    ]);

    Route::resource('drivers', 'DriverController');

    Route::resource('earnings', 'EarningController');

    Route::resource('driversPayouts', 'DriversPayoutController');

    Route::resource('restaurantsPayouts', 'RestaurantsPayoutController');

});

//Route::post('request/register' , 'Controller@requestRegister');    we make it in api for cashier app
Route::get('show/requests' , 'Controller@showRequests')->name('show.requests');
Route::post('accept/request/{id}' , 'Controller@acceptRequest')->name('accept.request');
Route::get('show/all/users/not/admins' , 'Controller@showUsers');
Route::post('make/admin/{id}' , 'Controller@makeAdmin')->name('make.admin');
Route::post('make/cashier/{id}' , 'Controller@makeCashier')->name('make.cashier');
Route::post('make/driver/{id}' , 'Controller@makeDriver')->name('make.driver');
Route::post('make/client/{id}' , 'Controller@makeClient')->name('make.client');

Route::post('add/role/{id}' , 'Controller@addRole')->name('add.role');
Route::post('revoke/role/{id}' , 'Controller@revokeRole')->name('revoke.role');

Route::get('records','Controller@records')->name('records');
Route::get('daily/reports','Controller@dailyReports')->name('daily.reports');
Route::post('print/recrord/pdf','Controller@printPDF')->name('print.pdf');
Route::post('print/recrord/{id}','Controller@printPDF')->name('print.pdf');
Route::get('show/map','Controller@showMap')->name('show.map');

Route::get('show/all/roles/to/edit','PermissionsChangeController@showAllRoles')->name('show.all.roles');
Route::get('show/role/permissions/to/edit/{roleid}','PermissionsChangeController@showRolePermissions')->name('show.role.permissions');
Route::post('edit/permissions/for/{roleid}','PermissionsChangeController@editPermissionsForRole')->name('edit.permissions.for.role');
Route::get('show/all/users/to/edit/permissions','PermissionsChangeController@showAllUsersToEditPermissions')->name('show.all.users.to.edit.permissions');
Route::get('show/user/permissions/to/edit/{userid}','PermissionsChangeController@showUserPermissions')->name('show.user.permissions');
Route::post('edit/permissions/for/user/{userid}','PermissionsChangeController@editPermissionsForUser')->name('edit.permissions.for.user');

Route::resource('/tags','TagController');

Route::post('/add/extras/for/food/{id}','FoodController@storeExtrasForFood')->name('add.extras.for.food');
Route::post('/add/foods/for/extra/{id}','ExtraController@storeFoodsForExtra')->name('add.foods.for.extra');

Route::get('search/order','OrderController@indexSearch')->name('search.order');
Route::get('make/search/order','OrderController@makeSearch')->name('make.search.order');
Route::get('clients','UserController@clients')->name('clients');
Route::get('clients/details/{id}','UserController@clientsMoreDetails')->name('clients.details');

    //});