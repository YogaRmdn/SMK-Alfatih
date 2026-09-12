<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LoginLogController;
use App\Http\Controllers\Admin\RegistrationController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Public\AnnouncementController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\GalleryController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\NewsController;
use App\Http\Controllers\Public\PageController;
use App\Http\Controllers\Public\PPDBController;
use App\Http\Controllers\Public\ProgramController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/program-keahlian', [ProgramController::class, 'index'])->name('programs.index');
Route::get('/program-keahlian/{program:slug}', [ProgramController::class, 'show'])->name('programs.show');

Route::get('/berita', [NewsController::class, 'index'])->name('news.index');
Route::get('/berita/{news:slug}', [NewsController::class, 'show'])->name('news.show');

Route::get('/galeri', [GalleryController::class, 'index'])->name('gallery.index');

Route::get('/pengumuman', [AnnouncementController::class, 'index'])->name('announcements.index');

Route::get('/kontak', [ContactController::class, 'index'])->name('contact.index');
Route::post('/kontak', [ContactController::class, 'send'])->middleware('throttle:5,1')->name('contact.send');

Route::get('/ppdb', [PPDBController::class, 'index'])->name('ppdb.index');
Route::get('/ppdb/siswa', [PPDBController::class, 'siswa'])->name('ppdb.siswa');
Route::post('/ppdb', [PPDBController::class, 'store'])->middleware('throttle:5,1')->name('ppdb.store');
Route::get('/ppdb/status', [PPDBController::class, 'status'])->name('ppdb.status');

Route::get('/{slug}', [PageController::class, 'show'])
    ->where('slug', '(?!admin)[a-z0-9-]+')
    ->name('pages.show');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::middleware('superadmin')->group(function () {
            Route::get('/login-logs', [LoginLogController::class, 'index'])
                ->name('login-logs.index');

            Route::get('/users', [UserManagementController::class, 'index'])
                ->name('users.index');
            Route::post('/users', [UserManagementController::class, 'store'])
                ->name('users.store');
            Route::put('/users/{user}', [UserManagementController::class, 'update'])
                ->name('users.update');
        });

        Route::get('/registrations', [RegistrationController::class, 'index'])
            ->name('registrations.index');
        Route::delete('/registrations', [RegistrationController::class, 'destroyAll'])
            ->name('registrations.destroy-all');
        Route::get('/registrations/{registration}', [RegistrationController::class, 'show'])
            ->name('registrations.show');
        Route::put('/registrations/{registration}', [RegistrationController::class, 'update'])
            ->name('registrations.update');
        Route::delete('/registrations/{registration}', [RegistrationController::class, 'destroy'])
            ->name('registrations.destroy');
    });
});
