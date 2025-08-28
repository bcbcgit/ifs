<?php
namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function create()
    {
        return view('contact.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'nullable|email|max:255',
            'message' => 'required|string',
        ]);

        // DB 保存
        $contact = Contact::create($validated);

        // 管理者にメール送信
        Mail::send('emails.contact', ['contact' => $contact], function($message) {
            $message->to(config('mail.admin_email'))
                ->subject('新しいお問い合わせが届きました');
        });

        return redirect()->route('contact.create')->with('success','お問い合わせを送信しました。');
    }
}
