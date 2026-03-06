<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    /**
     * Muestra la página principal del panel de control con estadísticas.
     */
    public function index()
    {
        $user = Auth::user();

        // Calculamos las estadísticas reales del usuario
        $readBooksCount = $user->books()->where('status', 'read')->count();
        $readingBooksCount = $user->books()->where('status', 'reading')->count();
        $listsCount = $user->lists()->count();
        $reviewsCount = $user->reviews()->count();

        return view('pages.dashboard.index', compact(
            'readBooksCount',
            'readingBooksCount',
            'listsCount',
            'reviewsCount'
        ));
    }

    /**
     * Muestra la página de edición de perfil con todos los países disponibles.
     */
    public function profile()
    {
        // Cargamos todos los países disponibles para el selector del formulario
        $countries = \App\Models\Country::orderBy('name')->get();
        return view('pages.dashboard.profile', compact('countries'));
    }

    /**
     * Actualiza el nombre, biografía, país y foto de perfil del usuario.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Validación de los campos del formulario de perfil
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'country_id' => 'nullable|exists:countries,id',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:3072', // máx. 3 MB
        ], [
            'name.required' => 'El nombre de usuario es obligatorio.',
            'name.max' => 'El nombre no puede superar los 255 caracteres.',
            'bio.max' => 'La biografía no puede superar los 1000 caracteres.',
            'country_id.exists' => 'El país seleccionado no es válido.',
            'avatar.image' => 'El archivo debe ser una imagen.',
            'avatar.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif o webp.',
            'avatar.max' => 'La imagen no debe superar los 3 MB.',
        ]);

        // Procesamos la imagen si se ha subido una nueva
        if ($request->hasFile('avatar')) {
            // Guardamos en storage/app/public/userimg con nombre único para evitar colisiones
            $path = $request->file('avatar')->store('userimg', 'public');
            // Guardamos la ruta pública accesible desde el navegador
            $user->avatar = '/storage/' . $path;
        }

        // Actualizamos los datos del perfil en la base de datos
        $user->name = $validated['name'];
        $user->bio = $validated['bio'];
        $user->country_id = $validated['country_id'];

        $user->save();

        return redirect()->route('dashboard.profile')->with('success', 'Perfil actualizado correctamente.');
    }

    /**
     * Muestra la página de configuración y seguridad de la cuenta.
     */
    public function settings()
    {
        return view('pages.dashboard.settings');
    }

    /**
     * Actualiza la visibilidad del perfil del usuario (privacidad).
     */
    public function updatePrivacy(Request $request)
    {
        $request->validate([
            'profile_visibility' => 'required|in:public,followers,friends,private',
        ], [
            'profile_visibility.required' => 'Debes seleccionar una opción de privacidad.',
            'profile_visibility.in' => 'La opción de privacidad seleccionada no es válida.',
        ]);

        // Guardamos la visibilidad elegida por el usuario
        Auth::user()->update([
            'profile_visibility' => $request->profile_visibility,
        ]);

        return redirect()->route('dashboard.settings')
            ->with('privacy_success', 'Preferencias de privacidad guardadas correctamente.');
    }

    /**
     * Actualiza el correo electrónico, teléfono y contraseña del usuario.
     */
    public function updateSettings(Request $request)
    {
        $user = Auth::user();

        // Validación de credenciales
        $validated = $request->validate([
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'telephone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Introduce un correo electrónico válido.',
            'email.unique' => 'Este correo electrónico ya está en uso por otra cuenta.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        // Actualizar email y teléfono
        $user->email = $validated['email'];
        $user->telephone = $validated['telephone'];

        // Solo actualizar la contraseña si se proporcionó una nueva
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('dashboard.settings')->with('success', 'Credenciales actualizadas correctamente.');
    }

    /**
     * Elimina permanentemente la cuenta del usuario.
     */
    public function destroyAccount(Request $request)
    {
        $user = Auth::user();

        // Cerrar sesión e invalidar
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // El modelo elimina en cascada o restringe según constrainsts en BD,
        // pero dado que es una petición directa, usaremos el borrado de Eloquent.
        $user->delete();

        return redirect()->route('home')->with('success', 'Tu cuenta ha sido eliminada permanentemente.');
    }
}
