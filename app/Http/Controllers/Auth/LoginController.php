<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use App\Models\login;
use Illuminate\Http\Request;
use Auth;

// use Illuminate\Http\Request $request;


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
      /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return Response
     */
    public function login(Request $request)
    {   
        $data=login::where('email',$request->email)
                    ->where('password',$request->password)->get()->toArray();
        $this->validateLogin($request);
        
        if($data){
            return back()->with(['message'=>'One Device Already logged In...']);
        }else{

            $new=new login;
            $new->email=$request->email;
            $new->password=$request->password;
            $new->save();
        }
                    

        if(method_exists($this,'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)){
                $this->fireLockoutEvent($request);
                return $this->sendLockoutResponse($request);
        }

        if($this->attemptLogin($request)){
            return $this->sendLoginResponse($request);
        }
        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
        
    }
 
   
    /**
 * Log the user out of the application.
 *
 * @param  \Illuminate\Http\Request $request
 * @return \Illuminate\Http\Response
 */
public function logout(Request $request)
{   
    $user=login::where('email',$request->email);
    $user->delete();
    // $user->email='';
    // $user->password='';
    // $user->save();    

    Auth::logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    return redirect('login')->with(['logout'=>'You Are Logged out By '.$request->email]);
}


}
