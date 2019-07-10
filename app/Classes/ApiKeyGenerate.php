<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 4/22/2019
 * Time: 7:43 PM
 */
namespace App\Classes;

use Illuminate\Support\Facades\DB;

class ApiKeyGenerate
{
    static function genKey() {
        $randStr = StringGenerate::RandStr(16);
        $key = hash('sha256', $randStr);
        $key = hash('md5', $key);
        $key = hash('sha256', $key);
        DB::table('apiKeys')->insert([
           ['secretKey' => $randStr, 'client_id' => $key]
        ]);
        return $key;
    }
}