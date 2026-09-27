<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\LoginUserRequest;
use App\Http\Requests\API\V1\UserRequest;
use App\Http\Resources\API\V1\UserResource;
use App\Models\User;
use App\Permissions\V1\TokenAbilities;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiResponse;

    public function login(LoginUserRequest $request)
    {
        // $request->validate($request->);

        if (! Auth::attempt($request->only('email', 'password'))) {
            return $this->error('Invalid Credentials: Try Again!', 404);
        }

        
        $user = User::firstWhere('email', $request->email);
        // dd($user);

        return $this->ok(
            message: 'Authenticated',
            data: [
                'token' => $user->createToken('API token for '.$user->email,TokenAbilities::getAbilities($user->role), now()->addMonth())->plainTextToken,
            ]
        );
    }

    public function register(UserRequest $request)
    {
        // $request->validate($request->all())
        $user = User::create([
            ...$request->validated(),
            'role' => 'author',
        ]);

        return $this->ok(message: 'Registration Successful', data: new UserResource($user));
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->ok('User Logged Out!');
    }

    // forgot-password
    // reset-password
}
