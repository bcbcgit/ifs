@extends('adminlte::page') {{-- AdminLTE のレイアウトを使用 --}}

@section('title', '管理者ダッシュボード')

@section('content_header')
    <h1>管理者ダッシュボード</h1>
@stop

@section('content')
    <p>ようこそ、管理者 {{ Auth::user()->name }} さん！</p>
    <p>ここでは管理者向けの操作が可能です。</p>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin-custom.css">
@stop

@section('js')
    <script>
        console.log('Admin dashboard loaded');
    </script>
@stop
