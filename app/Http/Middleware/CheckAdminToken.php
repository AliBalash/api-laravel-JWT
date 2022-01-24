<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class CheckAdminToken
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next,$guard)
    {
        $user = null;
        auth()->shouldUse($guard);
        $token = $request->header('auth-token');
//        $request->headers->set('auth-token',(string) $token, true);
        $request->headers->set('Authorization', 'bearer'.$token, true);
        try {
            $user = JWTAuth::parseToken()->authenticate();
//            dd($user);

        } catch (\Exception $exception) {
            if ($exception instanceof TokenInvalidException) {
                return response()->json([
                    'success' => false,
                    'msg' => 'INVALID_TOKEN',
                ], 400);
            } elseif ($exception instanceof TokenExpiredException) {
                return response()->json([
                    'success' => false,
                    'msg' => 'EXPIRED_TOKEN',
                ], 403);
            } else {
                return response()->json([
                    'success' => false,
                    'msg' => 'TOKEN_NOTFOUND',
                ], 404);
            }
        } catch (\Throwable $throwable) {

            if ($throwable instanceof TokenInvalidException) {
                return response()->json([
                    'success' => false,
                    'msg' => 'throwable :INVALID_TOKEN',
                ], 400);
            } elseif ($throwable instanceof TokenExpiredException) {
                return response()->json([
                    'success' => false,
                    'msg' => 'throwable :EXPIRED_TOKEN',
                ], 403);
            } else {
                return response()->json([
                    'success' => false,
                    'msg' => 'throwable :TOKEN_NOTFOUND',
                ], 404);
            }
        }

        if (!$user ){
            return response()->json(['success' => false, 'msg' => trans('Unauthenticated')]);
        }
        return $next($request);
    }
}
