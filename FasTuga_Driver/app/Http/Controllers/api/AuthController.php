<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Middleware\Authenticate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\AuthenticateUserRequest;
use Illuminate\Support\Facades\Hash;

const PASSPORT_SERVER_URL = "http://fastuga_driver.test";
const CLIENT_ID = 2;
const CLIENT_SECRET = 'arrozdoce2420';

class AuthController extends Controller
{
    private function passportAuthenticationData($username, $password)
    {
        return [
            'grant_type' => 'password',
            'client_id' => CLIENT_ID,
            'client_secret' => CLIENT_SECRET,
            'username' => $username,
            'password' => $password,
            'scope' => ''
        ];
    }

    public function loginUser(AuthenticateUserRequest $request) {
        $validated = $request->validated();
        return $this->login($request);
    }

    public function login(Request $request)
    {
        try {
            request()->request->add($this->passportAuthenticationData($request->username, $request->password));
            $request = Request::create(PASSPORT_SERVER_URL . '/oauth/token', 'POST');
            $response = Route::dispatch($request);
            $errorCode = $response->getStatusCode();
            $auth_server_response = json_decode((string) $response->content(), true);
            return response()->json($auth_server_response, $errorCode);
        } catch (\Exception $e) {
            return response()->json('Authentication has failed!', 401);
        }
    }

    public function logout(Request $request)
    {
        $accessToken = $request->user()->token();
        $token = $request->user()->tokens->find($accessToken);
        $token->revoke();
        $token->delete();
        return response(['msg' => 'Token revoked'], 200);
    }

    public function register(CreateUserRequest $request)
    {
        $validated = $request->validated();

        User::create([
            'name' => $validated['name'],
            'email' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'license_plate' => $validated['license_plate'],
            'phone_number' => $validated['phone_number'],
            'balance' => 0,
        ]);

        return $this->login($request);
    }
}
