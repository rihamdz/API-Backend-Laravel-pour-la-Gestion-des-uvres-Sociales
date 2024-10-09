<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Offer;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Minha;


class MinhaController extends Controller
{

/////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////
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
            'state' =>'required|string'
        ]);
        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422); }
     
            $minha = new Minha();
            $minha->offer_title = $request->offer_title;
            $minha->first_name = $request->first_name;
            $minha->last_name = $request->last_name;
            $minha->phone = $request->phone;
            $minha->second_phone = $request->second_phone;
            $minha->corp = $request->corp;
            $minha->rank = $request->rank;
            $minha->date_employment = $request->date_employment ; 
            $minha->employee_id = $request->employee_id ;        
            $minha->state = $request->state ;     
    


         if ($request->hasFile('justification') ) {
        $pdf = $request->file('justification');
        $filename = time() . '_' . $pdf->getClientOriginalName(). Str::random(10);
        $path = $pdf->storeAs('public/pdfs', $filename);
        $minha->justification = $path; }  

        if ($minha->save()) {
            return response()->json([
                'message' => 'minha added successfully' , 
                $minha  ,
            ]);
        } else {
            return response()->json([
                'message' => 'Failed to add minha'
            ], 500);
        }



         }



/////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////






}
