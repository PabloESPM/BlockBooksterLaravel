<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegisterForm()
    {
        $countries = Country::all();
        return view('pages.users.register', compact('countries'));
    }

    /**
     * Handle user registration.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:Male,Female,Other',
            'country_id' => 'required|exists:countries,id',
            'telephone' => 'required|string|max:20',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'date_of_birth' => $validatedData['date_of_birth'],
            'gender' => $validatedData['gender'],
            'country_id' => $validatedData['country_id'],
            'telephone' => $validatedData['telephone'],
            'type' => 'user', // Default type
            'avatar' => null, // Default or handle upload if added later
        ]);

        Auth::login($user);

        return redirect()->route('home');
    }

    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('pages.users.login');
    }

    /**
     * Handle user login.
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect to admin dashboard if admin or worker, else home
            if (Auth::user()->type === 'admin' || Auth::user()->type === 'worker') {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
    public function community()
    {
        // Most Followed: Users with most followers
        $mostFollowed = User::withCount('followers')
            ->orderBy('followers_count', 'desc')
            ->take(5)
            ->get();

        // Top Curators: Users with most lists created
        $topCurators = User::withCount('lists')
            ->orderBy('lists_count', 'desc')
            ->take(5)
            ->get();

        // Most Active: Users with most reviews
        $mostActive = User::withCount('reviews')
            ->orderBy('reviews_count', 'desc')
            ->take(5)
            ->get();

        return view('pages.users.community', compact('mostFollowed', 'topCurators', 'mostActive'));
    }
}