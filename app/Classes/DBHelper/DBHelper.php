<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 5/6/2019
 * Time: 11:18 AM
 */

namespace App\Classes\DBHelper;

use Illuminate\Support\Facades\DB;
use JsonException;

class DBHelper
{
    public static $except = [
        'id',
        'access',
        'password',
        'user_token'
    ];

    public static $exceptValidFields = [
        'firstName',
        'secondName',
        'fullName'
    ];

    public static function updateFields($table, $fieldsEnc, $user_token, $params = array(), $except = ['id', 'access', 'password', 'user_token'])
    {
//        dd(preg_match('/^[a-zA-Zа-яА-Я]+$/ui', 'Вячеславовна'));
        if (is_null($fieldsEnc)) return \Illuminate\Support\Facades\Response::json(array('message' => 'Заполните все поля'));
        $fields = array();
        try {
            $fields = json_decode($fieldsEnc);
        } catch (JsonException $exception) {
            return \Illuminate\Support\Facades\Response::json(array('message' => 'Неверно заполнены данные json.parse.err'));
        }
        $pattern = '/^[a-zA-Zа-яА-Я]+$/ui';
        if (!(isset($fields) && !is_null($fields) && is_object($fields))) return \Illuminate\Support\Facades\Response::json(array('message' => 'Неверно заполнены данные object.parse.err'));
        $upd = array();
        $hasSkip = false;
        $hasImg = 0;
        foreach ($fields as $key => $value) {
            $hasSkip = false;
            foreach ($except as $k => $v) {
                if ($key === $v) {
                    $hasSkip = true;
                    break;
                }
            }
            foreach (DBHelper::$exceptValidFields as $kk => $vv) {
                if ($key === $vv) {
                    if(!preg_match($pattern, $value)) {
                        return \Illuminate\Support\Facades\Response::json(array('message' => 'Имя, фамилия и отчество не могут содержать числа'));
                    }
                }
            }
            if ($hasSkip) continue;
            if ($key === 'image') {
                $imgId = DB::table($table)->where('id', '=', $params['id'])->get()->all()[0]->image;
                $hasImg = DB::table('images')->where('id','=', $imgId)->update(['image' => $params['image']]);
                if ($hasImg > 0) {
                    $upd += [$key => $imgId];
                    continue;
                }
                continue;
            }
            $upd += [$key => $value];
        }
        $hasUpd = DB::table($table)->where('id', '=', $params['id'])->update($upd);
        if ($hasUpd > 0 || $hasImg > 0) {
            return \Illuminate\Support\Facades\Response::json(true);
        } else {
            return \Illuminate\Support\Facades\Response::json(array('message' => 'Текущие данные совпадают с введенными'));
        }
    }
}