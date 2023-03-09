<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {

        $input = $request->all();
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required|min:5',
        ]);

        if ($credentials) {
            if (auth()->attempt(array('username' => $input['username'], 'password' => $input['password']))) {
                $cekLevel = auth()->user()->level;

                switch ($cekLevel) {
                    case 'admin':
                            return redirect()->route('home');
                        break;
                        
                        case 'petugas':
                            return redirect()->route('home');
                        break;
                    
                    default:
                        return redirect()->route('home');
                        break;
                }
            }
        }
    }
}
