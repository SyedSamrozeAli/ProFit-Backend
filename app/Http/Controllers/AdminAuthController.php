<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminAuthRequest;
use App\Http\Resources\AdminAuthResource;
use Auth;

class AdminAuthController extends Controller
{
    public function Login(AdminAuthRequest $request)
    {

        if (!$token = $this->getToken($request)) {
            return errorResponse("Unauthenticated User", 401);
        }

        $data = [
            'token' => $token,
            'user' => auth()->user(),
        ];

        return successResponse("Login Successfully", AdminAuthResource::make($data));


    }

    public function Logout()
    {
        // Invalidate the current user's token
        auth()->logout();

        return successResponse("Logged out successfully", null);
    }

    protected function getToken(AdminAuthRequest $request)
    {
        return Auth::guard('api')->attempt($request->only('email', 'password'));
    }
}
