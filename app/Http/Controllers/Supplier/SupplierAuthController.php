<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Supplier;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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

    public function showRegisterForm()
    {
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
            return redirect()->route('supplier.register')->with('status', 'You were logged out from your previous account to access the supplier register.');
        }

        if (Auth::guard('supplier')->check()) {
            return redirect()->route('supplier.dashboard');
        }
        return view('supplier.register');
    }

    public function register(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:suppliers',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $supplier = new Supplier();
        $supplier->name = $request->name;
        $supplier->email = $request->email;
        $supplier->phone = $request->phone;
        $supplier->password = Hash::make($request->password);
        $supplier->slug = Str::slug($request->name);
        $supplier->status = 0;
        $supplier->save();

        return redirect()->route('supplier.login')->with('status', 'Registration successful! Please login.');
    }

}
