<?php

namespace App\Http\Middleware;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;
use App\Models\User;
use Closure;

class AuthFirebase
{
    
    public function handle($request, Closure $next, $guard = null)
    {
        $auth = app('firebase.auth');
        $token = $request->bearerToken();
        if(!$token) {
            // Unauthorized response if token not there
            return response()->json([
                'error' => 'Token not provided.'
            ], 401);
        }
        try {
            $verifiedIdToken = $auth->verifyIdToken($token);
        } catch (FailedToVerifyToken $e) {
            echo 'The token is invalid: '.$e->getMessage();
        }
        $uid = $verifiedIdToken->claims()->get('sub');
        $user = $auth->getUser($uid);
        $request->auth = $user;
        $request->user = User::where('email', $user->email)->first();
        return $next($request);
    }
}
