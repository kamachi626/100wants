<?php

namespace App;

use Illuminate\Http\Request;
use DB;

class Auth
{

    /* ログインユーザ */
    public static function user(Request $req) {
        $session = $req->session();
        if(is_null($session)) {
        	return null;
        }
        $userId = $session->get("user.id");
        if(is_null($userId)) {
        	return null;
        }
        $user = DB::table("users")->where("id", $userId)->first();
        return $user;
    }

}
