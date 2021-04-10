<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\SignUpRequest;
use App\Services\AuthService;

class SignUpController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AuthService $service)
    {
        $this->middleware('guest');
        $this->service = $service;
    }

    /**
     * Login
     *
     * @param \App\Http\Requests\Auth\LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(SignUpRequest $request): JsonResponse
    {
        $user = $this->service->createUser($request->all());
        $user->token = $user->createToken("{$user->username}-access-token")->plainTextToken;

        return response()->json($user, JsonResponse::HTTP_OK);
    }
}
