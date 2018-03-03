<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;

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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function form()
    {
        return view('auth');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'bail|required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Precisa digitar email',
            'email.email' => 'Email inválido',
            'password.required' => 'Precisa digitar senha',
        ]);   

        if( Auth::attempt( $request->only( 'email', 'password' ) ) )

            return redirect( $this->redirectTo );
    
        else

            return back()->withErrors([
                'auth' => 'Usuário ou Senha invalido(s)'
            ]);
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('login');
    }

}
