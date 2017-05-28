<?php

namespace App\Http\Controllers;

use App\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class ProfileController extends Controller
{
    /**
     * 指定ユーザーのプロフィール表示
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Request $req, $id)
    {

        // ユーザ情報
        $user = Auth::user($req);

        // プロフィール表示ユーザ
        $profile_user = DB::table("users")->where("nickname", $id)->orderBy("updated_at", "desc")->first();
        if(is_null($profile_user)) {
            return abort(404);
        }

    	// リスト
    	$lists = DB::table("lists")->where("user_id", $profile_user->id)->get();

        return view('profile', [
        	"user" => $user,
        	"profile_user" => $profile_user,
        	"lists" => $lists,
            "color" => $profile_user->color,
            "subtitle" => $profile_user->name,
        ]);

    }
}