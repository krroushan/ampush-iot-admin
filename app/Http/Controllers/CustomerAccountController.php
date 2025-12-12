<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CustomerAccountController extends Controller
{
    /**
     * Show the account deletion form
     */
    public function showDeleteForm()
    {
        return view('customer.delete-account');
    }

    /**
     * Delete customer account
     */
    public function deleteAccount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string',
            'password' => 'required|string',
            'confirmation' => 'required|string|in:DELETE MY ACCOUNT'
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Find customer by phone number
            $customer = User::where('phone_number', $request->phone_number)
                ->where('role', 'customer')
                ->first();

            if (!$customer) {
                return back()
                    ->withErrors(['phone_number' => 'Customer not found with this phone number'])
                    ->withInput();
            }

            // Verify password
            if (!Hash::check($request->password, $customer->password)) {
                return back()
                    ->withErrors(['password' => 'Invalid password'])
                    ->withInput();
            }

            // Verify confirmation text
            if ($request->confirmation !== 'DELETE MY ACCOUNT') {
                return back()
                    ->withErrors(['confirmation' => 'Confirmation text must be exactly: DELETE MY ACCOUNT'])
                    ->withInput();
            }

            // Revoke all tokens
            $customer->tokens()->delete();

            // Delete profile photo if exists
            if ($customer->profile_photo && file_exists(public_path($customer->profile_photo))) {
                unlink(public_path($customer->profile_photo));
            }

            // Unassign devices (set user_id to null)
            \App\Models\Device::where('user_id', $customer->id)->update(['user_id' => null]);

            // Delete notifications related to this user
            \App\Models\Notification::where('user_id', $customer->id)->delete();

            // Delete the customer account
            $customer->delete();

            return redirect()->route('customer.account.deleted');

        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Failed to delete account: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Show success page after account deletion
     */
    public function deleted()
    {
        return view('customer.account-deleted');
    }
}

