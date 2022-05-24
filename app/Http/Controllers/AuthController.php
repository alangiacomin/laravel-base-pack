<?php

namespace App\Http\Controllers;

use Alangiacomin\LaravelBasePack\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use stdClass;

class AuthController extends Controller
{
    /**
     * Methods requiring acl permission
     *
     * @var array|string[]
     */
    public array $restrictedMethods = [
    ];

    /**
     * Methods returning a json response
     *
     * @var array|string[]
     */
    public array $jsonResponse = [
        'login',
        'logout',
        'profile',
    ];

    /**
     * @throws Exception
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials))
        {
            $request->session()->regenerate();
            return User::withPermissions(Auth::user());
        }
        throw new Exception("Error login", 1);
    }

    /**
     * @throws Exception
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
    }

    public function profile()
    {
        return User::withPermissions(Auth::user()) ?? new stdClass();
    }
}
