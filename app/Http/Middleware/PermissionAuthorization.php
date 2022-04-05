<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PermissionAuthorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $permission)
    {

        $user = auth('api')->user();

        if( ! $user->isAbleTo($permission)) {
            return response()->json([
                'message' => 'Anda tidak memiliki akses untuk halaman ini',
                'success' => false
            ], 403);
        }

        return $next($request);
    }
}
