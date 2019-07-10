<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ImageController extends Controller
{
    private $request;
    private $params;

    public function __construct()
    {
        $this->request = \Illuminate\Support\Facades\Request::instance();
        $this->params = $this->request->query->all();
    }
    //

    public function ImageCreateHandler() {
        $this->request->getContent();
    }
}
