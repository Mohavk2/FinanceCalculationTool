<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AuthenticationController extends Controller
{
    /**
     * Register new user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        return response()->json($user, 201);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        $credentials = request(['email', 'password']);
        if(!auth()->attempt($credentials)){
            return response()->json([
                'message' => 'The given data was invalid',
                'errors' => [
                    'password' =>[
                        'invalid credentials'
                    ]
                ]
            ], 422);
        }

        $user = User::where('email', $request->email)->first();
        $userAbilityNames = $user->abilities->pluck('name')->filter(function ($value){
            return $value != null;
        })->all();

        $authToken = $user->createToken('auth-token', $userAbilityNames)->plainTextToken;

        return response()->json([
           'access_token' => $authToken,
        ]);
    }
}
