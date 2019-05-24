<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ErrorsCodesHelper;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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
    protected $redirectTo = null;

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
     * Validate the user login request.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|email',
            'password' => 'required|string',
        ], [
            $this->username() . '.required' => ErrorsCodesHelper::EMAIL_REQUIRED,
            $this->username() . '.email' => ErrorsCodesHelper::WRONG_EMAIL_OR_PASSWORD,
            'password.required' => ErrorsCodesHelper::PASSWORD_REQUIRED,
            'password.string' => ErrorsCodesHelper::WRONG_EMAIL_OR_PASSWORD,
        ]);
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendLoginResponse(Request $request)
    {
        $this->clearLoginAttempts($request);
        $token = $this->guard()->user()->createToken(env('APP_NAME'))->accessToken;

        return ResponseHelper::successResponse(['token' => $token]);
    }

    /**
     * Redirect the user after determining they are locked out.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     */
    protected function sendLockoutResponse(Request $request)
    {
        return ResponseHelper::errorResponse(ErrorsCodesHelper::TOO_MUCH_LOGIN_ATTEMPTS);
    }

    /**
     * Get the failed login response instance.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        return ResponseHelper::errorResponse(ErrorsCodesHelper::WRONG_EMAIL_OR_PASSWORD);
    }

    /**
     * Handle logout
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return ResponseHelper::successResponse();
    }
}
