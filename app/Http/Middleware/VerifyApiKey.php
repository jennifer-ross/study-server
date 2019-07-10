<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class VerifyApiKey
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
        $cliendId = $request->query->get('client_id');
        if ($cliendId !== null) {

            $apiKey = DB::table('apiKeys')->where('client_id', '=', $cliendId)->limit(1)->get();

            if (!$apiKey->count() > 0) {
                return Response::json(array('message' => 'Invalid client id'), 403);
            }
            return $next($request);
        }
        return Response::json(array('message' => 'Client id is required'), 403);
    }
}
