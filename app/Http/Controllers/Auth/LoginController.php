<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin', ['except' => ['logout']]);
    }

    /**
     * Показывает форму авторизации или отправляет авторизованного администратора в панель.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        $user = Auth::guard('admin')->user();

        if ($user) {
            return redirect()->intended(route('cp.dashbaord.index'));
        }

        return view('auth.login');
    }

    /**
     * Проверяет учетные данные администратора и выполняет вход.
     */
    public function login(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'login' => 'required',
            'password' => 'required',
        ]);

        if (Auth::guard('admin')->attempt(['login' => $request->login, 'password' => $request->password], $request->remember)) {
            return redirect()->intended(route('cp.dashbaord.index'));
        }

        return redirect()
            ->back()
            ->withErrors(['message' => trans('auth.failed')])
            ->withInput($request->only('login', 'remember'));
    }

    /**
     * Возвращает пользователя на главную страницу после успешной авторизации.
     */
    protected function authenticated(Request $request, mixed $user): RedirectResponse
    {
        return redirect('/');
    }

    /**
     * Завершает админскую сессию и возвращает пользователя на главную страницу.
     */
    public function logout(): RedirectResponse
    {
        Auth::guard('admin')->logout();

        return redirect('/');
    }
}
