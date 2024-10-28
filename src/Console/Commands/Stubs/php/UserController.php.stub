<?php

namespace App\Http\Controllers;

use AlanGiacomin\LaravelBasePack\Controllers\Controller;
use App\Models\User\User;
use App\Models\User\UserFactory;
use App\Repositories\IUserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct(
        protected IUserRepository $userRepository
    ) {}

    public function get(Request $request)
    {
        return $request->user();
    }

    public function login(Request $request)
    {
        $loggedUser = $request->user();
        $retValues = [];
        //$retValues[] = ['loggedUser' => $this->loggedUser->attributesToArray()];
        // $retValues[] = ['user' => $loggedUser->attributesToArray() ?? null];
        if (!Auth::check()) {
            $retValues[] = 'not logged';
            $credentials = [
                'email' => 'test@example.com',
                'password' => 'password',
            ];

            if (Auth::attempt($credentials)) {
                $retValues[] = 'attempt ok';
                $request->session()->regenerate();
                $loggedUser = Auth::user();
            } else {
                $retValues[] = 'attempt failed';
            }
        } else {
            $retValues[] = 'already logged in';
        }

        $retValues[] = $loggedUser;

        return $retValues;
    }

    public function logout(Request $request)
    {
        $loggedUser = $request->user();
        $retValues = [];
        $retValues[] = ['user' => $loggedUser != null];
        if (Auth::check()) {
            $retValues[] = 'logged in';
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        $retValues[] = $loggedUser;

        return $retValues;
    }

    public function func(string $action)
    {
        print_log($action);

        // $job = $this->executeCommand(new FaiAzione);

        $user = $this->userRepository->findByEmail('admin@admin.com');
        if ($user === null) {
            $usersArray = $this->userRepository->getAll()->select(['id'])->flatten();
            User::destroy($usersArray);
            $user = UserFactory::new(
                [
                    'email' => 'admin@admin.com',
                ])
                ->unverified()
                ->create();
        }

        $job = $this->userRepository->getAll()->select(['id', 'email']);
        print_log($job);

        if ($action === 'login') {
            $credentials = [
                'email' => 'admin@admin.com',
                'password' => 'password',
            ];

            Auth::attempt($credentials);

            //Auth::logout();
            return redirect()->intended('getUser');
        }

        if ($action === 'logout') {
            Auth::logout();
        }

        $logged = Auth::user();
        if ($logged) {
            print_log($logged->attributesToArray());
        }

        return view('user');
    }
}
