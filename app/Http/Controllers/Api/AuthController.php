<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Foundation\Auth\ResetsPasswords;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role'=> $request->role,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;
        $user->sendEmailVerificationNotification();
        return response()->json([
            'data' => $user,
            'access_token' => $token,            
            'token_type' => 'Bearer',
            'massage' => 'silahkan cek email anda'
        ]);
    }

    public function login(Request $request)
    {
        if (! Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $role = User::where('email', $request->email)->get('role');

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login success',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'Role' => $role,
        ]);
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();
        return response()->json([
            'message' => 'logout success'
        ]);
    }

    public function verify(Request $request)
    {
        $userID = $request['id'];
        $user = User::find($userID);
        // $date = date('Y-m-d g:i:s');
        $user->email_verified_at = now();
        $user->save();
        // return view('emailverified');
        // return redirect()->to('emailverified');
        return redirect('emailverified');
    }  

    public function resend(Request $request)
    {        
        $userEmail = $request['email'];
        $user = User::where("email", $userEmail)->first();
        if(!$user) {
            return response()->json("There is no user with that email in our data", 401);
        } elseif ($user['email_verified_at'] != null) {
            return response()->json("The user email has been verified", 401);
        }
        $user->sendEmailVerificationNotification();
        return response()->json("The notification has been sent to your mailbox!", 200);
    }

    public function forgot() {
        $credentials = request()->validate(['email' => 'required|email']);

        Password::sendResetLink($credentials);

        return response()->json(["message" => "Reset Password LINK Has Been Sent To Your Emal"], 200);
    }

    public function reset(ResetPasswordRequest $request) {
        $reset_password_status = Password::reset($request->validated(), function ($user, $password) {
            $user->password = Hash::make($password);
            $user->save();
        });

        if ($reset_password_status == Password::INVALID_TOKEN) {
            return response()->json(["error" => "Invalid TOken", 255]);
        }

        // return response()->json(["success" => "Your Password Has Been Changed"], 200);
        return view('succesforgot');
    }
}
