<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;    // 追加

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    // getでtasks/にアクセスされた場合の「一覧表示処理」
    public function index()
    {
        $data = [];
        if(\Auth::check()){
        
            // 認証したユーザを取得
            $user = \Auth::user();
    
            // ユーザのタスク一覧を作成日時の昇順で取得
            $tasks = $user->tasks()->paginate(10);
            
            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
        }
        
        return view('welcome', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // getでtasks/createにアクセスされた場合の「新規登録画面表示処理」
    public function create()
    {
        $task = new Task;

        // task作成ビューを表示
        return view('tasks.create', [
            'task' => $task,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // postでtasks/にアクセスされた場合の「新規登録処理」
    public function store(Request $request)
    {
        // validation
        $request->validate([
            'status' => 'required|max:10', 
            'content' => 'required|max:255',    
        ]);
        
        
        // 認証済みユーザ（閲覧者）のTaskとして作成（リクエストされた値をもとに作成）
        $request->user()->tasks()->create([
            'content' => $request->content,
            'status' => $request->status
        ]);

        // トップページへリダイレクトさせる
        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // getでtasks/idにアクセスされた場合の「取得表示処理」
    public function show($id)
    {
        // idの値でtaskを検索して取得
        $task = Task::findOrFail($id);

        // 認証済みユーザ（閲覧者）がそのTaskの所有者である場合は、投稿を表示
        if (\Auth::id() === $task->user_id) {
            return view('tasks.show', [
            'task' => $task,
            ]);
        }
        
        else return redirect('/');

    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // getでtasks/id/editにアクセスされた場合の「更新画面表示処理」
    public function edit($id)
    {
        // idの値でtaskを検索して取得
        $task = Task::findOrFail($id);

        // 認証済みユーザ（閲覧者）がそのTaskの所有者である場合は、投稿を表示
        if (\Auth::id() === $task->user_id) {
            return view('tasks.edit', [
            'task' => $task,
            ]);
        }
        
        else return redirect('/');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // putまたはpatchでtasks/idにアクセスされた場合の「更新処理」
    public function update(Request $request, $id)
    {
        // validation
        $request->validate([
            'status' => 'required|max:10',
            'content' => 'required|max:255',
        ]);
        
        // idの値でtaskを検索して取得
        $task = Task::findOrFail($id);
        // 認証済みユーザ(閲覧者)そのTaskの所有者である場合は、taskを更新
        if (\Auth::id() === $task->user_id) {
            $task->status = $request->status;
            $task->content = $request->content;
            $task->save();
        }

        // トップページへリダイレクトさせる
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // deleteでtasks/idにアクセスされた場合の「削除処理」
    public function destroy($id)
    {
        // idの値でtaskを検索して取得
        $task = Task::findOrFail($id);
        // 認証済みユーザ（閲覧者）がそのTaskの所有者である場合は、投稿を削除
        if (\Auth::id() === $task->user_id) {
            $task->delete();
        }

        // トップページへリダイレクトさせる
        return redirect('/');
    }
}
