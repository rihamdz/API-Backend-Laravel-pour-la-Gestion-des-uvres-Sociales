<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Events\OfferAdded;
use App\Models\Categorie;


class OfferController  extends Controller
{
    
    public function AddOffer(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'required|string',
            'requirement' => 'nullable|string',
            'categorie_title' => 'required|exists:categories,title',
            'state' => 'required|boolean',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422); }
        
    
        // Create a new Offer instance
        $offer = new Offer();
        $offer->title = $request->title;
        $offer->description = $request->description;
        $offer->state = $request->state;
        $offer->categorie_title = $request->categorie_title ; 
        
        $offer->required_documents = $request->input('required_documents'); // Assuming required_documents is an array
        
        // Save the Offer instance to the database
        if ($offer->save()) {
            event(new OfferAdded($offer)); 

            return response()->json([
                'message' => 'Offer added successfully and mail sent to all employees' , 
                $offer  ,
            ]);
        } else {
            return response()->json([
                'message' => 'Failed to add Offer'
            ], 500);
        } 
    }
//////////////////////////////////////////////////////
/////////////////////////////////////////////////////
    public function ShowOffers(){

        $offers = Offer::all();
        return $offers ;
    }

//////////////////////////////////////////////////////
/////////////////////////////////////////////////////

public function ShowOffer(Request $request){

    $offer = Offer::find($request->id);
    return $offer ;
}

//////////////////////////////////////////////////////
/////////////////////////////////////////////////////
public function ShowOfferState(Request $request){
    
    if ($request->state == 'true')    {$offers = Offer::where('state', $request->state) ;return $offers ;
    } 
    else {$offers = Offer::where('state', $request->state) ;return $offers ;}   

}

//////////////////////////////////////////////////////
/////////////////////////////////////////////////////

    public function deleteOffer(Request $request){
        $offer = Offer::find($request->id);
        $offer->delete();
        return response()->json([
            'message' => 'Offer Deleted'
        ], 200);;
    }


//////////////////////////////////////////////////////
/////////////////////////////////////////////////////

    public function updateStatus(Request $request ){
        $validator = Validator::make($request->all(), [
            'state'    => 'required|boolean' , 
            'id'    => 'required'

            
        ], [
            'state.required' => 'Please Update  .' ,
            'id.required' => 'Pplease fill the id field  .'

        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $offer = Offer::find($request->id);
        $offer->state = $request->state;
        $offer->save();

        return response()->json([
            'message' => 'Status Updated'
        ], 200);;
    }

//////////////////////////////////////////////////////
/////////////////////////////////////////////////////

    public function EditOffer(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'title' => 'required|string',
            'description' => 'required|string',
            'requirement' => 'nullable|string',
            'categorie_title' => 'required|exists:categories,title',
            'state' => 'required|boolean',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422); }

        $offer = Offer::find($request->id);
        $offer->title = $request->title;
        $offer->description = $request->description;
        $offer->required_documents = $request->required_documents;
        $offer->state = $request->state;
        $offer->categorie_title = $request->categorie_title ;
        $offer->update();
        return response()->json([
            'message' => 'Status Updated' , 
            $offer
        ], 200);


    }
  //////
//////////////////////////////////////////////////////
/////////////////////////////////////////////////////

public function FilterByTitle(Request $request){
    $validator = Validator::make($request->all(), [
        'title' => 'required|string|exists:offers,title',
       
    ]);
    
    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 422); }

    $offer = Offer::where('title', $request->title)->first();   

    if ($offer) {
        // Offer found
        return response()->json(['offer' => $offer], 200);
    } else {
        // Offer not found
        return response()->json(['message' => 'Offer not found'], 404);
    }
   


}

//////////////////////////////////////////////////////
/////////////////////////////////////////////////////


public function FilterByCategorie(Request $request){
    $validator = Validator::make($request->all(), [
        'title' => 'required|string|exists:categories,title',
       
    ]);
    
    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 422); }

   
        $offers =  Offer::where('categorie_title' , $request->title)->get() ; 
        return response()->json([
        'message' => 'Resultat' , 
        $offers
    ], 200);


}



//////////////////////////////////////////////////////
/////////////////////////////////////////////////////

}
