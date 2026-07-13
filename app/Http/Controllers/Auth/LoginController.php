<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Redirect;
use Auth;
use Session;
use App\Models\User;
use Log;
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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function credentials(Request $request)
    {
        return [
            'email' => $request->email,
            'password' => $request->password,
            'status' => 1, // Only allow users with status = 1 to log in
        ];
    }

    protected function authenticated(Request $request, $user)
    {
        if ($user->status !== 1) {
            Auth::logout();
            return redirect()->back()->with('error', 'Inavalid credentials.');
        }
    
        if ($user['status'] == 1) {
            if ($user['role'] == 1 || $user['role'] == 2) {
                return redirect()->route('dashboard');
            }
            if ($user['role'] == 3) {

                $teacher = User::with(['classes' => function($query) {
                    $query->select('class_title');
                }])->find($user->id);

                if ($teacher) {
                    $classNames = $teacher->classes->pluck('class_title')->join(', ');
                    Session::put('classes', $classNames);
                }
                return redirect()->route('home');
            }

            Auth::logout();
            return redirect('login');
        }else{
            Auth::logout();
            return redirect('login');
        }
    }
}
