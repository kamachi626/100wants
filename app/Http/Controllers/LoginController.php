<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Socialite;
use DB;
use URL;

class LoginController extends Controller
{
    
    /* ログイン処理 */
    public function login(Request $req)
    {
        $req->session()->flush();
        $req->session()->put("pre_login_url", URL::previous());
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

        // 情報の更新
        DB::table("users")->where("id", $userId)->update([
            "name" => $userData->getName(),
            "nickname" => $userData->nickname,
            "color" => $userData->user["profile_link_color"],
            "image" => $userData->avatar_original,
            "remember_token" => md5(uniqid(rand(), true)),
        ]);

        // 再取得
        $user = DB::table("users")->where("id", $userId)->first();

        // セッションに格納
        $session = $req->session();
        $session->put('user.id', $user->remember_token);
        
        // リダイレクト
        $pre_url = $session->get("pre_login_url");
        if(!isset($pre_url)) {
            $pre_url = "/";
        }
        return redirect($pre_url);

    }

    /* ログアウト */
    public function logout(Request $req) {
        $req->session()->flush();
        return redirect(URL::previous());
    }

}