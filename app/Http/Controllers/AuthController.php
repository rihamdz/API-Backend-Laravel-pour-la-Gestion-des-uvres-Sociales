<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use App\Mail\EmailVerificationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        
        $validatedData = Validator::make($request->all(), [
            'name' => 'required|string|max:40',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8'
        ],
        [
            'name.required' => 'The name field is required',
            'name.string' => 'The name field must be string',
            'email.required' => 'The email field is required',
            'email.email' => 'The email field is in Wrong format',
            'password.required' => 'The password field is required',
        ]);
        
        
        
        
            if ($validatedData->fails()) {
                return response()->json(['error' => $validatedData->errors()], 422);
            }
    
        // Recherchez un employé avec l'e-mail spécifié
        $employee = Employee::where('email', $request->email)->first();
    
        if ($employee) {
            // Si un employé avec cet e-mail existe déjà, mettez à jour ses données
            $employee->update([
                'name' => $request->name,
                'active' => true, 
            ]);
            //créez un nouvel utilisateur dans la table "users"
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password) , 
                'email_verified_at' => null, // Marquer comme non vérifié pour le moment
            ]);
              // Génération d'un token pour la vérification par e-mail
        $verificationToken = Str::random(60);
        // $user->verification_token = $verificationToken; 
        $user->save();
        
        // Envoi de l'e-mail de vérification
        // Vous pouvez utiliser Laravel Notification ou d'autres bibliothèques pour gérer l'envoi d'e-mails
        // Voici un exemple simple pour l'envoi d'e-mails
        Mail::to($user->email)->send(new EmailVerificationNotification($user));
        return response()->json(['message' => 'User added successfully'], 200);

        } else {
            // Si aucun employé avec cet e-mail n'existe, retournez une erreur
            return response()->json(['message' => 'Employee not found with this email'], 404);
        }
    }

    
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
        // Vérifiez les informations d'identification pour l'authentification API
        if (Auth::attempt($credentials)) {
            // Authentification réussie
            $user = Auth::user();
            $token = $user->createToken('Personal Access Token')->plainTextToken;
            return response()->json(['token' => $token]);
        } else {
            // Authentification échouée
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }
    
}