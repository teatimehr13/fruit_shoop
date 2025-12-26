<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): Response
    {
        // return Inertia::render('Auth/ForgotPassword', [
        //     'status' => session('status'),
        // ]);

        return Inertia::render('Auth/ForgotPassword', [
            'status' => session('status'),
            'reset_link' => session('reset_link'),
            'demo_mode' => config('app.demo_reset_link'),
        ]);
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        // $status = Password::sendResetLink(
        //     $request->only('email')
        // );

        // if ($status == Password::RESET_LINK_SENT) {
        //     return back()->with('status', __($status));
        // }

        // throw ValidationException::withMessages([
        //     'email' => [trans($status)],
        // ]);

        $resetUrl = null;

        // 用 callback 取 token，但不送出 email
        $status = Password::sendResetLink(
            $request->only('email'),
            function ($user, string $token) use (&$resetUrl) {
                $resetUrl = url(route('password.reset', [
                    'token' => $token,
                    'email' => $user->getEmailForPasswordReset(),
                ], false));
                // Demo 模式：不寄信
            }
        );

        // 防止帳號枚舉：永遠回同一句
        $message = '如果此信箱存在，我們已產生重設密碼連結。';

        // 只有在 Demo 模式 + 真的有產生 token 時，才把連結帶回去顯示
        if (config('app.demo_reset_link') && $status === Password::RESET_LINK_SENT && $resetUrl) {
            return back()
                ->with('status', $message)
                ->with('reset_link', $resetUrl);
        }


        if ($status == Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }
}
