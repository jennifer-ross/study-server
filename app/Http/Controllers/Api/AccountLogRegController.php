<?php

namespace App\Http\Controllers\Api;

use App\Classes\Account\User;
use App\Classes\PasswordH;
use App\Http\Controllers\Controller;

class AccountLogRegController extends Controller
{
    private $request;
    private $params;

    public function __construct()
    {
        $this->request = \Illuminate\Support\Facades\Request::instance();
        $this->params = $this->request->query->all();
    }

    //
    public function AccountLoginHandler() {
        if (isset($this->params['login'])) {
            if (isset($this->params['login']) && isset($this->params['password'])) return  User::loginUser($this->params['login'], $this->params['password']);
            else return  User::loginUser(null, null, $this->params['user_token']);
        }else {
            if (isset($this->params['user_token'])) {
                return User::loginUser(null, null, $this->params['user_token']);
            }else {
                return User::loginUser(null, null);
            }
        }
    }

    public function AccountRegisterHandler() {
        if (isset($this->params['login']) && isset($this->params['password']) && isset($this->params['password2'])) {
            return User::createUser($this->params['login'], $this->params['password'], $this->params['password2']);
        }else {
            return User::createUser(null, null, null);
        }
    }
}
