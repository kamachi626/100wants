<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Auth;
use DB;

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

        // 多すぎる
        $list_count = DB::table("lists")->where("user_id", $user->id)->count();
        if($list_count >= 50) {
            return view("error", [
                "user" => $user,
                "error_code" => "Error",
                "error_message" => "これ以上登録できません。いらないリストを削除してください。"
            ]);
        }

        // 作成
        $list_id = DB::table("lists")->insertGetId([
            "user_id" => $user->id,
            "title" => $user->name."のやりたいことリスト",
            "color" => $user->color,
        ]);

        // 項目作成
        for ($i=0; $i < 100; $i++) { 
            DB::table("items")->insert([
                "list_id" => $list_id,
                "index" => $i,
            ]);
        }

        // リダイレクト
        return redirect("/list/".$list_id."/edit");

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
    public function save(Request $req)
    {

        // ID
        $id = $req->input("list_id");

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

        // 削除の場合
        if(!is_null($req->input("delete"))) {

            // 削除
            DB::table("lists")->where("id", $list->id)->delete();
            DB::table("items")->where("list_id", $list->id)->delete();

            // リスト一覧にリダイレクト
            return redirect("/");

        }

        // 保存ではない
        if(is_null($req->input("save"))) {
            return abort(403);
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

}