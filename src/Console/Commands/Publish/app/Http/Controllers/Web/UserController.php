<?php

namespace App\Http\Controllers\Web;

use AlanGiacomin\LaravelBasePack\Controllers\Controller;
use App\Models\User\Contracts\IUserRepository;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('user')]
class UserController extends Controller
{
    public function __construct(
        protected IUserRepository $userRepository
    ) {}

    #[Get('loadUser')]
    public function loadUser(Request $request)
    {
        return $request->user();
    }

    #[Post('login')]
    public function login(Request $request)
    {
        $loggedUser = $request->user();
        if (!Auth::check()) {
            $credentials = [
                'email' => $request->input('email'), //'test@example.com',
                'password' => $request->input('password'), // 'password',
            ];
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                $loggedUser = Auth::user();
            } else {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        }

        return $loggedUser;
    }

    #[Post('logout')]
    public function logout(Request $request)
    {
        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return null;
    }

    #[Get('all')]
    public function all()
    {
        return User::all();
    }

    #[Post('removeRole')]
    public function removeRole(Request $request)
    {
        $user = $this->userRepository->findById($request->input('id'));

        $user->removeRole($request->input('role'));

        return $user;
    }
}
