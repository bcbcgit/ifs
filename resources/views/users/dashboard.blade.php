<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザーダッシュボード</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.3/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
<div class="container mx-auto mt-10">
    <h1 class="text-3xl font-bold mb-5">ユーザーダッシュボード</h1>
    <p>ようこそ、ユーザー {{ Auth::user()->name }} さん！</p>
    <p>ここではユーザー向けの操作が可能です。</p>

    <div class="mt-5">
        <a href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
            ログアウト
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </div>
</div>
</body>
</html>
