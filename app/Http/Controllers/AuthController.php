<?php
/**
 * @author Anum Saeed <anumsaeed286@gmail.com>
 *
 * This controller manages the user authentication
 *
 * We have not used any default scaffolding provided
 * by Laravel. This is a custom built controller.
 */
namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * This controller manages the user authentication
 *
 * We have not used any default scaffolding provided
 * by Laravel. This is a custom built controller.
 *
 */
class AuthController extends Controller
{
    # This will tell the login function to redirect the user type to their homepage
    private $homepage = [
        # Middleware    =>     Route name
        'admin'    => 'dashboard.admin',
        'employee' => 'dashboard.employee',
    ];
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @url /login
     */
    public function login()
    {
        # Additional data sent to view
        $data = [
            # Additional classes sent to view
            'class' => [
                'body' => 'login-page'
            ],
            'base_url' => env('APP_URL', 'http://127.0.0.1:8000'),
            'title' => 'Login'
        ];
        return view('auth.login', $data);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @url /register
     */
    public function register_step_1()
    {
        # Additional data sent to view
        $data = [
            # Additional classes sent to view
            'class' => [
                'body' => 'register-page'
            ],
            'base_url' => env('APP_URL', 'http://127.0.0.1:8000'),
            'title' => 'Registration'
        ];
        return view('auth.register', $data);
    }

    public function register_step_2(Request $request)
    {
        $request->validate([
            'company'   => 'required|min:2',
            'email'     => 'required|email',
            'password'  => 'required|confirmed|min:4',
        ]);

        # Prepare additional data to send to the view
        $data = [
            # Additional classes sent to view
            'class' => [
                'body' => 'register-page'
            ],
            'base_url' => env('APP_URL', 'http://127.0.0.1:8000'),
            'title'    => 'Registration',
            'request'  => $request->all()
        ];

        # Check the registrar
        if ($request->registrar == 'employee')
            return view('auth.employee', $data);

        return view('auth.employee', $data);
    }

    /**
     * @param Request $request
     * @url /login (POST)
     */
    public function attemptLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        # Checks if the user exists
        $user = User::where('email', $request->email)->first();

        # Checks if the user does not exist
        if (!$user) return back()->with('error', 'User not found!');

        # Checks if the password is correct or not
        if (!Hash::check($request->password, $user->password)) return back()->with('error', 'Invalid credentials!');


        # Generate a new session for the user
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->input('remember', false))) {
            $request->session()->regenerate();

            return redirect()->intended(route($this->homepage[$user->user_role]));
        }

        return redirect(route($this->homepage[$user->user_role]));
    }

    public function logout()
    {
        Auth::logout();
        return redirect(route('login'));
    }
    public function password()
    {
        $data = [
            'title' => 'Forgot Password',
            'base_url' => env('APP_URL', 'http://127.0.0.1:8000'),
        ];
        # If everything went well
        return view('auth.forgot-password',$data);
    }

   public function forgotpassword(Request $request)
   {
       $user = Auth::user()->id;
       $request->validate([
            'password'             => 'required|confirmed|min:4'
        ]);

       $request->input('password')   &&  $user->password      = Hash::make($request->input('password'));
       $user->save();

        return redirect(route('login'));
    }


}
