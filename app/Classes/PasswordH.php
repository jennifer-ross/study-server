<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 3/10/2019
 * Time: 6:03 PM
 */

namespace App\Classes;

class PasswordH
{
    public static function hashPassword($password) {
        if (isset($password) && $password !== null) {
            for ($i = 0; $i < 5; $i++) {
                if ($i % 2) {
                    $alg = 'sha512';
                }else {
                    $alg = 'sha256';
                }
                $password = hash($alg, $password);
            }
            return $password;
        }
        return null;
    }
}