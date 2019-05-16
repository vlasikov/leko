<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//для версии 5.2 и ранее:
//use DB;
use Illuminate\Support\Facades\DB;

class AjaxController extends Controller
{
    public function dbInsertUser(Request $request) // добавление записи в БД

    {
        $mes = false;
        if ($this->checkNick($request->nick) &&
            $this->checkName($request->name) &&
            $this->checkLastname($request->lastname) &&
            $this->checkEmail($request->email) &&
            $this->checkPassword($request->password)) {
            $mes = DB::table('users')->insert([
                'nick' => $request->nick,
                'name' => $request->name,
                'lastname' => $request->lastname,
                'email' => $request->email,
                'password' => $request->password,
            ]);
        }
        $data = array("DB::table('users')->insert " => $mes);
        return response()->json($data);
    }

    public function dbCheckCollumnValue(Request $request) // возвращаем колличество совпадений в БД со строкой

    {
        $user = DB::table('users')->where($request->collumn, '=', $request->value)->count();
        return $user;
    }

    private function checkNick($nick)
    {
        if ((strlen($nick) < 1) || (preg_match('/[\W]/', $nick))) { // проверяем, что введены латинские буквы и цифры
        } else {
            $symbol1 = substr($nick, 0, 1);
            if (preg_match('/[^A-Za-z]/', $symbol1)) {
            } else {
                if (!(DB::table('users')->where('nick', '=', $nick)->count())) { // проверка на повтор в БД
                    return true;
                }
            }
        }
        return false;
    }

    private function checkName($name)
    {
        if (preg_match('/[^а-я]+/msiu', $name)) {
            return false;
        } else {
            return true;
        }
    }

    private function checkLastname($name)
    {
        if (preg_match('/[^а-я]+/msiu', $name)) {
            return false;
        } else {
            return true;
        }
    }

    private function checkEmail($email)
    {
        if (preg_match('/^[\w-\.]+@[\w-]+\.[a-z]{2,4}$/i', $email)) {
            if (!(DB::table('users')->where('email', '=', $email)->count())) // проверка на повтор в БД
            {
                return true;
            }

        }
        return false;
    }

    private function checkPassword($pass)
    {
        if (strlen($pass) < 5) {
            return false;
        } else {
            return true;
        }
    }
}
