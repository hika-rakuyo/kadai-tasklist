@extends('layouts.app')

@section('content')

{{-- ここにページ毎のコンテンツを書く --}}
    <h1>Detail</h1>

    <table class="table table-bordered">
        <tr>
            <th>To Do</th>
            <td>{{ $task->content }}</td>
        </tr>
        <tr>
            <th>status</th>
            <td>{{ $task->status }}</td>
        </tr>
        <tr>
            <th>last modified at</th>
            <td>{{ $task->updated_at }}</td>
        </tr>
        <tr>
            <th>added at</th>
            <td>{{ $task->created_at }}</td>
        </tr>
    </table>
    
    {{-- タスク編集ページへのリンク --}}
    {!! link_to_route('tasks.edit', 'Modify', ['task' => $task->id], ['class' => 'btn btn-light']) !!}
    
    {{-- タスク削除フォーム --}}
    {!! Form::model($task, ['route' => ['tasks.destroy', $task->id], 'method' => 'delete']) !!}
        {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}

@endsection