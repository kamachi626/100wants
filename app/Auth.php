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
        $remember_token = $session->get("user.id");
        if(is_null($remember_token)) {
        	return null;
        }
        $user = DB::table("users")->where("remember_token", $remember_token)->first();
        return $user;
    }

}
