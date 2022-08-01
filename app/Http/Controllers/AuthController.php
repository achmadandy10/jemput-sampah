<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                "regex:/^['\p{L}\s-]+$/u"
            ],
            'email' => [
                'required',
                'string',
                'email',
                Rule::unique(User::class)
            ],
            'phone_number' => [
                'required',
                'string',
                'numeric',
                Rule::unique(User::class)
            ],
            'password' => [
                'required',
            ]
        ]);

        if ($validate->fails()) {
            return ResponseFormatter::error(401, 'Validation errors', $validate->errors());
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'role' => 2,
                'password' => Hash::make($request->password)
            ]);

            $token = $user->createToken($user->email . '_token', ['server:user'])->plainTextToken;
            
            $data = [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user
            ];

            return ResponseFormatter::success('Registered Successfully', $data);
        } catch (QueryException $error) {
            return ResponseFormatter::error(500, 'Query Error', $error);
        }
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return ResponseFormatter::error(401, 'Authentication failed', []);
        }

        if ($user->role == 1) {
            $token = $user->createToken($user->email . '_token', ['server:admin'])->plainTextToken;
        } else if ($user->role == 2) {
            $token = $user->createToken($user->email . '_token', ['server:user'])->plainTextToken;
        } else {
            return ResponseFormatter::error(500, 'Someting error', 'Access Denied');
        }

        $data = [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ];

        return ResponseFormatter::success('Login success', $data);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();

        return ResponseFormatter::success('Token revoked');
    }

    public function profile()
    {
        $profile = User::where('id', auth()->user()->id)
            ->first();

        return ResponseFormatter::success('Profile', $profile);
    }
}
