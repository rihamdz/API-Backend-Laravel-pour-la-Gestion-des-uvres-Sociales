<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categorie;
use Illuminate\Support\Facades\Validator;



class CategorieController extends Controller
{

/////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////
    public function AddCategorie(Request $request) 
    {
        $validatedData = Validator::make($request->all(), [
            'title' => 'required|string|max:255|unique:categories',
        ],
        [
            'title.required' => 'The title field is required',
            'title.string' => 'The title field is in Wrong format',
            'title.unique' => 'The title already exists',

        ]);

        if ($validatedData->fails()) {
            return response()->json(['error' => $validatedData->errors()], 422);
        }   
    $categorie = new categorie() ; 
    $categorie->title = $request->title ; 
    


    if($categorie->save()){ 
            
        return response()->json([
          'message'=>'categorie added successfully' , $categorie ], 200);
      } else 
      {
        return response()->json([
            'message'=>'Erreur'
        ],500);
      }}

      

/////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////

public function EditCategorie(Request $request) 
{
    $validatedData = Validator::make($request->all(), [
        'title' => 'required|string|max:255',     
    ],
    [
        'title.required' => 'The title field is required',
        'title.string' => 'The title field is in Wrong format',
    ]);

    if ($validatedData->fails()) {
        return response()->json(['error' => $validatedData->errors()], 422);
    }   

    $categorie = Categorie::find($request->id);
    $categorie->title = $request->title ; 
  
    if($categorie->save()){ 
        
    return response()->json([
      'message'=>'categorie edited successfully' , $categorie ], 200);
  } else 
  {
    return response()->json([
        'message'=>'Erreur'
    ]);
  }}
/////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////

public function DeleteCategorie(Request $request) 
{
    $validatedData = Validator::make($request->all(), [
        'id' => 'required',
       
    ],
    [
        'id.required' => 'The id field is required',

    ]);

    if ($validatedData->fails()) {
        return response()->json(['error' => $validatedData->errors()], 422);
    }   

    $categorie = Categorie::find($request->id);

      if($categorie->delete()){ 
        
    return response()->json([
      'message'=>'categorie deleted successfully'  ], 200);
  } else 
  {
    return response()->json([
        'message'=>'Erreur'
    ]);
  }}

/////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////

public function ShowCategories(Request $request) {
 
    return Categorie::All() ; 


}


}