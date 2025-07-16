<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\FilterStoreController;
use App\Http\Controllers\CustomerLocationController;
use App\Http\Controllers\StoreLocationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\PartneringOrderController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\SpecifyProductController;
use App\Http\Controllers\MeasureNameController;
use App\Http\Controllers\MeasureController;
use App\Http\Controllers\MeasureValueController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\DesignController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CoAdminController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\RatingController;
use App\Http\Requests\EmployeeRegisterRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegisterMail;
use App\Models\Customer;
use App\Models\Design;
use App\Models\Feature;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\URL;


Route::controller(AuthController::class)->group(function (): void {

    Route::post('register', 'ownerRegister')->name('ownerRegister');

    Route::post('login', 'login')->name('login');

    Route::post('customer-register', 'customerRegister')->name('customerRegister');

    Route::post('customer-login', 'customerLogin')->name('customerLogin');

    Route::post('logout', 'logout')->name('logout');


    // Route::post('/sendSMS', 'sendSMS')->name('sendSMS');
    // Route::get('/sendSMS', 'sendSMS')->name('sendSMS');
    // Route::post('/sendPin', 'sendPin')->name('sendPin'); 
    // Route::post('/sendPinCode', 'sendPinCode')->name('sendPinCode'); 

    // Route::post('/verify-pin', 'verifyPin')->name('verifyPin');

    Route::post('/send-pin', [AuthController::class, 'sendPin']);
    Route::post('/verify-pin', [AuthController::class, 'verifyPin']);
});




Route::controller(CoAdminController::class)->group(function (): void {

    Route::post('co-admin-register', 'coAdminRegister')->name('coAdminRegister');

    Route::get('view-co-admins', 'viewCoAdmins')->name('viewCoAdmins')->middleware('auth:api');

    Route::delete('delete-co-admin/{user}', 'deleteCoAdmin')->name('deleteCoAdmin');
});



Route::controller(EmailController::class)->group(function (): void {

    Route::get('verify-email/{id}/{hash}', 'emailVerify')->middleware('signed')->name('verification.verify');

    Route::post('resend-verification', 'resendEmailVerification')->name('verification.resend');

    Route::post('/forgot-password', 'forgotPassword')->name('password.email');

    Route::post('/reset-password', 'resetPassword')->name('password.update');
});


Route::controller(StoreController::class)->group(function (): void {

    Route::get('show-store', 'show')->name('show.store')->middleware('auth:api');

    Route::get('view-stores', 'index')->name('view.stores');

    Route::put('update-store/{store}', 'update')->name('update.store')->middleware('auth:api');

    Route::delete('destroy-store/{store}', 'destroy')->name('destroy.store')->middleware('auth:api');

    Route::post('employee-register', 'addEmployee')->name('addEmployee')->middleware(['auth:sanctum', 'role:Store Owner']);

    Route::delete('delete-employee/{user}', 'deleteEmployee')->name('deleteEmployee');

    Route::get('view-employee', 'viewEmployees')->name('viewEmployees')->middleware('auth:api');
});


Route::controller(StoreLocationController::class)->group(function (): void {

    Route::get('view-store-loc', 'view')->name('view.store.Loc')->middleware('auth:api');

    Route::get('view-stores-loc', 'index')->name('view.stores.loc');

    Route::get('show-store-locs/{storeLoc}', 'show')->name('show.loc')->middleware('auth:api');

    Route::post('store-loc', 'store')->name('store.loc');

    Route::put('update-store-loc/{storeLoc}', 'update')->name('update.loc')->middleware('auth:api');

    Route::delete('destroy-store-loc/{storeLoc}', 'destroy')->name('destroy.loc')->middleware('auth:api');
});


Route::controller(CustomerLocationController::class)->group(function (): void {

    Route::get('view-customer-loc/{customer}', 'view')->name('view.customer.Loc');

    Route::get('view-customers-loc', 'index')->name('view.customers.loc');

    Route::get('show-customer-locs/{customerLoc}', 'show')->name('show.customer.loc');

    Route::post('store-customer-loc', 'store')->name('store.customer.loc')->middleware('auth:sanctum');

    Route::put('update-customer-loc/{customerLoc}', 'update')->name('update.customer.loc');

    Route::delete('destroy-customer-loc/{customerLoc}', 'destroy')->name('destroy.customer.loc');
});


Route::get('nearest-stores', [FilterStoreController::class, 'nearestToCustomer']);


Route::get('/reset-password/{token}', function ($token) {
    return response()->json(['token' => $token]);
})->name('password.reset');



Route::controller(OrderController::class)->group(function (): void {

    Route::get('orders/{order}','show')->name('show.order');

    Route::get('view-orders', 'index')->name('view.orders');

    Route::get('view-invoice/{order}', 'invoice')->name('view.invoice');

    Route::get('orders', 'view')->name('view.store.orders');

    Route::post('update-order-status/{id}', 'update')->name('update.order.status');

    // Route::get('/orders/{id}/details', 'customerOrderDetails')->name('customerOrderDetails');
    Route::get('/orders/{orderId}', 'customerOrderDetails')->name('customerOrderDetails');


});

Route::controller(StatusController::class)->group(function (): void {

    Route::get('show-status/{status}', 'show')->name('show.status');

    Route::get('view-statuses', 'index')->name('view.statuses');

    Route::post('add-status', 'store')->name('add.status');

    Route::put('update-status/{status}', 'update')->name('update.status');

    Route::delete('destroy-status/{status}', 'destroy')->name('destroy.status');
});



Route::controller(UserController::class)->group(function () {
    Route::get('show-user', 'show')->name('show.user');

    Route::put('update-user/{user}', 'update')->name('update.user');

    Route::put('update-password/{user}', 'updatePassword')->name('update.password');

    Route::put('update-email/{user}', 'updateEmail')->name('update.email');
});

Route::apiResource('product-categories', ProductCategoryController::class);

Route::apiResource('partnering-orders', PartneringOrderController::class)->except('store');

Route::apiResource('features', FeatureController::class)->except('store');


Route::controller(FeatureController::class)->group(function (): void {

    Route::post('features/{prod_catg}', 'store')->name('features.store');

    Route::get('view-features', 'viewCategoryFeatures')->name('view.category.features');
});


Route::controller(SpecifyProductController::class)->group(function () {
    Route::get('view-store-features', 'index')->name('view.store.features');
    Route::get('show-store-feature/{feature}', 'show')->name('show.store.feature');
    Route::get('view-unselected-features', 'unselectedFeatures')->name('unselectedFeatures');
    Route::post('store-feature', 'store')->name('select.feature');
    Route::delete('destroy-feature/{feature}', 'destroy')->name('destroy.feature');
    Route::get('view-store-product/{storeId}', 'storeProduct')->name('storeProduct');
});


Route::controller(DesignController::class)->group(function () {
    Route::get('show-store-designs/{store}', 'indexAdmin')->name('store.designs.show.admin');
    Route::get('view-store-designs', 'indexPartner')->name('store.designs.view.partner');
    Route::get('show-design/{design}', 'show')->name('store.design.show');
    Route::get('show-feature-design/{feature}', 'showStoreDesign')->name('feature.design.show');
    Route::post('store-design', 'store')->name('add.desgin');
    Route::put('update-design/{design}', 'update')->name('update.design');
    Route::delete('destroy-design/{design}', 'destroy')->name('destroy.design');
});

Route::apiResource('images', ImageController::class)->except('store');
Route::get('store-avr-ratings/{store}', [StoreController::class, 'getStoreRatings'])->name('store-avr-ratings');


Route::apiResource('item', ItemController::class)->except('index')->middleware('auth:api');
Route::get('items/{order}', [ItemController::class, 'index'])->name('order.items');
Route::post('items/{order}', [ItemController::class, 'chooseService'])->name('chooseService');
Route::delete('items/{item}', [ItemController::class, 'destroy'])->name('destroy');
Route::delete('items/{item}/{design}', [ItemController::class, 'removeDesignFromItem'])->name('removeDesignFromItem');


Route::controller(MeasureNameController::class)->group(function (): void {

    Route::get('view-measure-name', 'index')->name('viewMeasureNames');
});


Route::controller(MeasureController::class)->group(function (): void {

    Route::post('store-measure', 'store')->name('storeMeasure');
});

Route::controller(MeasureValueController::class)->group(function (): void {

    Route::post('store-measure-value', 'store')->name('storeMeasureValue');

     Route::put('update-measure-value/{id}', 'update')->name('updateMeasureValue');

});


Route::controller(ServiceController::class)->group(function (): void {

    Route::get('list-store-services/{store}', 'listStoreServices')->name('listStoreServices');

    Route::get('view-services', 'index')->name('index');

    Route::post('set-store-service', 'setStoreServices')->name('setStoreServices')->middleware('auth:api');
});


Route::controller(CustomerController::class)->group(function (): void {

    Route::get('view-customers', 'index')->name('view-customers');

    Route::get('show-customer', 'show')->name('show-customers')->middleware('auth:sanctum');

    Route::put('update-customer', 'update')->name('update-customers')->middleware('auth:sanctum');
});

Route::post('rate-order/{order}', [RatingController::class, 'store'])->name('rate.order');


// Route::get('/pay', [App\Http\Controllers\MyFatoorahController::class, 'index'])->name('myfatoorah.pay');
// Route::get('/callback', [App\Http\Controllers\MyFatoorahController::class, 'callback'])->name('myfatoorah.callback');

#API's with authentication:
// Route::middleware('auth:sanctum')->group(function () {
//     Route::prefix('measures')->controller(MeasureController::class)->group(function () {
//         Route::post('/', 'store');
//         Route::get('{measure}', 'show');
//         Route::put('{measure}', 'update');
//         Route::delete('{measure}', 'destroy');
//     });

//     Route::prefix('measure-values')->controller(MeasureValueController::class)->group(function () {
//         Route::post('/', 'store');
//         Route::put('{secondaryMeasure}', 'update');
//         Route::delete('{secondaryMeasure}', 'destroy');
//     });
// });

Route::prefix('measures')->controller(MeasureController::class)->group(function () {
    Route::post('/', 'store');
    Route::get('{measure}', 'show');
    Route::put('{measure}', 'update');
    Route::delete('{measure}', 'destroy');
});

Route::prefix('measure-values')->controller(MeasureValueController::class)->group(function () {
    Route::post('/', 'store');
    // Route::put('{secondaryMeasure}', 'update');
    Route::delete('{secondaryMeasure}', 'destroy');
});
