<?php

use App\Http\Controllers\Admin\Mosque\AnnouncementController as MosqueAnnouncementController;
use App\Http\Controllers\Admin\Mosque\MosqueSettingsController;
use App\Http\Controllers\Admin\Mosque\RunningTextController as MosqueRunningTextController;
use App\Http\Controllers\Admin\Mosque\FridayOfficerController as MosqueFridayOfficerController;
use App\Http\Controllers\Admin\Mosque\KajianController as MosqueKajianController;
use App\Http\Controllers\Admin\Super\MosqueController as SuperMosqueController;
use App\Http\Controllers\DisplayController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Registrasi masjid publik
Route::get('/registrasi-masjid', function () {
    return view('admin.super.mosques.create', [
        'action' => route('public.mosques.store'),
    ]);
})->name('public.mosques.create');
Route::post('/registrasi-masjid', [SuperMosqueController::class, 'store'])->name('public.mosques.store');

Route::middleware(['auth', 'role:super_admin'])->prefix('super')->group(function () {
    Route::resource('mosques', SuperMosqueController::class);
});

Route::middleware(['auth', 'role:mosque_admin'])->prefix('mosque')->group(function () {
    Route::resource('settings', MosqueSettingsController::class)->only(['index', 'store']);
    Route::delete('settings/logo', [MosqueSettingsController::class, 'deleteLogo'])->name('settings.deleteLogo');
    Route::delete('settings/wallpaper', [MosqueSettingsController::class, 'deleteWallpaper'])->name('settings.deleteWallpaper');
    Route::resource('announcements', MosqueAnnouncementController::class)->only(['index', 'store', 'destroy', 'edit', 'update']);
    Route::resource('running-texts', MosqueRunningTextController::class)->only(['index', 'store', 'destroy']);
    Route::resource('friday-officers', MosqueFridayOfficerController::class)->only(['index', 'store', 'destroy', 'edit', 'update']);
    Route::resource('kajians', MosqueKajianController::class)->only(['index', 'store', 'destroy', 'edit', 'update']);
    Route::get('profile', [\App\Http\Controllers\Admin\Mosque\MosqueProfileController::class, 'index'])->name('mosque.profile');
    Route::post('profile', [\App\Http\Controllers\Admin\Mosque\MosqueProfileController::class, 'store'])->name('mosque.profile.store');
});

Route::get('/m/{slug}', [DisplayController::class, 'show'])->name('display.show');
Route::get('/m/{slug}/pulse', [DisplayController::class, 'pulse'])->name('display.pulse');
