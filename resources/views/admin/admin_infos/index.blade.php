@extends('adminlte::page')

@section('title', '更新情報一覧')

@section('content_header')
    <h1>更新情報一覧</h1>
@stop

@section('content')
    <a href="{{ route('admin.admin_infos.create') }}" class="btn btn-primary mb-3">新規作成</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>タイトル</th>
            <th>内容</th>
            <th>作成日時</th>
            <th>更新</th>
            <th>削除</th>
        </tr>
        </thead>
        <tbody>
        @foreach($infos as $info)
            <tr>
                <td>{{ $info->id }}</td>
                <td>{{ $info->title }}</td>
                <td>{{ Str::limit($info->content, 50) }}</td>
                <td>{{ $info->created_at->format('Y-m-d H:i') }}</td>
                <td><a href="{{ route('admin.admin_infos.edit', $info->id) }}">編集</a></td>
                <td>
                    <form action="{{ route('admin.admin_infos.destroy', $info->id) }}" method="POST" onsubmit="return confirm('削除しますか？');">
                        @csrf
                        @method('DELETE')
                        <button type="submit">削除</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $infos->links() }}
@stop
