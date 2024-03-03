<?php

namespace App\Http\Controllers;


use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function login(array $credentials){
        if (!Auth::guard('web')->attempt($credentials)) {
            throw ValidationException::withMessages(
                [
                    "email" => "incorrect_credentials"
                ]
            );
        }

        $user = User::query()->findByEmail($credentials['email']);

        $expiredAt = Carbon::now()->addMinutes(config("sanctum.expiration"));

        return $user->createToken(name: 'api', expiresAt: $expiredAt);
    }
}
