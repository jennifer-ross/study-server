<?php

namespace App\Http\Controllers\Api;

use App\Classes\Account\User;
use App\Http\Controllers\Controller;

class AccountController extends Controller
{
    private $request;
    private $params;

    public function __construct()
    {
        $this->request = \Illuminate\Support\Facades\Request::instance();
        $this->params = $this->request->query->all();
    }
    //

    public function AccountChangePasswordHandler() {
        if (isset($this->params['password']) && isset($this->params['password2'])) {
            return User::changeUserPassword($this->params['user_token'], $this->params['password'], $this->params['password2']);
        }else {
            return User::changeUserPassword(null,null, null);
        }
    }

    public function AccountLogOutHandler() {
        if (isset($this->params['saved'])) {
            return User::logOut($this->params['user_token'], $this->params['saved']);
        }else {
            return User::logOut($this->params['user_token']);
        }
    }

    public function AccountUpdateFieldsHandler() {
        if (isset($this->params['fields'])) {
            return User::updateFields('users', $this->params['fields'], $this->params['user_token']);
        }else {
            return User::updateFields(null,null, null);
        }
    }

}
