<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminManagementController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderManagementController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'superAdminMiddleware'])
    ->prefix('superadmin')
    ->group(function () {
        Route::get('/admin-management', [AdminManagementController::class, 'manageAdmin'])->name('admin#management');

        Route::get('/admin-management/add-admin', [AdminManagementController::class, 'addAdminPage'])->name(
            'admin#addAdminPage',
        );

        Route::post('/admin-management/add-admin', [AdminManagementController::class, 'addAdmin'])->name(
            'admin#addAdmin',
        );

        Route::post('/admin/delete/{id}', [AdminManagementController::class, 'deleteAdmin'])->name('admin#deleteAdmin');
    });

Route::middleware(['auth', 'adminMiddleware'])
    ->prefix('admin')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'dashboard'])->name('admin#home');

        // category
        Route::group(['prefix' => 'category'], function () {
            Route::get('/list', [CategoryController::class, 'list'])->name('category#list');

            Route::post('/create', [CategoryController::class, 'create'])->name('category#create');

            Route::post('/delete/{id}', [CategoryController::class, 'delete'])->name('category#delete');

            Route::post('/update/{id}', [CategoryController::class, 'update'])->name('category#update');
        });

        // product
        Route::group(['prefix' => 'product'], function () {
            Route::get('list', [ProductController::class, 'list'])->name('product#list');

            Route::get('create', [ProductController::class, 'createPage'])->name('product#createPage');

            Route::post('create', [ProductController::class, 'create'])->name('product#create');

            Route::get('/edit/{id}', [ProductController::class, 'edit'])->name('product#edit');

            Route::post('/update/{id}', [ProductController::class, 'update'])->name('product#update');

            Route::post('/delete/{id}', [ProductController::class, 'delete'])->name('product#delete');
        });

        // profile
        Route::get('/profile', [ProfileController::class, 'profile'])->name('admin#profile');

        Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('admin#updateProfile');

        // order management
        Route::get('/orders', [OrderManagementController::class, 'orderBoard'])->name('admin#orderBoard');

        Route::get('/orders/{order}', [OrderManagementController::class, 'orderBoardDetails'])->name(
            'admin#orderBoardDetails',
        );

        Route::patch('/orders/{order}/status', [OrderManagementController::class, 'updateStatus'])->name(
            'admin.order.status',
        );

        Route::patch('/orders/{order}/payment/verify', [OrderManagementController::class, 'verifyPayment'])->name(
            'admin.order.payment.verify',
        );

        Route::patch('/orders/{order}/payment/reject', [OrderManagementController::class, 'rejectPayment'])->name(
            'admin.order.payment.reject',
        );

        // payment
        Route::get('/payment', [PaymentMethodController::class, 'payment'])->name('admin#payment');

        Route::get('/payment/create', [PaymentMethodController::class, 'createPayment'])->name('admin#createPayment');

        Route::post('/payment/store', [PaymentMethodController::class, 'storePayment'])->name('admin#storePayment');

        Route::get('/payment/edit/{id}', [PaymentMethodController::class, 'editPayment'])->name('admin#editPayment');

        Route::put('/payment/update/{id}', [PaymentMethodController::class, 'updatePayment'])->name(
            'admin#updatePayment',
        );

        Route::delete('/payment/delete/{id}', [PaymentMethodController::class, 'deletePayment'])->name(
            'admin#deletePayment',
        );

        Route::patch('/payment/toggle/{id}', [PaymentMethodController::class, 'toggleStatus'])->name(
            'admin#togglePayment',
        );
    });
