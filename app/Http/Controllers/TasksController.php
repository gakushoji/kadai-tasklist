<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Task;
use App\User;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
        if (\Auth::check()) {
            $user = \Auth::user();
            return redirect()->route('users.show', ['id'=>$user->id]);
        }else {
            return view('welcome');
              }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $task = new Task;

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
    public function store(Request $request)
    {
        $this->validate($request, [
            'status' => 'required|max:191',
            'content' => 'required|max:191',
            ]);
            
        $request->user()->tasks()->create([
            'status' => $request->status,
            'content' => $request->content,
            ]);

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::find($id);
        
        return view('tasks.show', [
            'task' => $task,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $task = \App\Task::find($id);

        return view('tasks.edit', [
            'task' => $task,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'status' => 'required|max:191',
            'content' => 'required|max:191',
            ]);
        
        $task = \App\Task::find($id);
        if (\Auth::id() === $task->user_id) {
        $task->status = $request->status;
        $task->content = $request->content;
        $task->save();
        
        return redirect()->route('users.show',['id'=>$task->user_id]);
        
        }else {
        return redirect('/');
        }
    }

    public function destroy($id)
    {
        $task = \App\Task::find($id);
        
        if (\Auth::id() === $task->user_id) {
            $task->delete();
        }

        return redirect()->back();
    }
}
