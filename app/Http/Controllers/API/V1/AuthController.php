<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\LoginUserRequest;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiResponse;

    public function login(LoginUserRequest $request)
    {
        // $request->validate($request->all());

        if (! Auth::attempt($request->only('email', 'password'))) {
            return $this->error('Invalid Credentials: Try Again!', 404);
        }

        $user = User::firstWhere('email', $request->email)->first();

        return $this->ok(
            message:'Authenticated',
           data: [
                'token' => $user->createToken('API token for '.$user->email)->plainTextToken,
            ]
        );
    }
}
