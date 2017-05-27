<?php

namespace App;

use Illuminate\Http\Request;
use DB;

class Auth
{

    /* ログインユーザ */
    public static function user(Request $req) {
        $session = $req->session();
        $userId = $session->get("user.id");
        $user = DB::table("users")->where("id", $userId)->first();
        return $user;
    }

}
