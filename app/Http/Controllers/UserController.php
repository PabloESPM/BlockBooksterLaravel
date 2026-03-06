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
     * Muestra el formulario de registro.
     */
    public function showRegisterForm()
    {
        $countries = Country::all();
        return view('pages.users.register', compact('countries'));
    }

    /**
     * Maneja el registro de usuario.
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
            'type' => 'user', // Tipo por defecto
            'avatar' => null, // Valor por defecto o manejar carga si se añade más adelante
        ]);

        Auth::login($user);

        return redirect()->route('home');
    }

    /**
     * Muestra el formulario de inicio de sesión.
     */
    public function showLoginForm()
    {
        return view('pages.users.login');
    }

    /**
     * Maneja el inicio de sesión del usuario.
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirige al panel de administración si es admin o trabajador, de lo contrario a inicio
            if (Auth::user()->type === 'admin' || Auth::user()->type === 'worker') {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    /**
     * Cierra la sesión del usuario.
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
        // Más seguidos: usuarios con más seguidores
        $mostFollowed = User::withCount('followers')
            ->orderBy('followers_count', 'desc')
            ->take(5)
            ->get();

        // Mejores curadores: usuarios con más listas creadas
        $topCurators = User::withCount('lists')
            ->orderBy('lists_count', 'desc')
            ->take(5)
            ->get();

        // Más activos: usuarios con más reseñas
        $mostActive = User::withCount('reviews')
            ->orderBy('reviews_count', 'desc')
            ->take(5)
            ->get();

        return view('pages.users.community', compact('mostFollowed', 'topCurators', 'mostActive'));
    }
}
