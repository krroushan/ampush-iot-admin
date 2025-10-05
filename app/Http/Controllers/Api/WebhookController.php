<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * Motor Command Webhook
     * POST /api/webhooks/motor-command
     */
    public function motorCommand(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'event' => 'required|string',
            'command' => 'required|string|in:MOTOR_ON,MOTOR_OFF,STATUS',
            'status' => 'required|string|in:SMS_SENT,SMS_DELIVERED,SMS_FAILED,MOTOR_ON,MOTOR_OFF,ERROR',
            'phone_number' => 'required|string|max:20',
            'timestamp' => 'required|integer|min:0',
            'app' => 'required|string',
            'version' => 'required|string'
        ]);

        if ($validator->fails()) {
            Log::warning('Invalid motor command webhook payload', [
                'errors' => $validator->errors(),
                'payload' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Invalid webhook payload',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            // Log the webhook event
            Log::info('Motor command webhook received', [
                'event' => $request->event,
                'command' => $request->command,
                'status' => $request->status,
                'phone_number' => $request->phone_number,
                'timestamp' => $request->timestamp,
                'app' => $request->app,
                'version' => $request->version,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            $response = [
                'success' => true,
                'message' => 'Motor command webhook processed successfully',
                'received_at' => now()->toISOString(),
                'event_id' => uniqid('motor_cmd_', true)
            ];

            return response()->json($response, 200);

        } catch (\Exception $e) {
            Log::error('Motor command webhook processing failed', [
                'error' => $e->getMessage(),
                'payload' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Webhook processing failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * SMS Status Webhook
     * POST /api/webhooks/sms-status
     */
    public function smsStatus(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'event' => 'required|string',
            'sms_type' => 'required|string|in:MOTOR_ON,MOTOR_OFF,STATUS',
            'phone_number' => 'required|string|max:20',
            'message' => 'required|string',
            'success' => 'required|boolean',
            'timestamp' => 'required|integer|min:0',
            'app' => 'required|string'
        ]);

        if ($validator->fails()) {
            Log::warning('Invalid SMS status webhook payload', [
                'errors' => $validator->errors(),
                'payload' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Invalid webhook payload',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            // Log the webhook event
            Log::info('SMS status webhook received', [
                'event' => $request->event,
                'sms_type' => $request->sms_type,
                'phone_number' => $request->phone_number,
                'message' => $request->message,
                'success' => $request->success,
                'timestamp' => $request->timestamp,
                'app' => $request->app,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            $response = [
                'success' => true,
                'message' => 'SMS status webhook processed successfully',
                'received_at' => now()->toISOString(),
                'event_id' => uniqid('sms_status_', true)
            ];

            return response()->json($response, 200);

        } catch (\Exception $e) {
            Log::error('SMS status webhook processing failed', [
                'error' => $e->getMessage(),
                'payload' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Webhook processing failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Webhook Health Check
     * GET /api/webhooks/health
     */
    public function health(): JsonResponse
    {
        return response()->json([
            'status' => 'healthy',
            'timestamp' => now()->toISOString(),
            'webhooks' => [
                'motor_command' => '/api/webhooks/motor-command',
                'sms_status' => '/api/webhooks/sms-status'
            ],
            'version' => '1.0.0'
        ], 200);
    }
}