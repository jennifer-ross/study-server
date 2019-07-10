<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class verifyClientId extends Controller
{
    //
    public function ClientIdHandler() {
        return \Illuminate\Support\Facades\Response::json(true);
    }
}
