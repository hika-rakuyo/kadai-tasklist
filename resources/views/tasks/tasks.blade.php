@if (count($tasks) > 0)
    <table class="table table-striped">
            <thead>
                <tr>
                    <th>To Do</th>
                    <th>status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tasks as $task)
                <tr>
                    {{-- task詳細ページへのリンク --}}
                    <td>{!! link_to_route('tasks.show', $task->content, ['task' => $task->id]) !!}</td>
                    <td>{!! link_to_route('tasks.show', $task->status, ['task' => $task->id]) !!}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    {{-- ページネーションのリンク --}}
    {{ $tasks->links() }}
@else
    <h2>You have nothing to do.</h2>
@endif