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

use App\Http\Controllers\ClockregistryController;
use App\Http\Controllers\ClockregistryemployeeController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeebaseController;
use App\Http\Controllers\EmployeevacationController;
use App\Http\Controllers\EmployeeattestController;
use App\Http\Controllers\EmployeelicenseController;
use App\Http\Controllers\EmployeeabsenceController;
use App\Http\Controllers\EmployeeallowanceController;
use App\Http\Controllers\EmployeeeasyController;
use App\Http\Controllers\EmployeepayController;
use App\Http\Controllers\EmployeeseparateController;

use App\Http\Controllers\HolidayController;

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ClockController;
use App\Http\Controllers\ClockbaseController;

use App\Http\Controllers\PointeventController;

use App\Http\Controllers\AccountController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ConcessionaireController;

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

    Route::get('/clockregistry', [ClockregistryController::class, 'index'])->name('clockregistry');
    Route::get('/clockregistryemployee', [ClockregistryemployeeController::class, 'index'])->name('clockregistryemployee');
    Route::get('/employee', [EmployeeController::class, 'index'])->name('employee');
    Route::get('/employeebase', [EmployeebaseController::class, 'index'])->name('employeebase');
    Route::get('/employeevacation', [EmployeevacationController::class, 'index'])->name('employeevacation');
    Route::get('/employeeattest', [EmployeeattestController::class, 'index'])->name('employeeattest');
    Route::get('/employeelicense', [EmployeelicenseController::class, 'index'])->name('employeelicense');
    Route::get('/employeeabsence', [EmployeeabsenceController::class, 'index'])->name('employeeabsence');
    Route::get('/employeeallowance', [EmployeeallowanceController::class, 'index'])->name('employeeallowance');
    Route::get('/employeeeasy', [EmployeeeasyController::class, 'index'])->name('employeeeasy');
    Route::get('/employeepay', [EmployeepayController::class, 'index'])->name('employeepay');
    Route::get('/employeeseparate', [EmployeeseparateController::class, 'index'])->name('employeeseparate');

    Route::get('/holiday', [HolidayController::class, 'index'])->name('holiday');

    Route::get('/clock', [ClockController::class, 'index'])->name('clock');
    Route::get('/clockbase', [ClockbaseController::class, 'index'])->name('clockbase');

    Route::get('/pointevent', [PointeventController::class, 'index'])->name('pointevent');

    Route::get('/account', [AccountController::class, 'index'])->name('account');
    Route::get('/bank', [BankController::class, 'index'])->name('bank');
    Route::get('/document', [DocumentController::class, 'index'])->name('document');
    Route::get('/concessionaire', [ConcessionaireController::class, 'index'])->name('concessionaire');
});
