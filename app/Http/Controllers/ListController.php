<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Auth;
use DB;
use stdClass;

class ListController extends Controller
{
    
    /* 指定したリストの表示 */
    public function show(Request $req, $id)
    {

        // リストが見つからない
        $list = DB::table("lists")->where("id", $id)->first();
        if(is_null($list) || $list->id != $id) {
            return abort(404);
        }

        // リストのユーザー
        $list_user = DB::table("users")->where("id", $list->user_id)->first();
        if(is_null($list_user)) {
            return abort(404);
        }

        // 項目
        $items = DB::table("items")->where("list_id", $list->id)->whereNotNull("title")->get();

        // ユーザ情報
        $user = Auth::user($req);

        // 変更可否
        $is_editable = !is_null($user) && $user->id == $list_user->id;

        // 表示
        return view("list", [
            "user" => $user,
            "list_user" => $list_user,
            "list" => $list,
            "items" => $items,
            "color" => $list->color,
            "is_editable" => $is_editable,
        ]);

    }

    /* リスト作成 */
    public function create(Request $req)
    {

        // ユーザ情報
        $user = Auth::user($req);
        if(is_null($user)) {
            return abort(403);
        }

        // 作成
        $list = new stdClass();
        $list->id = -1;
        $list->user_id = $user->id;
        $list->title = $user->name."のやりたいことリスト";
        $list->color = $user->color;

        // 項目構築
        $items = array();
        for ($i=0; $i < 100; $i++) { 
            $item = new stdClass();
            $item->title = null;
            $items[$i] = $item;
        }

        // 表示
        return view("list_edit", [
            "user" => $user,
            "list" => $list,
            "items" => $items,
            "color" => $list->color,
        ]);

    }

    /* 指定したリストの編集 */
    public function edit(Request $req, $id)
    {

        // ユーザ情報
        $user = Auth::user($req);
        if(is_null($user)) {
            return abort(403);
        }

        // リストが見つからない
        $list = DB::table("lists")->where("id", $id)->first();
        if(is_null($list)) {
            return abort(403);
        }

        // 別ユーザ
        if($user->id != $list->user_id) {
            return abort(403);
        }

        // 項目
        $items = DB::table("items")->where("list_id", $list->id)->get();

        // 表示
        return view("list_edit", [
            "user" => $user,
            "list" => $list,
            "items" => $items,
            "color" => $list->color,
        ]);

    }

    /* 指定したリストの保存 */
    public function update(Request $req)
    {

        // ID
        $id = $req->input("list_id");

        // ユーザ情報
        $user = Auth::user($req);
        if(is_null($user)) {
            return abort(403);
        }

        // 作成の場合
        if(!is_null($req->input("create"))) {

            // 多すぎる
            if(!$this->_isCreatable($user->id)) {
                return view("error", [
                    "user" => $user,
                    "error_code" => "Error",
                    "error_message" => "これ以上登録できません。いらないリストを削除してください。"
                ]);
            }

            // 作成
            $id = DB::table("lists")->insertGetId([
                "user_id" => $user->id,
                "title" => "title",
                "color" => $user->color,
            ]);

            // 項目作成
            for ($i=0; $i < 100; $i++) { 
                DB::table("items")->insert([
                    "list_id" => $id,
                    "index" => $i,
                ]);
            }

        }

        // リストが見つからない
        $list = DB::table("lists")->where("id", $id)->first();
        if(is_null($list)) {
            return abort(404);
        }

        // 別ユーザ
        if($user->id != $list->user_id) {
            return abort(403);
        }

        // 削除の場合
        if(!is_null($req->input("delete"))) {

            // 削除
            DB::table("lists")->where("id", $list->id)->delete();
            DB::table("items")->where("list_id", $list->id)->delete();

            // リスト一覧にリダイレクト
            return redirect("/");

        }

        // 件数
        $item_count = 0;
        $item_titles = $req->input("item_titles");
        foreach ($item_titles as $item_title) {
            if(isset($item_title)) {
                $item_count++;
            }
        }

        // リストの色
        $color = $user->color;
        $list_color = $req->input("list_color");
        if(isset($list_color)) {
            $color = preg_replace("/#/", "", $list_color);
        }

        // リスト情報保存
        DB::table("lists")->where("id", $id)->update([
            "title" => $req->input("title"),
            "count" => $item_count,
            "color" => $color,
        ]);

        // 項目保存
        for ($i=0; $i < 100; $i++) { 
            DB::table("items")->where("list_id", $id)->where("index", $i)->update([
                "title" => isset($item_titles[$i]) ? $item_titles[$i] : null,
            ]);
        }

        // リダイレクト
        return redirect("/list/".$list->id);

    }

    /* 作成可能か */
    public function _isCreatable($id) {
        $list_count = DB::table("lists")->where("user_id", $id)->count();
        return $list_count < 50;
    }

}