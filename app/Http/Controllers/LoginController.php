<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Socialite;
use DB;

class LoginController extends Controller
{
    
    /* ログイン処理 */
    public function login(Request $req)
    {
        $req->session()->flush();
    	return Socialite::driver('twitter')->redirect();
    }

    /* コールバック */
    public function callback(Request $req)
    {

        // ユーザ情報取得
        $userData = Socialite::driver('twitter')->user();
        $userId = $userData->getId();

        // DB問い合わせ
        $user = DB::table("users")->where("id", $userId)->first();
        if(empty($user)) {

            // 登録
            DB::table("users")->insert(["id" => $userId]);

        }

        // ユーザ引き当てID

        // 情報の更新
        DB::table("users")->where("id", $userId)->update([
            "name" => $userData->getName(),
            "nickname" => $userData->nickname,
            "color" => $userData->user["profile_link_color"],
        ]);

        // 再取得
        $user = DB::table("users")->where("id", $userId)->first();

        // セッションに格納
        $session = $req->session();
        $session->flush();
        $session->put('user.id', $user->id);
        $session->put('user.name', $user->name);
        $session->put('user.nickname', $user->nickname);
        return redirect("/");

    }

    /* ログアウト */
    public function logout(Request $req) {
        $req->session()->flush();
        return redirect("/");
    }

}