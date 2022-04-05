<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;

class TokenAuthorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            //Access token from the request
            $token = JWTAuth::parseToken();
            //Try authenticating user
            $token->authenticate();
        } catch (TokenExpiredException $e) {
            //Thrown if token has expired
            return unauthorized('Token anda telah kadaluarsa. Silahkan login kembali.');
        } catch (TokenInvalidException $e) {
            //Thrown if token invalid
            return unauthorized('Token anda salah. Silahkan login kembali');
        } catch (JWTException $e) {
            //Thrown if token was not found in the request.
            return unauthorized('Silahkan tambahkan Bearer pada request anda');
        }

        return $next($request);
    }

    private function unauthorized($message = null)
    {
        return response()->json([
            'message' => $message ? $message : 'Anda tidak memiliki akses untuk halaman ini',
            'success' => false
        ], 401);
    }
}