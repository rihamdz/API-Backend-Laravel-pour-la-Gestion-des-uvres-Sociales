<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Offer;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Silfa;
class SilfaContoller extends Controller
{
    public function StoreDemande(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'offer_title' => 'required|exists:offers,title',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'second_phone' => 'nullable|string|max:20',
            'corp' => 'required|in:prof,administrateur,worker',
            'rank' => 'required|string|max:255',
            'justification' => 'required|file',
            'date_employment' => 'required|date',
            'employee_id' => 'required|exists:employees,id', 
            'deduction' => 'required|numeric', 
            'for' => 'required|string', 
            'state' =>'required|string' ,
            'amount' =>'required' ,

        ]);
        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422); }
     
            $silfa = new Silfa();
            $silfa->offer_title = $request->offer_title;
            $silfa->first_name = $request->first_name;
            $silfa->last_name = $request->last_name;
            $silfa->phone = $request->phone;
            $silfa->second_phone = $request->second_phone;
            $silfa->corp = $request->corp;
            $silfa->rank = $request->rank;
            $silfa->date_employment = $request->date_employment ;     
            $silfa->employee_id = $request->employee_id ;   
            $silfa->amount = $request->amount ;  
            $silfa->deduction = $request->deduction ;   
            $silfa->for = $request->for ;     
            $silfa->state = $request->state ;     

  
  
  


         if ($request->hasFile('justification') ) {
        $pdf = $request->file('justification');
        $filename = time() . '_' . $pdf->getClientOriginalName(). Str::random(10);
        $path = $pdf->storeAs('public/pdfs', $filename);
        $silfa->justification = $path; }  

        if ($silfa->save()) {
            return response()->json([
                'message' => 'minha added successfully' , 
                $silfa  ,
            ]);
        } else {
            return response()->json([
                'message' => 'Failed to add minha'
            ], 500);
        }



         }
}
