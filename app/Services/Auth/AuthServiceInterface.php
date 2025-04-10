<?php

namespace App\Services\Auth;

use Illuminate\Http\Request;

interface AuthServiceInterface
{
    /**
     * Attempt to authenticate a user
     *
     * @param array $credentials
     * @return bool
     */
    public function attempt(array $credentials): bool;

    /**
     * Log the user out
     *
     * @return void
     */
    public function logout(): void;
}
