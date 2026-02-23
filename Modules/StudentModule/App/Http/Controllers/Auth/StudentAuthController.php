<?php

namespace Modules\StudentModule\App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudentAuthController extends Controller
{
    public function index()
    {
        return Auth::guard('student')->check()
            ? redirect()->route('student.dashboard')
            : view('studentmodule::auth.login');
    }

    public function login(Request $request)
    {
        // Validate input
        $credentials = $request->validate([
            'phone'    => 'required',
            'password' => 'required'
        ]);

        $remember = $request->boolean('rememberme');

        // Try login using phone + password
        if (Auth::guard('student')->attempt([
            'phone'    => $credentials['phone'],
            'password' => $credentials['password']
        ], $remember)) {

            $request->session()->regenerate();
            return redirect()->intended(route('student.dashboard'));
        }

        return back()->withErrors(['error' => 'رقم الهاتف أو كلمة المرور غير صحيحة.']);
    }

    public function changePassword()
    {
        $student = Auth::guard('student')->user();
        return view('studentmodule::student.change_password', compact('student'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password'     => 'required|confirmed|min:4',
        ]);

        $student = Auth::guard('student')->user();

        if (!Hash::check($request->old_password, $student->password)) {
            return back()->withErrors(['old_password' => 'كلمة المرور القديمة غير صحيحة.']);
        }

        $student->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()
            ->route('student.changePassword')
            ->with('success', 'تم تغيير كلمة المرور بنجاح.');
    }

    public function logout(Request $request)
    {
        Auth::guard('student')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('student.login');
    }
}
