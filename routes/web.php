<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::get('dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('dashboard');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');

    // Customer Management Routes
    Route::resource('customers', \App\Http\Controllers\CustomerController::class);
    Route::get('customers/search', [\App\Http\Controllers\CustomerController::class, 'search'])->name('customers.search');
    Route::post('customers/{customer}/assign-device', [\App\Http\Controllers\CustomerController::class, 'assignDevice'])->name('customers.assign-device');

    // Device Management Routes
    Route::resource('devices', \App\Http\Controllers\DeviceController::class);
    Route::post('devices/{device}/toggle-status', [\App\Http\Controllers\DeviceController::class, 'toggleStatus'])->name('devices.toggle-status');
    Route::post('devices/{device}/assign', [\App\Http\Controllers\DeviceController::class, 'assign'])->name('devices.assign');
    Route::post('devices/{device}/unassign', [\App\Http\Controllers\DeviceController::class, 'unassign'])->name('devices.unassign');

    // Motor Logs Management Routes
    Route::resource('motor-logs', \App\Http\Controllers\MotorLogsController::class);
    Route::post('motor-logs/bulk-delete', [\App\Http\Controllers\MotorLogsController::class, 'bulkDelete'])->name('motor-logs.bulk-delete');

    // Notification Routes
    Route::get('notifications', [\App\Http\Controllers\Admin\NotificationWebController::class, 'index'])->name('notifications.index');
    Route::get('notifications/send', [\App\Http\Controllers\Admin\NotificationWebController::class, 'send'])->name('notifications.send');
    Route::post('notifications/send', [\App\Http\Controllers\Admin\NotificationWebController::class, 'store'])->name('notifications.store');

    // Reports Routes
    Route::get('reports', [\App\Http\Controllers\Admin\ReportsController::class, 'index'])->name('admin.reports.index');
});

require __DIR__.'/auth.php';
