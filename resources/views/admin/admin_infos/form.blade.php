@extends('adminlte::page')

@section('title', isset($adminInfo) ? '編集' : '新規作成'.'管理者情報')

@section('content_header')
    <h1>{{ isset($adminInfo) ? '編集' : '新規作成' }} - 管理者情報</h1>
@stop

@section('content')
    <div class="container-fluid">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ isset($adminInfo) ? route('admin.admin_infos.update', $adminInfo->id) : route('admin.admin_infos.store') }}" method="POST">
            @csrf
            @if(isset($adminInfo))
                @method('PUT')
            @endif

            <div class="form-group">
                <label for="title">タイトル</label>
                <input type="text" class="form-control" name="title" id="title"
                       value="{{ old('title', $adminInfo->title ?? '') }}" required>
            </div>

            <div class="form-group mt-3">
                <label for="content">内容</label>
                <textarea class="form-control" name="content" id="content" rows="5" required>{{ old('content', $adminInfo->content ?? '') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary mt-3">
                {{ isset($adminInfo) ? '更新' : '保存' }}
            </button>
            <a href="{{ route('admin.admin_infos.index') }}" class="btn btn-secondary mt-3">キャンセル</a>
        </form>
    </div>
@endsection
