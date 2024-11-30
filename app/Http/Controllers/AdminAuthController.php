<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminAuthRequest;
use App\Http\Resources\AdminAuthResource;
use App\Mail\ForgotPasswordMail;
use App\Models\Admin;
use App\Models\PersonalAccessToken;
use Illuminate\Support\Facades\Http;
use Auth;
use Carbon\Carbon;
use DB;
use Mail;
use Str;
use URL;


class AdminAuthController extends Controller
{
    public function Login(AdminAuthRequest $request)
    {
        // Validate reCAPTCHA token
        $recaptchaToken = $request->input('recaptchaToken');

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('RECAPTCHA_SECRET_KEY'), // Add your secret key in .env
            'response' => $recaptchaToken,
        ]);

        $recaptchaResult = json_decode($response->body(), true);

        if (!$recaptchaResult['success']) {
            return errorResponse("Invalid reCAPTCHA verification", 422);
        }

        // Authentication logic
        if (!$token = $this->getToken($request)) {
            return errorResponse("Unauthenticated admin", 401);
        }

        $data = [
            'token' => $token,
            'admin' => auth()->user(),
        ];

        return successResponse("Login Successfully", AdminAuthResource::make($data));
    }


    public function Logout()
    {
        // Invalidate the current admin's token
        auth()->logout();

        return successResponse("Logged out successfully", null);
    }

    protected function getToken(AdminAuthRequest $request)
    {

        return Auth::guard('api')->attempt([
            'email' => $request->email,
            'password' => $request->password
        ]);

    }

    public function ForgotPassword(AdminAuthRequest $request)
    {
        try {

            $token = Str::random(40);


            // $domain = env('REACT_APP_URL');
            // dd($domain);
            $url = 'http://localhost:5173/reset-password?token=' . $token;
            $data = [
                "url" => $url,
                "email" => $request->email,
                "title" => "Reset Password Link",
                "body" => "Please click on the below button to reset your password",
            ];

            Mail::to($request->email)->send(new ForgotPasswordMail($data));

            $datetime = Carbon::now()->format('Y-m-d H:i:s');
            PersonalAccessToken::updateOrCreate(
                [
                    'email' => $request->email
                ],
                [
                    'email' => $request->email,
                    'token' => $token,
                    'created_at' => $datetime,
                ]
            );
            return successResponse("Mail sent successfully. Please check your Inbox");


        } catch (\Exception $e) {
            return errorResponse($e->getMessage());
        }
    }

    public function ResetPassword(AdminAuthRequest $request)
    {

        try {
            DB::beginTransaction();
            $token = $request->token;

            // $data = PersonalAccessToken::where('token', $token)->first();
            $data = PersonalAccessToken::getEmail($token);

            Admin::updatePassword($data->email, $request->password);
            PersonalAccessToken::deleteToken($token);
            DB::commit();
            return successResponse("Password updated successfully");

        } catch (\Exception $e) {
            DB::rollBack();
            return errorResponse($e->getMessage());
        }
    }
}
