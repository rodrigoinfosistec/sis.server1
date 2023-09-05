<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsergroupController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuditController;

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ProviderController;

use App\Http\Controllers\ProductgroupController;
use App\Http\Controllers\ProductController;

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeevacationController;
use App\Http\Controllers\EmployeeattestController;
use App\Http\Controllers\EmployeeabsenceController;

use App\Http\Controllers\HolidayController;

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ClockController;

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/usergroup', [UsergroupController::class, 'index'])->name('usergroup');
    Route::get('/user', [UserController::class, 'index'])->name('user');
    Route::get('/audit', [AuditController::class, 'index'])->name('audit');

    Route::get('/company', [CompanyController::class, 'index'])->name('company');
    Route::get('/provider', [ProviderController::class, 'index'])->name('provider');

    Route::get('/productgroup', [ProductgroupController::class, 'index'])->name('productgroup');
    Route::get('/product', [ProductController::class, 'index'])->name('product');

    Route::get('/invoice', [InvoiceController::class, 'index'])->name('invoice');
    Route::get('/price-zip/{invoice_id}/', [InvoiceController::class, 'priceZip'])->name('price-zip');

    Route::get('/employee', [EmployeeController::class, 'index'])->name('employee');
    Route::get('/employeevacation', [EmployeevacationController::class, 'index'])->name('employeevacation');
    Route::get('/employeeattest', [EmployeeattestController::class, 'index'])->name('employeeattest');
    Route::get('/employeeabsence', [EmployeeabsenceController::class, 'index'])->name('employeeabsence');

    Route::get('/holiday', [HolidayController::class, 'index'])->name('holiday');

    Route::get('/clock', [ClockController::class, 'index'])->name('clock');

});
