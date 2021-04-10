<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;

class LoginController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Login
     *
     * @param \App\Http\Requests\Auth\LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(LoginRequest $request): JsonResponse
    {
        $creds = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (!Auth::attempt($creds)) {
            return response()->json(
                ['error' => 'The provided credentials are incorrect.'],
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $user = $request->user();

        $user->token = $user->createToken("{$user->username}-access-token")->plainTextToken;

        return response()->json($user, JsonResponse::HTTP_OK);
    }
}
