<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{

    public function index()
    {
        return view('admins.index', [
            'users' => User::latest()->get(),
        ]);
    }

    public function store(Request $request)
    {

        // Validate the incoming request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|string|in:blog-admin,vendor-admin',
            'password' => 'required|string|min:8|confirmed',
        ]);
    
        // Create a new user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']), // Ensure password is hashed
        ]);
    
        // Optionally, redirect or return a response with success message
        return redirect()->route('admins.index')->with('success', 'User created successfully!');
    }

    public function edit(User $user)
    {
        return view('admins.edit', [
            'user' => $user,
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email',
            'role' => 'required|string',
        ]);
 
        $user->update($validated);
 
        return redirect(route('admins.index'));
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect(route('admins.index'));
    }
}
