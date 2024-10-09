<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Mail\EmailInvitation ;
use Carbon\Carbon;
use App\Models\Meet;
use App\Models\Employee;
use App\Models\Commity;

use Illuminate\Support\Facades\Mail;


;

class MeetController extends Controller
{

    public function AddMeet(Request $request)
    {
    
        $validatedData = Validator::make($request->all(),[
            'name' => 'required|string',
            'date' => 'required|date',
            'start_time' => 'required|',
            'duration' => 'required|integer|min:1',
            'invited' => 'nullable|array|exists:commities,email',
        ], [
            'invited.required' => 'The invited employee email field is required',
            'invited.exists' => 'One or more of the invited commities does not exist',
        ]);
    
        if ($validatedData->fails()) {
            return response()->json(['error' => $validatedData->errors()], 422);
        }
    
        $meet = new Meet();
        $meet->name = $request->name;
        $meet->date = $request->date;
        $meet->start_time = $request->start_time;
        $meet->duration = $request->duration; 
        $invited = $request->input('invited');
        $meet->invited = json_encode($invited) ; 
  
        $commities = Commity::whereIn('email', $invited)->get()->keyBy('email');
        
        
        /* foreach ($request->input('invited') as $email) {
            $commity = Commity::where('email', $email)->first();
            Mail::to($commity->email)->send(new EmailInvitation($meet));
                } */
            
        foreach ($commities as $email) {
                        Mail::to($email)->send(new EmailInvitation($meet));
                    }
            
        if($meet->save()){ return response()->json([
                'message' => 'Meet invitation sent successfully',
                'meet' => $meet,
            ], 200);}
        
         else {return response()->json([
                'message' => 'Failed to add meet'
            ], 500); 
        } }
    







        //////////////////////////////////////////////////////
        //////////////////////////////////////////////////////



     public function AddPv(Request $request)
    {
    // Validate the request
    $validator = Validator::make($request->all(), [
        'id' => 'required|exists:meets,id', 
        'pv' => 'required|file|mimes:pdf|max:2048', // Ensuring the file is a PDF and has a max size limit
    ]);
    
    // Return validation errors
    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 422);
    }

    // Find the meet record
    $meet = Meet::where('id', $request->id)->first();
    
    // If meet not found
    if (!$meet) {  
        return response()->json(['message' => 'No meet found with this ID'], 404);
    }

    // Handle file upload
    if ($request->hasFile('pv')) {
        $pdf = $request->file('pv');
        $filename = time() . '_' . Str::random(10) . '.' . $pdf->getClientOriginalExtension();
        $path = $pdf->storeAs('/home/kali/backendProject_1SC-Dev/storage/app/public/pdfs/', $filename);

        // Update the meet record with the path
        $meet->pv = $path;
    }  

    // Save the meet record
    if ($meet->save()) {
        return response()->json([
            'message' => 'PDF added successfully',
            'meet' => $meet,
        ], 200);
    } else {
        return response()->json(['message' => 'Failed to add PDF'], 500);
    }}


        //////////////////////////////////////////////////////
        //////////////////////////////////////////////////////



        public function ConsulerPv(Request $request)
        {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:meets,id', 
        ]);
        
        // Return validation errors
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
    
        $meet = Meet::where('id', $request->id)->first();

        return response()->json([
            'message' => 'PDF pv',
            'pv' => $meet->pv,
        ], 200);
    
        
    
    }

        //////////////////////////////////////////////////////
        //////////////////////////////////////////////////////



        public function ShowMeets()
        {
            $today = now()->toDateString() ;
            // Fetch all records from the database
            $meets = Meet::all();
        
            // Initialize arrays to hold categorized records
            $meetsFuture = [];
            $meetsPast = [];
        
            // Loop through each record and compare dates and times
           /* foreach ($meets as $meet) {
                // Create a Carbon instance for the start time
                $startTime = Carbon::createFromFormat('H:i:s', $meet->start_time);
                
                // Add the duration to the start time to get the end time
                $endTime = $startTime->copy()->addMinutes($meet->duration);
        
                if ($startTime->lessThanOrEqualTo($now) && $endTime->greaterThanOrEqualTo($now)) {
                    $meetCurrent[] = $meet; // Current
                } elseif ($startTime->greaterThan($now)) {
                    $meetsFuture[] = $meet; // Future
                } else {
                    $meetsPast[] = $meet; // Past
                }
            } */



            foreach ($meets as $meet) {
        
                if ($meet->date >= $today ) {
                    $meetsFuture[] = $meet; }// Future
                else {
                    $meetsPast[] = $meet; // Past
                }
            }
        
            // Return the categorized records as a JSON response
            return response()->json([
                'future' => $meetsFuture,
                'past' => $meetsPast, 
            ]);
        
    
    }
















}

























