<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Supplier;
use Illuminate\Support\Facades\Hash;

class SupplierAuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
            return redirect()->route('supplier.login')->with('status', 'You were logged out from your previous account to access the supplier login.');
        }

        if (Auth::guard('supplier')->check()) {
            return redirect()->route('supplier.dashboard');
        }
        return view('supplier.login');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $email = $request->input('email');
        $password = $request->input('password');
        
        $supplier = Supplier::where('email', $email)->first();

        if ($supplier) {
            if (Hash::check($password, $supplier->password)) {
                Auth::guard('supplier')->login($supplier);
                return redirect()->route('supplier.dashboard');
            } else {
                return view('supplier.login')
                    ->with('message', 'Wrong password. Please try again.')
                    ->with('email', $email);
            }
        } else {
            return view('supplier.login')
                ->with('message', 'Credential error. User not found.')
                ->with('email', $email);
        }
    }

}
