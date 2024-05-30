<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{
    protected $sessionLifetime;

    function __construct()
    {
        $this->sessionLifetime = env('SESSION_LIFETIME', 120);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->only(['email', 'password']), [
            'email' => ['required', 'email'],
            'password' => ['required', 'string']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
                'data'    => null,
                'status'  => false,
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (!Auth::attempt($request->only(['email', 'password']))) {
            return response()->json([
                'message' => 'The credentials is not valid!',
                'errors'  => [],
                'data'    => null,
                'status'  => false,
            ], Response::HTTP_UNAUTHORIZED);
        }
        $user = $request->user();
        $expired = Carbon::now()->addMinutes($this->sessionLifetime);
        $abilities = $user->role->permissions->pluck('permission_name')->toArray();
        $token = $user->createToken($user->id, $abilities, $expired)->plainTextToken;
        return response()->json([
            'message' => 'Login success',
            'data'    => [
                'user'  => new UserResource($user),
                'token' => $token,
            ],
            'status' => true,
            'errors' => []
        ], Response::HTTP_OK);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'message' => 'Logout success',
            'data'    => null,
            'status'  => true,
            'errors'  => []
        ], Response::HTTP_OK);
    }
}
