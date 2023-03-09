<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RombelController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SPPController;
use App\Http\Controllers\OfficerController;
use App\Http\Controllers\PaymentController;

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

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::middleware(['admin'])->group(function () {
        Route::resource('rombel', RombelController::class);
        Route::resource('spp', SPPController::class);
        Route::resource('officer', OfficerController::class);
        Route::resource('student', StudentController::class);
    });

    //Payment
    Route::get('payment/getData/{nisn}/{berapa}', [PaymentController::class, 'getData'])->name('payment.getData');
    Route::resource('payment', PaymentController::class);

    //Rombel
    Route::get('/rombel/{id}/delete', [RombelController::class, 'delete'])->name('rombel.delete');
    Route::post('edit-rombel', [RombelController::class, 'edit']);

    //Spp
    Route::get('/spp/{id}/delete', [SPPController::class, 'delete'])->name('spp.delete');
    Route::post('edit-spp', [SPPController::class, 'edit']);

    //Officer
    Route::get('/officer/{id}/delete', [OfficerController::class, 'delete'])->name('officer.delete');
    Route::post('edit-officer', [OfficerController::class, 'edit']);

    //Student
    Route::get('/student/{id}/delete', [StudentController::class, 'delete'])->name('student.delete');
    Route::post('edit-student', [StudentController::class, 'edit']);

    Route::middleware(['petugas'])->group(function () {
        
    });
    // Route::middleware(['siswa'])->group(function () {
        
    // });
});
