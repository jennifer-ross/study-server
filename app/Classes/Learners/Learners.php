<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 5/6/2019
 * Time: 12:01 PM
 */

namespace App\Classes\Learners;


use App\Classes\DBHelper\DBHelper;
use Illuminate\Support\Facades\DB;

class Learners extends DBHelper
{
    public static function getUser($user_token) {
        return DB::table('users')->where('user_token','=', $user_token)->get();
    }

    public static function getLearners($limit, $page, $user_token) {
        if (is_null($limit) || is_null($page)) return \Illuminate\Support\Facades\Response::json(array('message' => 'Не возможно получить данные limit_page.err'));
        if ($limit < 0 || $limit > 40) return \Illuminate\Support\Facades\Response::json(array('message' => 'Не возможно получить данные limit.count.err'));
        if ($page < 0 ) return \Illuminate\Support\Facades\Response::json(array('message' => 'Не возможно получить данные page.count.err'));
        $learners = DB::table('learners')->where('owner', '=', self::getUser($user_token)->all()[0]->id)->forPage($page, $limit)->get();
        if ($learners->count() > 0) {
            $learnersArr = $learners->all();
            foreach ($learners->all() as $key => $value) {
                $learnersArr[$key]->image = DB::table('images')->where('id', '=', $value->image)->get()->all()[0]->image;
            }
            return \Illuminate\Support\Facades\Response::json($learnersArr);
        }else {
            return \Illuminate\Support\Facades\Response::json(array('message' => 'Обучающихся не найдено'));
        }
    }

    public static function Search($searchBy, $s, $user_token) {
        if (is_null($searchBy) || is_null($s)) return \Illuminate\Support\Facades\Response::json(array('message' => 'Не удается выполнить поиск'));
        $learners = DB::table('learners')->where([
            ['owner', '=', self::getUser($user_token)->all()[0]->id],
            [$searchBy, 'LIKE','%' . $s . '%']
        ])->get();
        if ($learners->count() > 0) {
        $learnersArr = $learners->all();
        foreach ($learners->all() as $key => $value) {
            $learnersArr[$key]->image = DB::table('images')->where('id', '=', $value->image)->get()->all()[0]->image;
        }
        return \Illuminate\Support\Facades\Response::json($learnersArr);
    }else {
            return \Illuminate\Support\Facades\Response::json(array('message' => 'Обучающихся не найдено'));
        }
    }

    public static function Remove($fields, $user_token) {
        if (is_null($fields)) return \Illuminate\Support\Facades\Response::json(array('message' => 'Не удается выполнить удаление'));
        try {
            $fieldsDec = json_decode($fields);
        } catch (JsonException $exception) {
            return \Illuminate\Support\Facades\Response::json(array('message' => 'Неверно заполнены данные R.json.parse.err'));
        }
        if (!(isset($fieldsDec) && !is_null($fieldsDec) && is_object($fieldsDec))) return \Illuminate\Support\Facades\Response::json(array('message' => 'Неверно заполнены данные R.object.parse.err'));
        foreach ($fieldsDec->id as $key => $value) {
            $learner = DB::table('learners')->where('id', '=', $value)->get()->all()[0];
            $Img = DB::table('images')->delete($learner->image);
            $hasDel = DB::table('learners')->delete($value);
        }
        return \Illuminate\Support\Facades\Response::json(true);
    }

    public static function getMaxCount($user_token) {
        $learners = DB::table('learners')->where('owner', '=', self::getUser($user_token)->all()[0]->id)->get();
        if ($learners->count() > 0) {
            return \Illuminate\Support\Facades\Response::json(array('table' => 'learners', 'count' => $learners->count()));
        }else {
            return \Illuminate\Support\Facades\Response::json(array('message' => 'Обучающихся не найдено'));
        }
    }

    public static function createLearner($firstName, $secondName, $dateBorn, $user_token, $fullName, $image = '') {
        if (is_null($firstName) || is_null($secondName) || is_null($dateBorn) || is_null($fullName)) return \Illuminate\Support\Facades\Response::json(array('message' => 'Заполните все поля'));
        if ($image === '' || $image === null) return \Illuminate\Support\Facades\Response::json(array('message' => 'Не удалось загрузить изображение'));
        $pattern = '/^[a-zA-Zа-яА-Я]+$/ui';
        if(!preg_match($pattern, $firstName) || !preg_match($pattern, $secondName) || !preg_match($pattern, $fullName)) {
            return \Illuminate\Support\Facades\Response::json(array('message' => 'Имя, фамилия и отчество не могут содержать числа'));
        }
        DB::beginTransaction();

        try {

            $imageId = DB::table('images')->insertGetId(['image' => $image]);

            if (!($imageId > 0)) {
                throw new \Exception("");
            }

            $learner = DB::table('learners')->insert([
                'firstName' => $firstName,
                'secondName' => $secondName,
                'fullName' => $fullName,
                'dateBorn' => $dateBorn,
                'image' => $imageId,
                'owner' => self::getUser($user_token)->all()[0]->id
            ]);

            if (!$learner) {
                throw new \Exception("");
            }

            DB::commit();
            // all good
            return \Illuminate\Support\Facades\Response::json(true);
        } catch (\Exception $e) {
            DB::rollback();
            return \Illuminate\Support\Facades\Response::json(array('message' => 'Не удалось добавить обучающегося'));
            // something went wrong
        }

//        if ($learner) {
//            return \Illuminate\Support\Facades\Response::json(true);
//        }else {
//            return \Illuminate\Support\Facades\Response::json(array('message' => 'Не удалось добавить учащегося'));
//        }
    }

}