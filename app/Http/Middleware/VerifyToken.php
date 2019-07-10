<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class VerifyToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $getToken = $request->query->get('user_token');
        if ($getToken !== null) {
            $hasSaved = $request->query->get('hasSaved');
            if ($hasSaved !== null && $hasSaved === true) {
                // auth by db
                $user_token = DB::table('users')->where('user_token','=', $getToken)->get();
                if ($user_token->count() > 0) {
                    return $next($request);
                }
                return Response::json(array('message' => 'Вы не авторизованы','verified' => false), 403);
            }
//            dd(session()->all());
            // auth by session
            $user_token = session()->get('user_token');
            $user_tokenDB = DB::table('users')->where('user_token','=', $getToken)->get();
//            dd($user_token,$user_tokenDB,$getToken);
            if ($user_token === $getToken && $getToken == $user_tokenDB->count() > 0) {
                return $next($request);
            }
            return Response::json(array('message' => 'Вы не авторизованы','verified' => false), 403);
        }else {
            return Response::json(array('message' => 'Вы не авторизованы','verified' => false), 403);
        }
    }
}
