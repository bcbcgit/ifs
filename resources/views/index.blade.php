@extends('layouts.base')

@section('contents','index')
@section('page_title','TOP')

@section('content')
    <div id="@yield('contents')" class="contents p-4">
        <div id="top_image">
            <div class="inner">
                <div class="title fw-bold">管理人からのお知らせ</div>
                <p>いつもお世話になっています。管理人ノルニルです。</p>
                <div class="text_wrap">
                    @foreach($infos as $info)
                        <div class="mb-4 mt-8">
                            <h6 class="fw-bold">【{{ $info->updated_at->format('Y.m.d') }}】{{ $info->title }}</h6>
                            <p>{!! nl2br(e($info->content)) !!}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
