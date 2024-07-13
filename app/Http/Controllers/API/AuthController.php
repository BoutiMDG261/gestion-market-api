<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = JWTAuth::fromUser($user);

            return response()->json([
                'token' => $token,
                'user' => $user,
            ]);
        }

        return response()->json(['error' => 'Non autorisé'], 401);
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password'))
        ]);

        $token = Auth::login($user);

        return response()->json(['token' => $token]);
    }

    public function logout()
    {
        $token = JWTAuth::getToken();

        if ($token) {
            try {
                JWTAuth::invalidate($token);
            } catch (JWTException $e) {
                return response()->json(['error' => 'requête echoué'], 500);
            }
        }

        if (Auth::user()) {
            Auth::logout();
            return response()->json(['message' => 'Deconnexion réussie']);
        }
    }

    public function refreshToken()
    {
        try {
            $token = JWTAuth::parseToken()->refresh();
        } catch (TokenExpiredException $e) {
            return response()->json(['error' => 'Token expiré'], 401);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Token invalide'], 401);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Erreur lors de la création du token'], 500);
        }

        return response()->json(['token' => $token]);
    }
}
