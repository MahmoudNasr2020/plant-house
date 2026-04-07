<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (auth('customer')->check()) {
            return redirect()->route('store.profile');
        }

        return view('store.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (auth('customer')->attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('store.home'));
        }

        return back()->withErrors(['email' => 'البريد أو كلمة المرور غير صحيحة.'])->onlyInput('email');
    }

    public function showRegister(): View|RedirectResponse
    {
        if (auth('customer')->check()) {
            return redirect()->route('store.home');
        }

        return view('store.auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'name'                  => ['required', 'string', 'max:150'],
            'email'                 => ['required', 'email', 'unique:customers,email'],
            'phone'                 => ['nullable', 'string', 'max:30'],
            'password'              => ['required', 'min:8', 'confirmed'],
        ]);

        $customer = Customer::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => $request->password,
        ]);

        auth('customer')->login($customer);
        $request->session()->regenerate();

        return redirect()->route('store.home')->with('success', 'مرحباً! تم إنشاء حسابك بنجاح.');
    }

    public function logout(Request $request): RedirectResponse
    {
        auth('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('store.home');
    }

    public function profile(): View
    {
        $customer = auth('customer')->user();
        $orders   = $customer->orders()->with('items')->latest()->paginate(10);

        return view('store.auth.profile', compact('customer', 'orders'));
    }

    public function orders(): View
    {
        $orders = auth('customer')->user()->orders()->with('items')->latest()->paginate(20);

        return view('store.auth.orders', compact('orders'));
    }

    public function editProfile(): View
    {
        return view('store.auth.edit-profile', [
            'customer' => auth('customer')->user(),
        ]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $customer = auth('customer')->user();

        $request->validate([
            'name'     => ['required', 'string', 'max:150'],
            'email'    => ['required', 'email', Rule::unique('customers', 'email')->ignore($customer->id)],
            'phone'    => ['nullable', 'string', 'max:30'],
            'password' => ['nullable', 'min:8', 'confirmed'],
        ]);

        $data = $request->only(['name', 'email', 'phone']);

        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        $customer->update($data);

        return redirect()->route('store.profile')
            ->with('success', 'تم تحديث بياناتك بنجاح!');
    }
}
