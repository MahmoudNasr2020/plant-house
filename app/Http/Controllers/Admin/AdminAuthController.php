<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (auth()->check()) {
            return redirect()->route('admin.home');
        }

        return view('admin.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (auth()->attempt($credentials, $request->boolean('remember'))) {
            if (!auth()->user()->is_active) {
                auth()->logout();
                return back()->withErrors(['email' => 'حسابك موقف. تواصل مع المدير العام.']);
            }

            $request->session()->regenerate();

            return redirect()->intended(route('admin.home'));
        }

        return back()->withErrors(['email' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة.'])
            ->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    public function editProfile(): View
    {
        return view('admin.profile', [
            'admin' => auth()->user(),
        ]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $request->validate([
            'name'             => ['required', 'string', 'max:150'],
            'email'            => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'current_password' => ['nullable', 'required_with:password'],
            'password'         => ['nullable', 'min:8', 'confirmed'],
        ]);

        if ($request->filled('password')) {
            if (! \Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة'])->withInput();
            }
        }

        $data = $request->only(['name', 'email']);

        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        $user->update($data);

        return redirect()->route('admin.profile')
            ->with('success', 'تم تحديث بياناتك بنجاح!');
    }
}
