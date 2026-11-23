<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => $this->newPasswordRules(),
        ]);

        $email = Str::lower($validated['email']);
        $this->validateEmailAvailable($email);
        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $email,
                'password' => Hash::make($validated['password']),
            ]);
        } catch (UniqueConstraintViolationException) {
            $this->duplicateEmail();
        }

        Auth::login($user);
        $request->session()->regenerate();

        return response()->json(['user' => $user], 201);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $validated['email'])->first()
            ?? User::whereRaw('lower(email) = ?', [Str::lower($validated['email'])])->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials do not match our records.'],
            ]);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return response()->json(['user' => $user]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->noContent();
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'current_password' => 'nullable|string',
        ]);

        $email = Str::lower($validated['email']);
        if ($email !== Str::lower($user->email)) {
            $this->verifyPassword($user, $validated['current_password'] ?? '');
            $this->validateEmailAvailable($email, $user->id);
        }

        try {
            $user->update(['name' => $validated['name'], 'email' => $email]);
        } catch (UniqueConstraintViolationException) {
            $this->duplicateEmail();
        }

        return response()->json($user);
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => $this->newPasswordRules(),
        ]);

        $this->verifyPassword($user, $validated['current_password']);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        $request->session()->regenerate();

        return response()->json(['success' => true]);
    }

    private function newPasswordRules(): array
    {
        return ['bail', 'required', 'string', 'min:12', 'confirmed', static function ($attribute, $value, $fail) {
            if (strlen($value) > 72 || str_contains($value, "\0")) {
                $fail('Use a password of at most 72 bytes.');
            }
        }];
    }

    private function validateEmailAvailable(string $email, ?int $except = null): void
    {
        $users = User::whereRaw('lower(email) = ?', [$email]);
        if ($except !== null) {
            $users->whereKeyNot($except);
        }
        if ($users->exists()) {
            $this->duplicateEmail();
        }
    }

    private function duplicateEmail(): never
    {
        throw ValidationException::withMessages(['email' => ['This email address is already registered.']]);
    }

    private function verifyPassword(User $user, string $password): void
    {
        if (! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages(['current_password' => ['Enter your current password.']]);
        }
    }
}
