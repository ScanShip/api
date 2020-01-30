<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\ApiController;

use App\Http\Requests\LoginRequest;

use App\Http\Requests\RegisterRequest;
use App\Models\Entity;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\AuthManager;

class AuthController extends ApiController
{
    private $auth;
    private $user;

    /**
     * Inject Auth and User class to avoid static call
     */
    public function __construct(AuthManager $auth, User $user)
    {
        $this->auth = $auth;
        $this->user = $user;
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$this->auth->attempt($credentials)) {
            return $this->respondWithError('Bad credentials');
        }

        $user = $this->auth->user();

        $token = $user->createToken('Api');
        $tokenExpire = Carbon::parse($token->token->expires_at)->toDateTimeString();

        return $this->respondSuccess([
            'user' => [
                'email'     => $user->email,
                'firstname' => $user->firstname,
                'lastname'  => $user->lastname,
                // 'role'      => $user->roles()->get()->pluck('role'),
            ],
            'token'        => $token->accessToken,
            'token_type'   => 'Bearer',
            'token_expire' => $tokenExpire,
        ], [
            'x-auth-token'        => $token->accessToken,
            'x-auth-token-expire' => $tokenExpire,
        ]);
    }

    /**
     * Register API
     *
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterRequest $request)
    {
        $postArray = $request->only('firstname','lastname','email','password');
        $postArray['password'] = bcrypt($postArray['password']);
        $user = new User($postArray);
        $user->save();
        $success['user'] = $user->only('email', 'firstname', 'lastname');

        return $this->respondCreated($success);
    }
}
