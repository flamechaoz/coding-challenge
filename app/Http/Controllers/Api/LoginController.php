<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use \Carbon\Carbon;

class LoginController extends Controller
{

    public function fallback()
    {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized',
        ], 401);
    }


    public function userData(Request $request){

        return [
            'user' => $request->user(),
            'info' => $request->user()->info,
        ];

    }

    public function logout(Request $request)
    {
        $request->user()->token()->delete();

        $request->user()->token()->revoke();

        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out',
        ], 200);
    }

    public function login(Request $request){

        $credentials = $request->only('usrnm', 'pwd');
        $validation = Validator::make(
            $credentials,
        [
            'usrnm' => 'required|alpha_num',
            'pwd' => 'required',
        ],
        [
            'usrnm.required' => '":attribute" field is required.',
            'usrnm.alpha_num' => '":attribute" can only contain alphanumeric characters',
            'pwd.required' => '":attribute" field is required.',
        ],
        [
            'usrnm' => 'Username',
            'pwd' => 'Password',
        ]);

        $credentials = ((object) $credentials);

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Verification failed.',
                'errors' => $validation->errors(),
            ], 422);
        }

        if( !Auth::attempt(['username' => $credentials->usrnm, 'password' => $credentials->pwd, 'status' => 1]) ){

            return response()->json([
                'success' => false,
                'message' => 'Login failed, invalid login credentials.', //Login failed
            ], 401);

        }

        $accessToken = Auth::user()->createToken('PS WATCH P_A_T');

        // for keep me logged in feature
        $token = $accessToken->token;

        if ($request->keep_logged_in) {
            // $token->expires_at = Carbon::now()->addWeeks(1); //1  week expiration
        }
        else{
            $token->expires_at = Carbon::now();
        }

        return response()->json([
            'success' => true,
            'message' => 'Login successful!',
            'access_token' => $accessToken->accessToken,
            'token_type' => 'Bearer',
            'grantAccess' => $request->user()->grantAccess,
            'expires_at' => Carbon::parse(
                $accessToken->token->expires_at
            )->toDateTimeString(),
        ]);

    }

}
