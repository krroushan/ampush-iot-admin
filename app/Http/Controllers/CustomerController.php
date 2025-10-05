<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers
     */
    public function index()
    {
        $customers = User::where('role', 'customer')
            ->latest()
            ->paginate(15);

        return view('customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new customer
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created customer
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'required|string|max:20',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:50',
            'password' => 'required|string|min:8|confirmed',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
            'password' => bcrypt($request->password),
            'role' => 'customer',
            'email_verified_at' => now(), // Auto-verify for admin-created customers
        ];

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $filename = 'customer_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/profile-photos'), $filename);
            $data['profile_photo'] = 'images/profile-photos/' . $filename;
        }

        User::create($data);

        return redirect()->route('customers.index')
            ->with('success', 'Customer created successfully.');
    }

    /**
     * Display the specified customer
     */
    public function show(User $customer)
    {
        // Ensure the user is a customer
        if ($customer->role !== 'customer') {
            abort(404);
        }

        return view('customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the customer
     */
    public function edit(User $customer)
    {
        // Ensure the user is a customer
        if ($customer->role !== 'customer') {
            abort(404);
        }

        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer
     */
    public function update(Request $request, User $customer)
    {
        // Ensure the user is a customer
        if ($customer->role !== 'customer') {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($customer->id)
            ],
            'phone_number' => 'required|string|max:20',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:50',
            'password' => 'nullable|string|min:8|confirmed',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
        ];

        // Only update password if provided
        if ($request->filled('password')) {
            $updateData['password'] = bcrypt($request->password);
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

        return redirect()->route('customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified customer
     */
    public function destroy(User $customer)
    {
        // Ensure the user is a customer
        if ($customer->role !== 'customer') {
            abort(404);
        }

        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted successfully.');
    }

    /**
     * Search customers
     */
    public function search(Request $request)
    {
        $query = $request->get('search');
        
        $customers = User::where('role', 'customer')
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%")
                  ->orWhere('phone_number', 'like', "%{$query}%")
                  ->orWhere('address_line_1', 'like', "%{$query}%")
                  ->orWhere('city', 'like', "%{$query}%")
                  ->orWhere('state', 'like', "%{$query}%")
                  ->orWhere('postal_code', 'like', "%{$query}%");
            })
            ->latest()
            ->paginate(15);

        return view('customers.index', compact('customers'));
    }

}
