<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use AuthenticatesUsers;

    private const redirect = 'index';

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request) {
         $this->validate($request, [
            'name' => 'required|min:5|max:26',
            'password' => 'required|min:5|max:26'
        ]);

        $formData = $request->only(['name', 'password']);

        if (Auth::attempt($formData)) {
            return redirect(route(self::redirect));
        }

        return redirect(route('auth.login'))->withErrors([
            'password' => __('auth.failed')
        ]);
    }

    /**
     * @param Request $request
     */
    public function register(Request $request)
    {
        $valid = $this->validate($request, [
            'name' => 'required|min:5|max:26',
            'password' => 'required|min:5|max:26'
        ]);

        if (User::where('name', $valid['name'])->exists()) {
            return redirect(route('auth.register'))->withErrors([
                "name" => "Пользователь с таким именем уже есть"
            ]);
        }

        $valid['password'] = Hash::make($valid['password']);

        $user = User::create($valid);

        if ($user) {
            Auth::loginUsingId($user->id);
            return redirect(route(self::redirect));
        }

        return redirect(route('auth.register'))->withErrors([
            'name' => __('Ошибка регистрации')
        ]);
    }
}
