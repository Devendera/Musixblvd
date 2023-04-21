<?php

namespace App\Http\Controllers;

use App\Craft;
use App\Gender;
use App\Genre;
use App\Mail\SendMail;
use App\Platform;
use App\Type;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\Exceptions\JWTException;
use Artisan;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        //Validation
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'type' => [
                'required',
                Rule::in(['creative', 'manager', 'studio']),
            ],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $email = $request->input('email');
        $password = $request->input('password');
        $city = $request->input('city');
        $state = $request->input('state');
        $country = $request->input('country');
        $type = $request->input('type');

        $user = new User([
            'email' => $email,
            'password' => bcrypt($password),
            'state' => $state,
            'city' => $city,
            'country' => $country,
            'type' => $type,
        ]);

        if ($user->save()) {

            $verification_code = Str::random(30); //Generate verification code
            DB::table('user_verifications')->insert(['user_id' => $user['id'], 'token' => $verification_code, 'email' => $email]);

            $data = array(
                'verification_code' => $verification_code,
            );

            Mail::to($email)->send(new SendMail($data, 1));
            return response()->json(['message' => 'Thanks for signing up! Please check your email to complete your registration.'], 200);
        }

        $response = [
            'error' => 'An error occured',
        ];

        return response()->json($response, 404);
    }

    public function verifyUser($verification_code)
    {
        $check = DB::table('user_verifications')->where('token', $verification_code)->first();
        if (!is_null($check)) {
            $user = User::find($check->user_id);
            $user['is_verified'] = 1;
            $user['email_verified_at'] = time();
            $user->save();
            DB::table('user_verifications')->where('token', $verification_code)->delete();
            return response()->view('email.email_verified');
        } else {
            return response()->view('email.invalid');
        }
    }

    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $credentials = $request->only('email', 'password');

        try {
            if (!$token = auth('api')->attempt($credentials)) {
                return response()->json(['error' => 'Wrong credentials. Please make sure of the entered email and password.'], 401);
            } else {
                //Check if email is verified
                $user = User::where('email', $request->input('email'))->first();
                if ($user['is_verified']) {
                    return $this->respondWithToken($token);
                } else {
                    $response = [
                        'error' => 'Please verify your email first',
                    ];
                    return response()->json($response, 401);
                }
            }
        } catch (JWTException $e) {
            return response()->json(['message' => 'Failed to create token'], 500);
        }
    }

    public function loginWithProvider(Request $request)
    {
        //Validation for login
        $validator = Validator::make($request->all(), [
            'provider_key' => 'required',
            'provider_type' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $provider_key = $request->input('provider_key');
        $provider_type = $request->input('provider_type');

        //-----Check if user exists------//
        $authentication_provider = AuthenticationProvider::where([['provider_key', $provider_key], ['provider_type', $provider_type]])->first();
        if ($authentication_provider) {
            $user = User::find($authentication_provider['user_id']);
            $token = auth('api')->login($user);
            if ($token) {
                return $this->respondWithToken($token);
            } else {
                $response = [
                    'error' => 'An error occured',
                ];

                return response()->json($response, 404);
            }
        }

        //Validation For register
        $validator = Validator::make($request->all(), [
            'provider_key' => 'required|unique:authentication_providers',
            'provider_type' => 'required',
            'email' => 'email|unique:users',
            'phone_number' => 'unique:users',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        //----Create New User---//
        $user = new User();
        if ($request->has('email')) {
            $user['email'] = $request->input('email');
        }

        if ($request->has('phone_number')) {
            $user['phone_number'] = $request->input('phone_number');
        }

        if ($user->save()) {

            $authentication_provider = new AuthenticationProvider([
                'provider_key' => $provider_key,
                'provider_type' => $provider_type,
                'user_id' => $user['id'],
            ]);

            if ($authentication_provider->save()) {
                $user = User::find($user['id']);
                $token = auth('api')->login($user);
                if ($token) {
                    return $this->respondWithToken($token);
                }
            }
        }

        $response = [
            'error' => 'An error occured',
        ];

        return response()->json($response, 404);
    }

    public function forgotPassword(Request $request)
    {
        //Validation
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $email = $request->input('email');

        $verification_code = Str::random(30); //Generate verification code

        $user = User::where('email', $email)->first();

        DB::table('password_resets')->insert(['user_id' => $user['id'], 'token' => $verification_code, 'email' => $email]);

        $data = array(
            'verification_code' => $verification_code,
        );

        Mail::to($email)->send(new SendMail($data, 3));
        return response()->json(['message' => 'Please check your email'], 200);
    }

    public function showPasswordResetForm($verification_code)
    {
        return response()->view('email.reset_password_fields', ['verification_code' => $verification_code]);
    }

    public function changePassword(Request $request)
    {

        $this->validate($request, [
            'password' => 'required|min:5',
            'confirm_password' => 'required|min:5',
        ]);

        if ($request->input('password') != $request->input('confirm_password')) {
            $errors = new MessageBag();

            // add your error messages:
            $errors->add('password_not_match', 'Password doesn\'t match');

            return redirect()->back()->withErrors($errors);
        }

        $item = DB::table('password_resets')->where('token', $request->input('verification_code'))->first();

        if ($item) {
            $user = User::where('id', $item->user_id)->first();
            $user['password'] = bcrypt($request->input('password'));
            $user->save();
            DB::table('password_resets')->where('token', $request->input('verification_code'))->delete();
            return response()->view('email.password_changed');
        }

        return response()->view('email.invalid');
    }

    public function sendResetPasswordLink($verification_code)
    {
        $check = DB::table('password_resets')->where('token', $verification_code)->first();
        if (!is_null($check)) {
            $user = User::find($check->user_id);
            $user['is_verified'] = 1;
            $user['email_verified_at'] = time();
            $user->save();
            DB::table('user_verifications')->where('token', $verification_code)->delete();
            return response()->view('email.email_verified');
        } else {
            return response()->view('email.invalid');
        }
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function inviteByEmail(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        Mail::to($request->input('email'))->send(new SendMail(null, 4));

        $response = [
            'msg' => 'Invitation sent',
        ];

        return response()->json($response, 200);
    }
/* Function for login user in web based */
    public function loginweb(Request $request)
    {
        $userData = $request->input();
        $email = $userData['email'];
        if (Auth::guard('web')->attempt(['email' => $userData['email'], 'password' => $userData['password']])) {
            $emailVerified = Auth::guard('web')->user()->is_verified;
            if ($emailVerified == 1) {
                $type = Auth::guard('web')->user()->type;
                $flag = Auth::guard('web')->user()->register_flag;
                $has_profile = Auth::guard('web')->user()->has_profile;
                if ($type == Config::get('constants.Manager')) {
                    if ($has_profile == Config::get('constants.hasProfile')) {
                        return redirect()->intended('/user-index');
                    } else {
                        return view('web.manager');
                    }
                } elseif ($type == Config::get('constants.Creative')) {
                    if ($has_profile == Config::get('constants.hasProfile')) {
                        return redirect()->intended('/user-index');
                    } else {
                        $genres = Genre::select('id', 'title')->get();
                        $genders = Gender::select('id', 'title')->get();
                        $crafts = Craft::select('id', 'title')->get();
                        $types = Type::select('id', 'title')->get();
                        $platforms = Platform::select('id', 'title')->get();
                        return view('web.creative', compact('genres', 'genders', 'crafts', 'types', 'platforms'));
                    }
                } elseif ($type == Config::get('constants.Studio')) {
                    if ($has_profile == Config::get('constants.hasProfile')) {
                        return redirect()->intended('/user-index');
                    } else {
                        return view('web.studio');
                    }
                }
            } else {
                return redirect('/login')->with('flash_message_error', Config::get('constants.VerifyEmail'));
            }
        } else {
            return redirect('/login')->with('flash_message_error', Config::get('constants.Invalid'));
        }

    }

/* Function for register new user in web based */
    public function registerweb(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',

        ]
        );

        if ($validator->fails()) {
            return redirect('/register')->with('flash_message_error', Config::get('constants.EmailExist'));
        }
        $email = $request->input('email');
        $password = $request->input('password');
        $city = $request->input('city');
        $state = $request->input('state');
        $country = $request->input('country');
        $type = $request->input('type');

        $user = new User([
            'email' => $email,
            'password' => bcrypt($password),
            'state' => $state,
            'city' => $city,
            'country' => $country,
            'type' => $type,
        ]);

        if ($user->save()) {

            $verification_code = Str::random(30); //Generate verification code
            DB::table('user_verifications')->insert(['user_id' => $user['id'], 'token' => $verification_code, 'email' => $email]);

            $data = array(
                'verification_code' => $verification_code,
            );

            Mail::to($email)->send(new SendMail($data, 1));

            return redirect('/login')->with('flash_message_success', Config::get('constants.UserRegister'));
        }
    }

    /* Function for render view for forget password */
    public function forgetUserPassword(Request $request)
    {
        return view('web.user_forget_password');
    }

/* Function for forget password functionality for web user*/
    public function forgotPasswordUser(Request $request)
    {
        //Validation
        $email = $request->email;
        $verification_code = Str::random(30); //Generate verification code
        $user = User::where('email', $email)->first();
        $emailExist = \DB::table('users')->where('email', '=', $email)->first();
        if ($emailExist) {
            DB::table('password_resets')->insert(['user_id' => $user->id, 'token' => $verification_code, 'email' => $email]);
            $to_name = "MusixBLVD";
            $to_email = $email;
            $path = url('resetpassword/');
            $data = array('name' => 'Musicxblvd', 'body' => $path . '/' . $email, 'verificationCode' => $verification_code);
            Mail::send('reset_password_web', $data, function ($message) use ($to_name, $to_email) {
                $message->to($to_email)
                    ->subject('Forget Password');
            });
            return redirect('/login')->with('flash_message_success', Config::get('constants.PasswordResetLink'));
        } else {
            return redirect('/forgot-password')->with('flash_message_error', Config::get('constants.EmailNotExist'));
        }
    }

/* Function for return forget password form for web user*/
    public function forgetPasswordForm(Request $request, $email)
    {
        return view('web.reset_password_form', compact('email'));
    }

/* Function for save new password via forget option for web user*/
    public function saveNewPassword(Request $request)
    {
        $email = $request->email;
        $password = $request->password;
        $verification = $request->verification;
        $access = DB::table('password_resets')->where('token', $verification)->first();
        if ($access) {
            $user = User::where('id', $access->user_id)->first();
            $user['password'] = bcrypt($password);
            $user->save();
            DB::table('password_resets')->where('token', $request->input('verification_code'))->delete();
            return redirect('/login')->with('flash_message_success', Config::get('constants.ChangePassword'));
        }
        return redirect('resetpassword/' . $email)->with('flash_message_error', Config::get('constants.VerificationError'));
    }

/* Function for change user password for web user*/
    public function changeUserPassword(Request $request)
    {
        return view('web.changePassword');
    }

/* Function for save change password  for web user*/
    public function saveChangePassword(Request $request)
    {
        $user = Auth::guard('web')->user();
        $userId = $user->id;
        $password = $request->password;
        $user = User::where('id', $userId)->first();
        $user['password'] = bcrypt($password);
        $user->save();
        return redirect('/login')->with('flash_message_success', Config::get('constants.ChangePassword'));
    }

    /* Function for logout for web user*/
    public function logout(Request $request)
    {
        \App::setlocale(1);
        $request->session()->put('lang', 1);
        Artisan::call('cache:clear');
        Auth::guard('web')->logout();
        return redirect('/login');
    }
}
