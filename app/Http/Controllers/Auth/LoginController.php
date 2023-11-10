<?php

namespace App\Http\Controllers\Auth;

use App\Models\Wallet;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Helpers\UUIDGenerate;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Log;

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

    protected function guard()
    {
        return Auth::guard();
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        $user->ip = $request->ip();
        $user->user_agent = $request->server('HTTP_USER_AGENT');
        $user->login_at = date('Y-m-d H:i:s');
        $user->update();
        Wallet::firstOrCreate(
            ['user_id' => $user->id],
            [
                'account_number' => UUIDGenerate::accountNumber(),
                'amount' => 0,
            ],
        );
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson() ? new JsonResponse([], 204) : redirect('/');
    }

    public function username()
    {
        return 'phone';
    }

    protected function credentials(Request $request)
    {
        // Log::info($request->phone);

        return $request->only($this->username(), 'password');
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        $type = Str::contains($request->phone, '@') ? 'email' : 'phone';
        Log::info($type);
        return $this->guard()->attempt([$type => $request->phone, 'password' => $request->password], $request->boolean('remember'));
    }
}

// Auth::attempt(['phone' => $request->phone, 'password' => $request->password]))
