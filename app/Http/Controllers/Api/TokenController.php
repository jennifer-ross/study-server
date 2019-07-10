<?php

namespace App\Http\Controllers\Api;

use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class TokenController extends Controller
{
    private $request;

    public function __construct()
    {
        $this->request = \Illuminate\Support\Facades\Request::instance();
    }

    //
    public function TokenHandler() {
        $params = $this->request->query->all();
        if (isset($params['action']) && $params['action'] === 'get') {
            return $this->getToken();
        }
        return response('Unauthorized action.',403);
    }

    public function getToken($null = false) {
        $token = csrf_token();
        if ($null) $token = null;
        return \Illuminate\Support\Facades\Response::json(array('_token' => $token));
    }
}
