@extends('layouts.base')

@section('contents','contact')
@section('page_title','Contact')

@section('content')
    <div id="@yield('contents')" class="contents p-4">
        <h1>お問い合わせフォーム</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('contact.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>お名前</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                @error('name')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label>メールアドレス（返信が必要な際は入力してください）</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                @error('email')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label>内容</label>
                <textarea name="message" rows="5" class="form-control">{{ old('message') }}</textarea>
                @error('message')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="btn btn-primary">送信</button>
        </form>
    </div>
@endsection
