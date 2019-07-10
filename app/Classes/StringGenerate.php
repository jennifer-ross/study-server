<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 4/22/2019
 * Time: 7:45 PM
 */

namespace App\Classes;

class StringGenerate
{
    static function RandStr($length) {
        if ($length === null || $length < 0 || !isset($length)) {
            $length = 8;
        }
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}