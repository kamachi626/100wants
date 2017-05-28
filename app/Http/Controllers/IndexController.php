<?php

namespace App\Http\Controllers;

use App\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class IndexController extends Controller
{
    /**
     * 指定ユーザーのプロフィール表示
     *
     * @param  int  $id
     * @return Response
     */
    public function index(Request $req)
    {

        // ユーザ情報
        $user = Auth::user($req);

        // ログイン済み
        if(!is_null($user)) {
			return redirect("/".$user->nickname);
        }

        return view('index', [
        	"user" => $user,
        	"profile_user" => $user,
            "is_top" => true,
        ]);

    }
}