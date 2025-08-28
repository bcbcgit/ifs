<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminInfo;
use Illuminate\Http\Request;

class AdminInfoController extends Controller
{
    // 一覧
    public function index()
    {
        $infos = AdminInfo::latest()->paginate(10);
        return view('admin.admin_infos.index', compact('infos'));
    }

    // 新規作成フォーム
    public function create()
    {
        return view('admin.admin_infos.form');
    }

    // 保存
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        AdminInfo::create($request->only('title', 'content'));

        return redirect()->route('admin.admin_infos.index')
            ->with('success', '情報を作成しました。');
    }

    // 編集フォーム
    public function edit(AdminInfo $adminInfo)
    {
        return view('admin.admin_infos.form', compact('adminInfo'));
    }

    // 更新
    public function update(Request $request, AdminInfo $admin_info)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $admin_info->update($request->only('title', 'content'));

        return redirect()->route('admin.admin_infos.index')
            ->with('success', '情報を更新しました。');
    }

    // 削除
    public function destroy(AdminInfo $admin_info)
    {
        $admin_info->delete();

        return redirect()->route('admin.admin_infos.index')
            ->with('success', '情報を削除しました。');
    }
}
