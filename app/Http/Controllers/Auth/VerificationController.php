<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Verified;

class VerificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Страница с уведомлением о необходимости подтвердить email
    public function show(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return view('auth.verify-email');
    }

    // Подтверждение email
    public function verify(Request $request, $id, $hash)
    {
        if (! hash_equals((string) $hash, sha1($request->user()->getEmailForVerification()))) {
            abort(403);
        }

        if ($request->user()->hasVerifiedEmail()) {
            return redirect('/profile')->with('status', 'Ваш email уже подтверждён.');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));

            // Флэш-сообщение об успешном подтверждении почты
            session()->flash('success', 'Ваш email был успешно подтверждён!');
        }


        return redirect('/profile')->with('status', 'Email подтверждён!');
    }

    // Повторная отправка письма с подтверждением
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect('/profile');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'На вашу почту отправлено новое письмо для подтверждения.');
    }
}