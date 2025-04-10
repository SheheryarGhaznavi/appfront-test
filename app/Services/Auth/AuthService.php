<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Auth;

class AuthService implements AuthServiceInterface
{
    /**
     * Attempt to authenticate a user
     *
     * @param array $credentials
     * @return bool
     */
    public function attempt(array $credentials): bool
    {
        return Auth::attempt($credentials);
    }

    /**
     * Log the user out
     *
     * @return void
     */
    public function logout(): void
    {
        Auth::logout();
    }
}
