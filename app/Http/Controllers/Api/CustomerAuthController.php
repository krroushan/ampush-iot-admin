<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CustomerAuthController extends Controller
{

    /**
     * Customer Login
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required_without:phone_number|email',
            'phone_number' => 'required_without:email|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Determine login method and find customer
            if ($request->has('email')) {
                $customer = User::where('email', $request->email)
                    ->where('role', 'customer')
                    ->first();
            } else {
                $customer = User::where('phone_number', $request->phone_number)
                    ->where('role', 'customer')
                    ->first();
            }

            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials'
                ], 401);
            }

            // Verify password
            if (!Hash::check($request->password, $customer->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials'
                ], 401);
            }

            // Create token
            $token = $customer->createToken('CustomerAuthToken')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'data' => [
                    'customer' => [
                        'id' => $customer->id,
                        'name' => $customer->name,
                        'email' => $customer->email,
                        'phone_number' => $customer->phone_number,
                        'address_line_1' => $customer->address_line_1,
                        'address_line_2' => $customer->address_line_2,
                        'city' => $customer->city,
                        'state' => $customer->state,
                        'postal_code' => $customer->postal_code,
                        'country' => $customer->country,
                        'profile_photo_url' => $customer->profile_photo_url,
                        'created_at' => $customer->created_at,
                    ],
                    'token' => $token,
                    'token_type' => 'Bearer'
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Login failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Customer Logout
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            // Revoke the current token
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Logout successful'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Logout failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Customer Profile
     */
    public function profile(Request $request): JsonResponse
    {
        try {
            $customer = $request->user();

            return response()->json([
                'success' => true,
                'data' => [
                    'customer' => [
                        'id' => $customer->id,
                        'name' => $customer->name,
                        'email' => $customer->email,
                        'phone_number' => $customer->phone_number,
                        'address_line_1' => $customer->address_line_1,
                        'address_line_2' => $customer->address_line_2,
                        'city' => $customer->city,
                        'state' => $customer->state,
                        'postal_code' => $customer->postal_code,
                        'country' => $customer->country,
                        'profile_photo_url' => $customer->profile_photo_url,
                        'created_at' => $customer->created_at,
                        'updated_at' => $customer->updated_at,
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch profile',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update Customer Profile
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $customer = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $customer->id,
            'phone_number' => 'sometimes|required|string|max:20',
            'address_line_1' => 'sometimes|required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'sometimes|required|string|max:100',
            'state' => 'sometimes|required|string|max:100',
            'postal_code' => 'sometimes|required|string|max:20',
            'country' => 'sometimes|required|string|max:50',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $updateData = [];

            // Only update provided fields
            $allowedFields = [
                'name', 'email', 'phone_number', 'address_line_1', 
                'address_line_2', 'city', 'state', 'postal_code', 'country'
            ];

            foreach ($allowedFields as $field) {
                if ($request->has($field)) {
                    $updateData[$field] = $request->input($field);
                }
            }

            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                // Delete old photo if exists
                if ($customer->profile_photo && file_exists(public_path($customer->profile_photo))) {
                    unlink(public_path($customer->profile_photo));
                }
                
                // Upload new photo
                $file = $request->file('profile_photo');
                $filename = 'customer_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('images/profile-photos'), $filename);
                $updateData['profile_photo'] = 'images/profile-photos/' . $filename;
            }

            $customer->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => [
                    'customer' => [
                        'id' => $customer->id,
                        'name' => $customer->name,
                        'email' => $customer->email,
                        'phone_number' => $customer->phone_number,
                        'address_line_1' => $customer->address_line_1,
                        'address_line_2' => $customer->address_line_2,
                        'city' => $customer->city,
                        'state' => $customer->state,
                        'postal_code' => $customer->postal_code,
                        'country' => $customer->country,
                        'profile_photo_url' => $customer->profile_photo_url,
                        'updated_at' => $customer->updated_at,
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Profile update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    /**
     * Refresh Token
     */
    public function refreshToken(Request $request): JsonResponse
    {
        try {
            $customer = $request->user();

            // Revoke current token
            $request->user()->currentAccessToken()->delete();

            // Create new token
            $token = $customer->createToken('CustomerAuthToken')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Token refreshed successfully',
                'data' => [
                    'token' => $token,
                    'token_type' => 'Bearer'
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token refresh failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
