<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Models\Offer;
use App\Models\Employee;
use App\Models\Demande;


class DemandeController extends Controller
{
    public function store(Request $request)
{
 $validatedData = Validator::make($request->all(), [
    'employee_id' => 'required|exists:employees,id',
    'offer_id' => 'required|exists:offers,id',
    'required_docs' => 'nullable|file|mimes:pdf'
],
[
    'employee_id.required' => 'The employee_id field is required',
    'offer_id.required' => 'The offer_id field is required',
    'required_docs.mimes' => 'The file must be a PDF',
]);




    if ($validatedData->fails()) {
        return response()->json(['error' => $validatedData->errors()], 422);
    }


    // Handle file upload if a PDF is provided
    if ($request->hasFile('required_docs')) {
        $file = $request->file('required_docs');
        $filename = time().'_'. $file->getClientOriginalName(). Str::random(10) ; 
        $path = $file->storeAs('public/pdfs/EmployeePDF' , $filename);
    
    }

    // Create a new Demande instance with the validated data
    $demande = new Demande ; 
    $demande->offer_id = $request->offer_id ; 
    $demande->employee_id = $request->employee_id ; 
    $demande->required_docs = $request->required_docs ; 
    $demande->date = Carbon::today();
    if ($demande->save()) {
        $employee = Employee::find($demande['employee_id']);
        Mail::to($employee->email)->send(new DemandeConfirmation($demande));
        return response()->json([
            'message' => 'Demande successfuly Done' , 
            $demande  ,
        ]);
    } else {
        return response()->json([
            'message' => 'Failed to add item'
        ], 500);
    }

    // Return a response indicating success or failure
    return response()->json(['message' => 'Demande created successfully', 'demande' => $demande], 200);
}
}
