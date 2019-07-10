<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 5/6/2019
 * Time: 11:19 AM
 */

namespace App\Classes\Account;


use App\Classes\DBHelper\DBHelper;
use App\Classes\PasswordH;
use App\Classes\StringGenerate;
use Illuminate\Support\Facades\DB;

class User extends DBHelper
{
    public static function createUser($login, $password, $password2) {
        if (is_null($login) || is_null($password) || is_null($password2) ) return \Illuminate\Support\Facades\Response::json(array('message' => 'Заполните все поля'));
        if ($password !== $password2 ) return \Illuminate\Support\Facades\Response::json(array('message' => 'Пароли не совпадают'));

        $user = DB::table('users')->where('login', '=', $login)->limit(1)->get();
        if ($user->count() > 0) {
            return \Illuminate\Support\Facades\Response::json(array('message' => 'Пользователь с таким логином уже существует'));
        }else {
            $hasUser = DB::table('users')->insert([
                [
                    'login' => $login,
                    'password' => PasswordH::hashPassword($password),
                    'access' => 1
                ]
            ]);
            if ($hasUser) {
                return \Illuminate\Support\Facades\Response::json(true);
            }else {
                return \Illuminate\Support\Facades\Response::json(array('message' => 'Произошла ошибка, повторите попытку'));
            }
        }
    }

    public static function loginUser($login, $password, $user_token=null) {
        if ($user_token !== null) {
            $token = $user_token;
            $user = DB::table('users')->where([
                ['user_token', '=', $user_token]
            ])->limit(1)->get();
            if ($user->count() > 0) {
                session(['user_token' => $token]);
                return \Illuminate\Support\Facades\Response::json(array('user_token' => $token));
            }else {
                return \Illuminate\Support\Facades\Response::json(array('message' => 'Не удалось автризоваться'));
            }
        }
        if (is_null($login) ) return \Illuminate\Support\Facades\Response::json(array('message' => 'Заполните поле Логин'));
        if (is_null($password) || $password === "") return \Illuminate\Support\Facades\Response::json(array('message' => 'Заполните поле Пароль'));
        $token = StringGenerate::RandStr(64);
        $user = DB::table('users')->where([
            ['login', '=', $login],
            ['password', '=', PasswordH::hashPassword($password)]
        ])->limit(1)->get();
        if ($user->count() > 0) {
            $hasUpd = DB::table('users')->where('id', '=', $user->all()[0]->id)->update(['user_token' => $token]);
            if ($hasUpd > 0) {
                session(['user_token' => $token]);
                return \Illuminate\Support\Facades\Response::json(array('user_token' => $token));
            } else {
                return \Illuminate\Support\Facades\Response::json(array('message' => 'Не удалось авторизоваться'));
            }
        }
        return \Illuminate\Support\Facades\Response::json(array('message' => 'Ни одного пользователя с таким логином и паролем не найдено'));
    }

    public static function logOut($user_token, $saved=false) {
        if ($saved === 'false' || $saved === 'False' || $saved === false) {
            $hasUpd = DB::table('users')->where('user_token','=', $user_token)->limit(1)->update([
                'user_token' => null
            ]);
        }else {
            $hasUpd = 1;
        }
        session()->forget('user_token');
        if ($hasUpd > 0) {
            return \Illuminate\Support\Facades\Response::json('true');
        }else {
            return \Illuminate\Support\Facades\Response::json(array('message' => 'Вы не авторизованы'));
        }
    }

    public static function changeUserPassword($user_token, $password, $password2) {
        if (is_null($user_token) ) return \Illuminate\Support\Facades\Response::json(array('message' => 'Вы не авторизованы'));
        if (is_null($password) || is_null($password2) ) return \Illuminate\Support\Facades\Response::json(array('message' => 'Заполните все поля'));
        if ($password !== $password2 ) return \Illuminate\Support\Facades\Response::json(array('message' => 'Пароли не совпадают'));

        $user = DB::table('users')->where('user_token','=', $user_token)->limit(1)->get();
        if ($user->count() > 0) {
            $hasUpd = DB::table('users')->where('id','=', $user->all()[0]->id)->update([
                'password' => PasswordH::hashPassword($password)
            ]);
            if ($hasUpd) {
                return \Illuminate\Support\Facades\Response::json('true');
            }else {
                return \Illuminate\Support\Facades\Response::json(array('message' => 'Новый пароль совпадает с текущим'));
            }
        }

        return \Illuminate\Support\Facades\Response::json(array('message' => 'Unauthorized action.'), 200);
    }
}