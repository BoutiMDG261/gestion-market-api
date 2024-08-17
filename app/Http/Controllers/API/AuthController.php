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
        $credentials = $request->only('username', 'password');

        // Vérification des identifiants
        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => "Le nom d'utilisateur et/ou le mot de passe saisis sont incorrects."], 403);
        }

        // Génération du token JWT
        $user = Auth::user();
        $token = JWTAuth::fromUser($user);

        // Retourne le token et les informations de l'utilisateur
        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function register(RegisterRequest $request)
    {
        // Création du nouvel utilisateur
        $user = User::create([
            'fullname' => $request->input('fullname'),
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password'))
        ]);

        // Génération du token JWT
        $token = Auth::login($user);

        // Retourne le token et un message de confirmation
        return response()->json(['token' => $token, 'message' => "Utilisateur crée avec succès."]);
    }

    public function logout()
    {
        // Récupération du token JWT
        $token = JWTAuth::getToken();

        // Invalidation du token
        if ($token) {
            try {
                JWTAuth::invalidate($token);
            } catch (JWTException $e) {
                return response()->json(['error' => 'Requête échouée'], 500);
            }
        }

        // Déconnexion de l'utilisateur
        if (Auth::user()) {
            Auth::logout();
            return response()->json(['message' => 'Déconnexion réussie']);
        }
    }

    public function refreshToken()
    {
        // Tentative de rafraîchissement du token
        try {
            $token = JWTAuth::parseToken()->refresh();
        } catch (TokenExpiredException $e) {
            return response()->json(['error' => 'Token expiré'], 401);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Token invalide'], 401);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Erreur lors de la création du token'], 500);
        }

        // Retourne le nouveau token
        return response()->json(['token' => $token]);
    }
}
