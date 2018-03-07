<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class AuthController extends Controller
{    
    protected $attributes = [
        'email' => 'bail|required|email',
        'password' => 'required',
    ];

    protected $options = [
        'email.required' => 'Precisa digitar email',
        'email.email' => 'Email inválido',
        'password.required' => 'Precisa digitar senha',
    ];

    public function form()
    {
        return view('auth');
    }

    public function authenticate(Request $request)
    {
        $request->validate( $this->attributes, $this->options );   

        if( Auth::attempt( $request->only( 'email', 'password' ) ) ) {

            return redirect( '/' );

        } else {

            return back()->withErrors([
                'auth' => 'Usuário ou Senha invalido(s)'
            ]);
            
        }
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('login');
    }
}
