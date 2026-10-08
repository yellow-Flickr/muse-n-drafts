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

    /**
     * Log in.
     *
     * Authenticate with an email address and password to receive an API token.
     *
     * @unauthenticated
     *
     * @group Authentication
     *
     * @bodyParam email string required The user's email address. Example: reader@example.com
     * @bodyParam password string required The user's password (minimum 8 characters). Example: password123
     */
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
                'token' => $user->createToken('API token for '.$user->email, TokenAbilities::getAbilities($user->role), now()->addMonth())->plainTextToken,
            ]
        );
    }

    /**
    * Register.
     *
    * Create a reader account.
     *
     * @unauthenticated
     *
     * @group Authentication
     *
    * @bodyParam name string required The user's name. Example: Avery Reader
    * @bodyParam email string required A unique email address. Example: reader@example.com
    * @bodyParam password string required The user's password (minimum 8 characters). Example: password123
    * @bodyParam password_confirmation string required Must match the password. Example: password123
     */
    public function register(UserRequest $request)
    {
        // $request->validate($request->all())
        $user = User::create([
            ...$request->validated(),
            'role' => 'reader',
        ]);

        return $this->ok(message: 'Registration Successful!', data: new UserResource($user));
    }

    /**
    * Log out.
     *
    * Revoke the current API access token.
     *
     * @authenticated
     *
     * @group Authentication
     *
     * @response 200 {
     *     "message": "User Logged Out!",
    "status": 200
     * }
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->ok('User Logged Out!');
    }

    // forgot-password
    // reset-password
}
