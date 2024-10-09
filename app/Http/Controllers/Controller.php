<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Mail;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;
use App\Models\Employee;
use App\Models\User;
use App\Mail\ConfirmEmail;
use App\Mail\ResetPasswordMail ;


class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;


    /////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////
    public function RegisterEmployee(Request $request)
    {

        $validatedData = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required|confirmed|string|min:5'
        ],
        [
            'email.required' => 'The email field is required',
            'email.email' => 'The email field is in Wrong format',
            'password.required' => 'The password field is required',
            'password.min' => 'The password field must be at least 5 caracteres ',
            'password.min' => 'The password Confirmation does not match ',

        ]);

        if ($validatedData->fails()) {
            return response()->json(['error' => $validatedData->errors()], 422);
        }
    
        // Recherchez l'employé avec l'e-mail spécifié
        $employee = Employee::where('email', $request->email)->first();
        $user = User::where('email', $request->email)->first();

        if ($employee) {

            if (!$user) {
                // Si l'employé n'a pas encore de compte utilisateur, créez-en un
                $user = new User();
                $user->email = $request->email;
                $user->password = Hash::make($request->password);
                $user->confirmation_token = Str::random(40); // Generate a random confirmation token
                $user->save();
               
               // Send email verification notification
               Mail::to($user->email)->send(new ConfirmEmail($user));
                return response()->json(['message' => 'User registered successfully , We sent you a email confirmation']);
            } else {
                // Si l'employé a déjà un compte utilisateur, renvoyez une erreur
                return response()->json(['message' => 'Employee already has a user account'], 403);
            }
        } else {
            // Si aucun employé avec cet e-mail n'existe, renvoyez une erreur
            return response()->json(['message' => 'Employee does not exist'], 404);
        }
    }


    ////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////

    public function Email_Confirmation($token)
    {
        $validatedData = Validator::make($request->all(), [
            'token' => 'required|string|max:40|min:40',
            
        ],
        [
            'token.required' => 'The token field is required',
            'token.min' => 'The token field must be at least 40 caracteres ',
            'token.max' => 'The token field must be at max 40 caracteres ',
            'token.string' => 'The token field must be string ',


        ]);      
        if ($validatedData->fails()) {
            return response()->json(['error' => $validatedData->errors()], 422);
        }  
        $user = User::where('confirmation_token', $token)->first();
        if (!$user) {
            return response()->json(['error' => 'Invalid token'], 404);
        }

        $user->email_verified_at = now();
        $user->confirmation_token = null;
        $user->save(); 

        return response()->json(['message' => 'Email valided'], 200);

    
    }

    ////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////


    public function LoginEmployee(Request $request)
    {

        $validatedData = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:5'
        ],
        [
            'email.required' => 'The email field is required',
            'email.email' => 'The email field is in Wrong format',
            'password.required' => 'The password field is required',
            'password.min' => 'The password field must be at least 5 caracteres ',

        ]);

        if ($validatedData->fails()) {
            return response()->json(['error' => $validatedData->errors()], 422);
        }


        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['error' => 'Invalid data'], 404);
       }
 
        if ($user->email_verified_at == null) {
        return response()->json(['error' => 'You must verify your email first'], 404);
        }

       $employee = Employee::where('email', $request->email)->first();

       
       return response()->json(['message' => $employee], 200);
     }
    ////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////

    public function ForgotPassword(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
        ], [
            'email.required' => 'The email field is required',
            'email.email' => 'The email field is in the wrong format',
        ]);
    
        if ($validatedData->fails()) {
            return response()->json(['error' => $validatedData->errors()], 422);
        }
    
        $user = User::where('email', $request->email)->first();
    
        if (!$user) {
            return response()->json(['error' => 'No user found with this email address'], 404);
        }
    
        $user->otp = mt_rand(0000, 9999); // Ensure a 4-digit OTP
        $user->save();
    
        Mail::to($user->email)->send(new ResetPasswordMail($user));
    
        return response()->json(['message' => 'OTP sent successfully']);
    }
       
    ////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////

    public function VerifyOtp(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'otp' => 'required|string',
        ], [
            'email.required' => 'The email field is required',
            'email.email' => 'The email field is in the wrong format',
            'otp.required' => 'The otp field is required',
            'otp.string' => 'The otp field is in the wrong format',

        ]);
    
        if ($validatedData->fails()) {
            return response()->json(['error' => $validatedData->errors()], 422);
        }

        $user = User::where('email', $request->email)->first(); 
        $done = ($user->otp == $request->otp) ;
        if (!$done) {
            return response()->json(['error' => 'Invalid OTP'], 400);
        }

        // If OTP is valid, allow the user to reset their password
        return response()->json(['message' => 'OTP verified successfully']);
    }

    ////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////

    public function SetNewPassword(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'otp' => 'required|string',
            'password' => 'required|confirmed',

        ], [
            'email.required' => 'The email field is required',
            'email.email' => 'The email field is in the wrong format',
            'otp.required' => 'The otp field is required',
            'otp.string' => 'The otp field is in the wrong format',
            'password.required' => 'The password field is required' , 
            'password.confirmed' => 'The password confirmation field is required' , 


        ]);
    
        if ($validatedData->fails()) {
            return response()->json(['error' => $validatedData->errors()], 422);
        }

        // Validate OTP
        $user = User::where('email', $request->email)->where('otp', $request->otp)->first();
        
        if (!$user) {
            return response()->json(['error' => 'Invalid OTP'], 400);
        }

        $user->password = Hash::make($request->password);
        $user->otp = null ; 
        $user->save();

        return response()->json(['message' => 'Password reset successfully']);
    }







    ////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////



}
