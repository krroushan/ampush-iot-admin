<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CustomerAuthController;
use App\Http\Controllers\Api\MotorLogController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\WebhookController;
use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\Admin\NotificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes (no authentication required)
Route::prefix('customer')->group(function () {
    // Authentication routes
    Route::post('/login', [CustomerAuthController::class, 'login']);
});

// Protected routes (authentication required)
Route::middleware(['auth:sanctum', 'customer'])->prefix('customer')->group(function () {
    // Profile routes
    Route::get('/profile', [CustomerAuthController::class, 'profile']);
    Route::put('/profile', [CustomerAuthController::class, 'updateProfile']);
    Route::post('/logout', [CustomerAuthController::class, 'logout']);
    Route::post('/refresh-token', [CustomerAuthController::class, 'refreshToken']);
    
    // My devices route
    Route::get('/devices', [DeviceController::class, 'myDevices']);
    
    // FCM token update
    Route::post('/fcm-token', [CustomerAuthController::class, 'updateFCMToken']);
});

// IoT Motor Logs API Routes (Public - for mobile app sync)
Route::prefix('logs')->group(function () {
    Route::post('/', [MotorLogController::class, 'store']); // Sync single log
    Route::post('/batch', [MotorLogController::class, 'batchStore']); // Batch sync
    Route::get('/', [MotorLogController::class, 'index']); // Get logs with filters
    Route::get('/{id}', [MotorLogController::class, 'show']); // Get single log
    Route::delete('/{id}', [MotorLogController::class, 'destroy']); // Delete log
    Route::get('/unsynced/count', [MotorLogController::class, 'unsyncedCount']); // Get unsynced count
});

// IoT Reports API Routes (Public - for mobile app reports)
Route::prefix('reports')->group(function () {
    Route::get('/daily', [ReportController::class, 'daily']); // Daily report
    Route::get('/weekly', [ReportController::class, 'weekly']); // Weekly report
    Route::get('/monthly', [ReportController::class, 'monthly']); // Monthly report
    Route::get('/custom', [ReportController::class, 'custom']); // Custom date range report
    Route::get('/summary', [ReportController::class, 'summary']); // Dashboard summary
});

// IoT Webhooks API Routes (Public - for real-time events)
Route::prefix('webhooks')->group(function () {
    Route::post('/motor-command', [WebhookController::class, 'motorCommand']); // Motor command events
    Route::post('/sms-status', [WebhookController::class, 'smsStatus']); // SMS status events
    Route::get('/health', [WebhookController::class, 'health']); // Webhook health check
});

// IoT Devices API Routes (Public - for mobile app device management)
Route::prefix('devices')->group(function () {
    Route::get('/', [DeviceController::class, 'index']); // Get all devices with filters
    Route::get('/{identifier}', [DeviceController::class, 'show']); // Get device by ID or SMS number
    Route::post('/', [DeviceController::class, 'store']); // Register/update device
    Route::post('/{smsNumber}/activity', [DeviceController::class, 'updateActivity']); // Update last activity
    Route::post('/by-phone', [DeviceController::class, 'getByPhone']); // Get device by phone number
});

// Admin Notification API Routes (Protected - for admin panel)
Route::middleware(['auth:sanctum'])->prefix('admin')->group(function () {
    Route::post('/notifications/send-all', [NotificationController::class, 'sendToAll']); // Send to all customers
    Route::post('/notifications/send-customer/{customerId}', [NotificationController::class, 'sendToCustomer']); // Send to specific customer
    Route::post('/notifications/send-by-phone', [NotificationController::class, 'sendToCustomersByPhone']); // Send by phone numbers
    Route::get('/notifications/customers', [NotificationController::class, 'getCustomersWithTokens']); // Get customers with FCM tokens
});

// Health check route
Route::get('/health', function () {
    return response()->json([
        'success' => true,
        'message' => 'API is healthy',
        'timestamp' => now()->toISOString(),
        'version' => '1.0.0'
    ]);
});

// Fallback route
Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => 'API endpoint not found'
    ], 404);
});