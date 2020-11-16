<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Response;

class LoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return Response
     */
    public function authenticate(Request $request)
    {
        if (empty($request->username)) {
            return response()->json([
                'status' => [
                    'errorCode' => 23,
                    'errorMessage' => 'Field username is required.'
                ],
                'data' => null
            ], 500);
        }

        if (empty($request->password)) {
            return response()->json([
                'status' => [
                    'errorCode' => 23,
                    'errorMessage' => 'Field password is required.'
                ],
                'data' => null
            ], 500);
        }

        $credentials = $request->only('username', 'password');

        $token = Auth::attempt($credentials);

        if ($token) {
            $auth = Auth::user();
            $auth['token'] = $token;
            
            return response()->json([
                'status' => [
                    'errorCode' => 0,
                    'errorMessage' => ''
                ],
                'data' => $auth
            ], 200);
        } else {
            return response()->json([
                'status' => [
                    'errorCode' => 10,
                    'errorMessage' => 'Incorrect Username and Password!'
                ],
                'data' => null
            ], 400);
        }
    }
}
