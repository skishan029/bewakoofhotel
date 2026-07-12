<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TwilioController;

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

Route::get('/install', function () {
    // \Illuminate\Support\Facades\Artisan::call('storage:link');
    // \Illuminate\Support\Facades\Artisan::call('migrate:fresh --seed');
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('route:clear');
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    \Illuminate\Support\Facades\Artisan::call('config:clear');

    /**
     * php artisan migrate --path=database/migrations/2026_02_27_212613_add_region_columns_to_customers_table.php
     */
    $migrationFiles = [
        'database/migrations/2026_02_03_215904_add_shipping_charge_to_products_table.php',
        'database/migrations/2026_02_03_221311_create_customers_table.php',
        'database/migrations/2026_02_07_220352_add_is_active_to_products_table.php',
        'database/migrations/2026_02_07_224333_add_restaurant_hours_to_panelsettings_table.php',
        'database/migrations/2026_02_08_125849_add_fields_to_product_orders_table.php',
        'database/migrations/2026_02_09_223044_create_order_intents_table.php',
        'database/migrations/2026_02_16_213751_create_regions_table.php',
        'database/migrations/2026_02_19_215655_create_category_products_table.php',
        'database/migrations/2026_02_20_203703_add_online_columns_to_products_table.php',
        'database/migrations/2026_02_20_204407_add_delivery_columns_to_product_order_items_table.php',
        'database/migrations/2026_02_20_230120_add_region_columns_to_product_orders_table.php',
        'database/migrations/2026_02_20_230130_add_region_columns_to_order_intents_table.php',
        'database/migrations/2026_02_27_091909_add_is_split_to_products_table.php',

        'database/migrations/2026_02_27_212613_add_region_columns_to_customers_table.php',
    ];
    foreach ($migrationFiles as $file) {
        \Illuminate\Support\Facades\Artisan::call('migrate', [
            '--path' => $file,
            '--force' => true,
        ]);
    }
    \App\Models\ProductOrder::where('status', '2')->update(['is_paid' => 1]);
});

Route::match(['get', 'post'], '/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('about', [\App\Http\Controllers\HomeController::class, 'about'])->name('about');
// Route::get('menu', [\App\Http\Controllers\HomeController::class, 'menu'])->name('menu');
Route::match(['get', 'post'], 'contact', [\App\Http\Controllers\HomeController::class, 'contact'])->name('contact');
Route::match(['get', 'post'], 'testimonial-enquiry', [\App\Http\Controllers\HomeController::class, 'testimonialEnquiry'])->name('testimonialenquiry');
Route::get('faq', [\App\Http\Controllers\HomeController::class, 'faq'])->name('faq');

// Customer Auth Routes
Route::match(['get', 'post'], 'login', [\App\Http\Controllers\CustomerAuthController::class, 'login'])->name('customer.login');
Route::match(['get', 'post'], 'register', [\App\Http\Controllers\CustomerAuthController::class, 'register'])->name('customer.register');
Route::get('verify/{key}', [\App\Http\Controllers\CustomerAuthController::class, 'verify'])->name('customer.verify')->whereAlphaNumeric('key');
Route::post('verify-otp', [\App\Http\Controllers\CustomerAuthController::class, 'verifyOtp'])->name('customer.verify-otp');
Route::post('resend-otp', [\App\Http\Controllers\CustomerAuthController::class, 'resendOtp'])->name('customer.resend-otp');
Route::match(['get', 'post'], 'change-password', [\App\Http\Controllers\CustomerAuthController::class, 'changePassword'])->name('customer.change-password');
Route::get('logout', [\App\Http\Controllers\CustomerAuthController::class, 'logout'])->name('customer.logout');

Route::prefix('payment')->name('payment.')->group(function () {
    Route::get('success', [\App\Http\Controllers\Customer\PaymentController::class, 'success'])->name('success');
    Route::get('failed', [\App\Http\Controllers\Customer\PaymentController::class, 'failed'])->name('failed');
    Route::get('processing', [\App\Http\Controllers\Customer\PaymentController::class, 'processing'])->name('processing');
    Route::get('verify', [\App\Http\Controllers\Customer\PaymentController::class, 'verify'])->name('verify');
});

Route::get('category', [\App\Http\Controllers\Customer\CategoryController::class, 'index'])->name('customer.category');
Route::prefix('product')->name('customer.product.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Customer\ProductController::class, 'index'])->name('index');
});

// Route::prefix('customer')->name('customer.')->middleware('customer.auth')->group(function () {
Route::name('customer.')->middleware('customer.auth')->group(function () {
    Route::get('dashboard', [\App\Http\Controllers\Customer\MainController::class, 'dashboard'])->name('dashboard');
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::match(['get', 'post'], 'update', [\App\Http\Controllers\Customer\ProfileController::class, 'update'])->name('update');
        Route::match(['get', 'post'], 'change-password', [\App\Http\Controllers\Customer\ProfileController::class, 'changePassword'])->name('changepassword');
    });
    /*
    Route::get('category', [\App\Http\Controllers\Customer\CategoryController::class, 'index'])->name('category');
    Route::prefix('product')->name('product.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Customer\ProductController::class, 'index'])->name('index');
    });
    */
    Route::prefix('order')->name('order.')->group(function () {
        Route::match(['get', 'post'], 'checkout', [\App\Http\Controllers\Customer\OrderController::class, 'checkout'])->name('checkout');
        Route::get('success/{key}', [\App\Http\Controllers\Customer\OrderController::class, 'success'])->name('success');
        Route::get('/', [\App\Http\Controllers\Customer\OrderController::class, 'index'])->name('index');
    });

    Route::resource('cart', \App\Http\Controllers\Customer\CartController::class);

    Route::prefix('ajax')->name('ajax.')->group(function () {
        Route::get('subregions', [\App\Http\Controllers\Customer\AjaxController::class, 'getSubRegions'])->name('subregions');
    });
});



Route::get('/call', [TwilioController::class, 'makeCall']);
Route::any('twiml', [TwilioController::class, 'twiml'])->name('twiml');

/*
Route::get('/test', function () {
    $products = \App\Models\Product::withTrashed()->get();
    if ($products->isNotEmpty()) {
        $purpose = "P";
        \Illuminate\Support\Facades\DB::table('products')->update(['product_code'=> NULL]);

        foreach ($products as $key => $product) {

            if (strlen($product->id) === 1 ) {
                $code = $purpose.'00000'.($product->id);
            }elseif (strlen($product->id) === 2 ) {
                $code = $purpose.'0000'.($product->id);
            }elseif (strlen($product->id) === 3 ) {
                $code = $purpose.'000'.($product->id);
            }elseif (strlen($product->id) === 4 ) {
                $code = $purpose.'00'.($product->id);
            }elseif (strlen($product->id) === 5 ) {
                $code = $purpose.'0'.($product->id);
            }elseif(strlen($product->id) >= 6 ){
                $code = $purpose.''.($product->id);
            }

            //dump("product id = {$product->id} & Code = $code");
            $product->product_code = $code;
            $product->save();
            //dump("Updated product id = {$product->id} & Code = {$product->product_code}");
        }
    }
});
*/
