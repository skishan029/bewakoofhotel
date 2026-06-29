<?php

use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::match(['get', 'post'], '/', [\App\Http\Controllers\Admin\AuthController::class, 'index'])->name('index')->middleware('admin.guest');

    Route::get('logout', [\App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout');
    Route::match(['get', 'post'], 'verify/{otp_key}',  [\App\Http\Controllers\Admin\AuthController::class, 'verifyLoginOtp'])->name('verify')->whereAlphaNumeric('otp_key');


    Route::middleware(['admin.auth'])->group(function () {
        Route::match(['get', 'post'], 'dashboard', [\App\Http\Controllers\Admin\MainController::class, 'dashboard'])->name('dashboard');

        Route::resource('region', \App\Http\Controllers\Admin\RegionController::class);

        Route::prefix('frontend')->name('frontend.')->middleware('admin.isaccessmenu:5')->group(function () {

            Route::match(['get', 'post'], 'setting', [\App\Http\Controllers\Admin\MainController::class, 'panelSetting'])->name('setting')->middleware('admin.isaccessmenu:11');

            Route::prefix('slider')->name('slider.')->middleware('admin.isaccessmenu:6')->group(function () {
                Route::get('/', [\App\Http\Controllers\Admin\Frontend\SliderController::class, 'index'])->name('index');
                Route::match(['get', 'post'], 'upload', [\App\Http\Controllers\Admin\Frontend\SliderController::class, 'upload'])->name('upload');
                Route::match(['get', 'post'], 'edit/{id}', [\App\Http\Controllers\Admin\Frontend\SliderController::class, 'edit'])->name('edit')->whereNumber('id');
                Route::post('delete', [\App\Http\Controllers\Admin\Frontend\SliderController::class, 'delete'])->name('delete');
            });

            Route::prefix('gallery')->name('gallery.')->middleware('admin.isaccessmenu:8')->group(function () {
                Route::get('/', [\App\Http\Controllers\Admin\Frontend\GalleryController::class, 'index'])->name('index');
                Route::match(['get', 'post'], 'upload', [\App\Http\Controllers\Admin\Frontend\GalleryController::class, 'upload'])->name('upload');
                Route::match(['get', 'post'], 'edit/{id}', [\App\Http\Controllers\Admin\Frontend\GalleryController::class, 'edit'])->name('edit')->whereNumber('id');
                Route::post('delete', [\App\Http\Controllers\Admin\Frontend\GalleryController::class, 'delete'])->name('delete');
            });

            Route::prefix('frequentlyaskedquestion')->name('frequentlyaskedquestion.')->middleware('admin.isaccessmenu:9')->group(function () {
                Route::get('/', [\App\Http\Controllers\Admin\Frontend\FrequentlyAskedQuestionController::class, 'index'])->name('index');
                Route::match(['get', 'post'], 'create', [\App\Http\Controllers\Admin\Frontend\FrequentlyAskedQuestionController::class, 'create'])->name('create');
                Route::match(['get', 'post'], 'edit/{id}', [\App\Http\Controllers\Admin\Frontend\FrequentlyAskedQuestionController::class, 'edit'])->name('edit')->whereNumber('id');
            });


            Route::prefix('fooditem')->name('fooditem.')->middleware('admin.isaccessmenu:7')->group(function () {
                Route::get('/', [\App\Http\Controllers\Admin\Frontend\FoodItemController::class, 'index'])->name('index');
                Route::match(['get', 'post'], 'upload', [\App\Http\Controllers\Admin\Frontend\FoodItemController::class, 'upload'])->name('upload');
                Route::match(['get', 'post'], 'edit/{id}', [\App\Http\Controllers\Admin\Frontend\FoodItemController::class, 'edit'])->name('edit')->whereNumber('id');
                Route::post('delete', [\App\Http\Controllers\Admin\Frontend\FoodItemController::class, 'delete'])->name('delete');
            });

            Route::prefix('testimonial')->middleware('admin.isaccessmenu:10')->name('testimonial.')->group(function () {
                Route::get('/', [\App\Http\Controllers\Admin\Frontend\TestimonialController::class, 'index'])->name('index');
                Route::match(['get', 'post'], 'create', [\App\Http\Controllers\Admin\Frontend\TestimonialController::class, 'create'])->name('create');
                Route::match(['get', 'post'], 'edit/{id}', [\App\Http\Controllers\Admin\Frontend\TestimonialController::class, 'edit'])->name('edit')->whereNumber('id');
                Route::post('delete', [\App\Http\Controllers\Admin\Frontend\TestimonialController::class, 'delete'])->name('delete');
            });

            Route::prefix('video')->name('video.')->middleware('admin.isaccessmenu:5')->group(function () {
                Route::get('/', [\App\Http\Controllers\Admin\Frontend\VideoController::class, 'index'])->name('index');
                Route::match(['get', 'post'], 'upload', [\App\Http\Controllers\Admin\Frontend\VideoController::class, 'video'])->name('upload');
                Route::match(['get', 'post'], 'edit/{id}', [\App\Http\Controllers\Admin\Frontend\VideoController::class, 'edit'])->name('edit')->whereNumber('id');
                Route::post('delete', [\App\Http\Controllers\Admin\Frontend\VideoController::class, 'delete'])->name('delete');
            });
            Route::prefix('offer')->name('offer.')->middleware('admin.isaccessmenu:5')->group(function () {
                Route::get('/', [\App\Http\Controllers\Admin\Frontend\OfferController::class, 'index'])->name('index');
                Route::match(['get', 'post'], 'create', [\App\Http\Controllers\Admin\Frontend\OfferController::class, 'offerSet'])->name('create');
                Route::match(['get', 'post'], 'edit/{id}', [\App\Http\Controllers\Admin\Frontend\OfferController::class, 'edit'])->name('edit')->whereNumber('id');
                Route::post('delete', [\App\Http\Controllers\Admin\Frontend\OfferController::class, 'delete'])->name('delete');
            });
            Route::prefix('contactus')->name('contactus.')->middleware('admin.isaccessmenu:5')->group(function () {
                // Route::get('/', [\App\Http\Controllers\Admin\Frontend\OfferController::class, 'index'])->name('index');
                Route::match(['get', 'post'], 'create', [\App\Http\Controllers\Admin\Frontend\ContactusController::class, 'imageAdd'])->name('create');
                // Route::match(['get', 'post'], 'edit/{id}', [\App\Http\Controllers\Admin\Frontend\OfferController::class, 'edit'])->name('edit')->whereNumber('id');
                // Route::post('delete', [\App\Http\Controllers\Admin\Frontend\OfferController::class, 'delete'])->name('delete');
            });
        });

        Route::prefix('profile')->name('profile.')->group(function () {
            Route::match(['get', 'post'], 'change-password', [\App\Http\Controllers\Admin\MainController::class, 'changePassword'])->name('changepassword');

            Route::match(['get', 'post'], 'employee-change-password', [\App\Http\Controllers\Admin\MainController::class, 'empChangePassword'])->name('empchangepassword');
            Route::post('emp-password', [\App\Http\Controllers\Admin\MainController::class, 'empPassword'])->name('emppassword');
        });

        Route::prefix('productmaster')->name('productmaster.')->group(function () {

            Route::prefix('category')->name('category.')->group(function () {
                Route::get('/', [\App\Http\Controllers\Admin\ProductMaster\CategoryController::class, 'index'])->name('index');
                Route::match(['get', 'post'], 'create', [\App\Http\Controllers\Admin\ProductMaster\CategoryController::class, 'create'])->name('create');
                Route::match(['get', 'post'], 'edit/{id}', [\App\Http\Controllers\Admin\ProductMaster\CategoryController::class, 'edit'])->name('edit')->whereNumber('id');
            });

            Route::prefix('product')->name('product.')->middleware('admin.isaccessmenu:1')->group(function () {
                Route::get('/', [\App\Http\Controllers\Admin\ProductMaster\ProductController::class, 'index'])->name('index');
                Route::match(['get', 'post'], 'create', [\App\Http\Controllers\Admin\ProductMaster\ProductController::class, 'create'])->name('create');
                Route::match(['get', 'post'], 'edit/{id}', [\App\Http\Controllers\Admin\ProductMaster\ProductController::class, 'edit'])->name('edit');
                Route::get('status/{id}', [\App\Http\Controllers\Admin\ProductMaster\ProductController::class, 'changeStatus'])->name('status')->whereNumber('id');
            });
        });

        Route::prefix('order')->name('order.')->middleware('admin.isaccessmenu:2')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('index');
            Route::get('pending-index', [\App\Http\Controllers\Admin\OrderController::class, 'pendingIndex'])->name('pendingindex');
            Route::post('place-order-form', [\App\Http\Controllers\Admin\OrderController::class, 'placeOrderForm'])->name('placeorderform');
            Route::post('place-order-submit', [\App\Http\Controllers\Admin\OrderController::class, 'placeOrderSubmit'])->name('placeordersubmit');
            Route::match(['get', 'post'], 'create', [\App\Http\Controllers\Admin\OrderController::class, 'create'])->name('create');
            Route::match(['get', 'post'], 'edit/{id}', [\App\Http\Controllers\Admin\OrderController::class, 'edit'])->name('edit');
            Route::get('summary/{order_key}', [\App\Http\Controllers\Admin\OrderController::class, 'orderSummary'])->name('summary')->whereAlphaNumeric('order_key');
            Route::get('details/{order_key}', [\App\Http\Controllers\Admin\OrderController::class, 'details'])->name('details')->whereAlphaNumeric('order_key');
            Route::get('print/{order_key}', [\App\Http\Controllers\Admin\OrderController::class, 'print'])->name('print')->whereAlphaNumeric('order_key');
        });

        Route::prefix('customer-order')->name('customerorder.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\CustomerOrderController::class, 'index'])->name('index');
            Route::get('details/{order_key}', [\App\Http\Controllers\Admin\CustomerOrderController::class, 'details'])->name('details')->whereAlphaNumeric('order_key');
            Route::post('update-status', [\App\Http\Controllers\Admin\CustomerOrderController::class, 'updateStatus'])->name('updatestatus');
        });

        Route::resource('customer', \App\Http\Controllers\Admin\CustomerController::class);

        Route::prefix('report')->name('report.')->middleware('admin.isaccessmenu:2')->group(function () {
            Route::get('order', [\App\Http\Controllers\Admin\OrderController::class, 'report'])->name('index');
            Route::get('product', [\App\Http\Controllers\Admin\OrderController::class, 'productReport'])->name('product');
            Route::get('expense', [\App\Http\Controllers\Admin\ExpenceController::class, 'report'])->name('expense');
            Route::get('attendance', [\App\Http\Controllers\Admin\AttendanceController::class, 'report'])->name('attendance');
            Route::get('salary', [\App\Http\Controllers\Admin\SalaryReportController::class, 'index'])->name('salary');
        });

        Route::prefix('expence')->name('expence.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\ExpenceController::class, 'index'])->name('index');
            Route::match(['get', 'post'], 'expence-Add', [\App\Http\Controllers\Admin\ExpenceController::class, 'expenceAdd'])->name('expenceadd');
            Route::match(['get', 'post'], 'edit/{id}', [\App\Http\Controllers\Admin\ExpenceController::class, 'edit'])->name('edit')->whereNumber('id');
            Route::post('delete', [\App\Http\Controllers\Admin\ExpenceController::class, 'delete'])->name('delete');
        });

        Route::prefix('employee')->name('employee.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\EmployeeController::class, 'index'])->name('index');
            Route::match(['get', 'post'], 'create', [\App\Http\Controllers\Admin\EmployeeController::class, 'create'])->name('create');
            Route::match(['get', 'post'], 'edit/{id}', [\App\Http\Controllers\Admin\EmployeeController::class, 'edit'])->name('edit')->whereNumber('id');
            Route::post('delete', [\App\Http\Controllers\Admin\EmployeeController::class, 'delete'])->name('delete');
            Route::post('restore', [\App\Http\Controllers\Admin\EmployeeController::class, 'restore'])->name('restore');
        });

        Route::prefix('attendance')->name('attendance.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\AttendanceController::class, 'index'])->name('index');
            Route::match(['get', 'post'], 'create', [\App\Http\Controllers\Admin\AttendanceController::class, 'create'])->name('create');
            Route::post('checkattendance', [\App\Http\Controllers\Admin\AttendanceController::class, 'checkAttendance'])->name('checkattendance');
            Route::match(['get', 'post'], 'edit/{id}', [\App\Http\Controllers\Admin\AttendanceController::class, 'edit'])->name('edit')->whereNumber('id');
            Route::post('delete', [\App\Http\Controllers\Admin\AttendanceController::class, 'delete'])->name('delete');
        });


        Route::prefix('ajax')->name('ajax.')->group(function () {
            Route::prefix('changestatus')->name('changestatus.')->group(function () {
                Route::post('user', [\App\Http\Controllers\Admin\AjaxDeleteController::class, 'userStatus'])->name('user');
                Route::post('product', [\App\Http\Controllers\Admin\AjaxDeleteController::class, 'productStatus'])->name('product');
                Route::post('productcategory', [\App\Http\Controllers\Admin\AjaxDeleteController::class, 'productCategoryStatus'])->name('productcategory');
                Route::post('frequentlyaskedquestion', [\App\Http\Controllers\Admin\AjaxDeleteController::class, 'frequentlyAskedQuestionStatus'])->name('frequentlyaskedquestion');
                Route::post('testimonial', [\App\Http\Controllers\Admin\AjaxDeleteController::class, 'testimonialStatus'])->name('testimonial');
                Route::post('region', [\App\Http\Controllers\Admin\AjaxDeleteController::class, 'regionStatus'])->name('region');
            });
        });

        Route::get('backup', [\App\Http\Controllers\Admin\BackupController::class, 'index'])->name('databasebackup');
    });
});
