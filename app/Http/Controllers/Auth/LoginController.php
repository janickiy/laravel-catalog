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
     * Shows the login form or sends an authenticated administrator to the panel.
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
     * Checks administrator credentials and logs the user in.
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
     * Returns the user to the home page after successful authentication.
     */
    protected function authenticated(Request $request, mixed $user): RedirectResponse
    {
        return redirect('/');
    }

    /**
     * Ends the admin session and returns the user to the home page.
     */
    public function logout(): RedirectResponse
    {
        Auth::guard('admin')->logout();

        return redirect('/');
    }
}
