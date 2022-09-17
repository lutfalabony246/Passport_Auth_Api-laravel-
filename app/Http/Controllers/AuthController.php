<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
     // for registering user for authentication
     public function register(Request $request)
     {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => [
                'required',
                'min:8',
            ],
        ]);
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            $token = $user->createToken('telcomtoken')->accessToken;
            $response = [
                'user' => $user,
                'token' => $token
            ];
            return response($response, 201);
            // return 'User data created successfully!';
        } catch (\Exception $e) {
            return ('Insert into database error -' . $e->getLine() . $e->getMessage());
        }
     }

      // for registering user for authentication
    public function login(Request $request)
    {
        $fieldRequired = $request->validate([
            'email' => 'required',
            'password' => 'required',


        ]);
        try {
            // check email for login user
            $user = User::where('email', $request->email)->first();
            if (!$user || !Hash::check($fieldRequired['password'], $user->password)) {
               return response([
                    'message' => 'You have entered incorrect email or password'
                ], 401);
            } else {
                $token = $user->createToken('telcomtoken')->accessToken;
                $response = [
                    'user' => $user,
                    'token' => $token
                ];
                return response($response, 201);
            }
            // return 'User data created successfully!';
        } catch (\Exception $e) {
            return ('Insert into database error -' . $e->getLine() . $e->getMessage());
        }
      
    }
}
