<?php

namespace App\Actions\Auth;

use Illuminate\Contracts\Auth\Authenticatable;

class LogoutUserAction
{
    /**
     * Logout the authenticated user by revoking their current token.
     *
     * @param  Authenticatable  $user
     * @return void
     */
    public function execute(Authenticatable $user): void
    {
        $user->currentAccessToken()->delete();
    }
}

