<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Employee;
use App\Models\Commity;
use App\Imports\EmployeeImport;
use App\Exports\EmployeeExport ; 
use App\Imports\CommityImport;
use App\Exports\CommityExport;

use Maatwebsite\Excel\Facades\Excel;


class AdminController extends Controller
{
    /////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////
    public function login(Request $request)
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
         if ($request->email == 'admin@esi-sba.dz' && $request->password == 'admin'){

            return response()->json(['message' => 'ADMIN login successfully'], 200);

        } else {

            return response()->json(['message' => 'Wrong'], 400);
        }
         }
    ///////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////

    public function AddEmployee(Request $request) {

    $validatedData = Validator::make($request->all(), [
            'full_name' => 'required|string', 
            'email' => 'required|email|max:255',
            'phone' => 'required|min:9|numeric',
            'salary' =>'required|numeric|min:0|max:999999.99'
          ],
          [
            'full_name.required' => 'The full name field is required',
            'full_name.string' => 'The full name field must be a string',
            'email.required' => 'The email field is required',
            'email.email' => 'The email field is in Wrong format',
            'phone.required' => 'The phone number field is required',
            'phone.numeric' => 'The phone number field must be a numeric',
            'phone.regex' => 'The phone number field is in the wrong format',
            'salary.required' => 'The salary field is required',
            'salary.numeric' => 'The salary field must be a numeric value',
            'salary.min' => 'The salary field must be at least 0',
            'salary.max' => 'The salary field must not exceed 999999.99',
  
          ]);
  
          if ($validatedData->fails()) {
              return response()->json(['error' => $validatedData->errors()], 422);
          }

          $employee = new Employee();
          $employee->email = $request->email ;
          $employee->full_name = $request->full_name ;
          $employee->phone = $request->phone;
          $employee->salary = $request->salary ;

          if(Employee::where('email', $request->email)->first()){
            return response()->json([
                'message'=>'You cant add employee already existe' ], 404);
          }
          
          if($employee->save()){ 
            
            return response()->json([
              'message'=>'Employee added successfully' , $employee ], 200);
          } else 
          {
            return response()->json([
                'message'=>'Erreur'
            ]);
          }

        }
    
    ////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////
    
    public function AddCommity(Request $request) {

        $validatedData = Validator::make($request->all(), [
                'full_name' => 'required|string', 
                'email' => 'required|email|max:255',
                'phone' => 'required|min:9|numeric',
                'type' => 'required|in :PRESEDENT,VICE-PRESEDENT,TRESORIER,SIMPLE' , //unique_values_for_PRESEDENT_and_VICE-PRESEDENT_and_TRESORIER', // Custom validateur i will write it later 
                'salary' =>'required|numeric|min:0|max:999999.99'
              ],
              [
               'full_name.required' => 'The full name field is required',
                'full_name.string' => 'The full name field must be a string',
                'email.required' => 'The email field is required',
                'email.email' => 'The email field is in Wrong format',
                'phone.required' => 'The phone number field is required',
                'phone.numeric' => 'The phone number field must be a numeric',
                'phone.regex' => 'The phone number field is in the wrong format',
                'salary.required' => 'The salary field is required',
                'salary.numeric' => 'The salary field must be a numeric value',
                'salary.min' => 'The salary field must be at least 0',
                'salary.max' => 'The salary field must not exceed 999999.99',
                'type.required' => 'The type field is required',
                'type.in' => 'The type field must be one of: PRESEDENT , VICE-PRESEDENT , TRESORIER , SIMPLE',
      
              ]);
      
              if ($validatedData->fails()) {
                  return response()->json(['error' => $validatedData->errors()], 422);
              }
    
              $commity = new Commity();
              $commity->email = $request->email ;
              $commity->full_name = $request->full_name ;
              $commity->phone = $request->phone;
              $commity->salary = $request->salary ;
              $commity->type = $request->type ;

    
              if(Commity::where('email', $request->email)->first()){
                return response()->json([
                    'message'=>'You cant add commity member already existe' ], 404);
              }
              
              if($commity->save()){ 
                
                return response()->json([
                  'message'=>'commity member added successfully' , $commity ], 200);
              } else 
              {
                return response()->json([
                    'message'=>'Erreur'
                ]);
              }}
    
    ////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////
    
       public function ShowEmployees(Request $request) {

        $employees = Employee::all();
        return $employees ;


       }
    ////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////


       public function ShowCommities(Request $request) {
        $commity_leaders = Commity::whereIn('type', ['a', 'b'])->get();; 
        $commity_members = Commity::whereNotIn('type', ['PRESEDENT', 'VICE-PRESEDENT' , 'TRESORIER'])->get();; 
         return [
            'commity_leaders' => $commity_leaders,
            'commity_members' => $commity_members
        ]; ; }

    ////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////

    public function FindEmployee(Request $request) {
        $validatedData = Validator::make($request->all(), [
            'id' => 'required|integer', 
          ],
          [
           'id.required' => 'The id field is required',
           'id.integer' => 'The id field is integer ',
          ]);
  
          if ($validatedData->fails()) {
              return response()->json(['error' => $validatedData->errors()], 422);
          }

        $employee = Employee::find($request->id);
        return $employee ;
        }



    ////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////

    public function EditEmployee(Request $request) {
        $validatedData = Validator::make($request->all(), [
            'id' => 'required|integer', 
          ],
          [
           'id.required' => 'The id field is required',
           'id.integer' => 'The id field is integer ',
          ]);
  
          if ($validatedData->fails()) {
              return response()->json(['error' => $validatedData->errors()], 422);
          }

        $employee = Employee::find($request->id);
        $validatedData = Validator::make($request->all(), [
            'full_name' => 'required|string', 
            'email' => 'required|email|max:255',
            'phone' => 'required|min:9|numeric',
            'salary' =>'required|numeric|min:0|max:999999.99'
          ],
          [
            'full_name.required' => 'The full name field is required',
            'full_name.string' => 'The full name field must be a string',
            'email.required' => 'The email field is required',
            'email.email' => 'The email field is in Wrong format',
            'phone.required' => 'The phone number field is required',
            'phone.numeric' => 'The phone number field must be a numeric',
            'phone.regex' => 'The phone number field is in the wrong format',
            'salary.required' => 'The salary field is required',
            'salary.numeric' => 'The salary field must be a numeric value',
            'salary.min' => 'The salary field must be at least 0',
            'salary.max' => 'The salary field must not exceed 999999.99',
  
          ]);
  
          if ($validatedData->fails()) {
              return response()->json(['error' => $validatedData->errors()], 422);
          }


        $employee->email = $request->email ;
        $employee->full_name = $request->full_name ;
        $employee->phone = $request->phone;
        $employee->salary = $request->salary ;

        if(Employee::where('email', $request->email)->first()){
            return response()->json([
                'message'=>'You cant add employee already existe' ], 404);
          }
          
          if($employee->save()){ 
            
            return response()->json([
              'message'=>'Employee added successfully' , $employee ], 200);
          } else 
          {
            return response()->json([
                'message'=>'Erreur'
            ]);
          }

        }

    ////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////

    public function FindCommity(Request $request) {
        $validatedData = Validator::make($request->all(), [
            'id' => 'required|integer', 
          ],
          [
           'id.required' => 'The id field is required',
           'id.integer' => 'The id field is integer ',
          ]);
  
          if ($validatedData->fails()) {
              return response()->json(['error' => $validatedData->errors()], 422);
          }

        $commity = Commity::find($request->id);
        return $commity ;
        }

    ////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////



    public function EditCommity(Request $request) {
        $validatedData = Validator::make($request->all(), [
            'id' => 'required|integer', 
          ],
          [
           'id.required' => 'The id field is required',
           'id.integer' => 'The id field is integer ',
          ]);
  
          if ($validatedData->fails()) {
              return response()->json(['error' => $validatedData->errors()], 422);
          }

        $commity = Commity::find($request->id);
        $validatedData = Validator::make($request->all(), [
            'full_name' => 'required|string', 
            'email' => 'required|email|max:255',
            'phone' => 'required|min:9|numeric',
            'salary' =>'required|numeric|min:0|max:999999.99' , 
            'type' => 'required|in :PRESEDENT,VICE-PRESEDENT,TRESORIER,SIMPLE'
          ],
          [
            'full_name.required' => 'The full name field is required',
            'full_name.string' => 'The full name field must be a string',
            'email.required' => 'The email field is required',
            'email.email' => 'The email field is in Wrong format',
            'phone.required' => 'The phone number field is required',
            'phone.numeric' => 'The phone number field must be a numeric',
            'phone.regex' => 'The phone number field is in the wrong format',
            'salary.required' => 'The salary field is required',
            'salary.numeric' => 'The salary field must be a numeric value',
            'salary.min' => 'The salary field must be at least 0',
            'salary.max' => 'The salary field must not exceed 999999.99',
            'type.required' => 'The type field is required',
            'type.in' => 'The type field must be one of: PRESEDENT , VICE-PRESEDENT , TRESORIER , SIMPLE',
  
          ]);
  
          if ($validatedData->fails()) {
              return response()->json(['error' => $validatedData->errors()], 422);
          }


        $commity->email = $request->email ;
        $commity->full_name = $request->full_name ;
        $commity->phone = $request->phone;
        $commity->salary = $request->salary ;

     
          
          if($commity->save()){ 
            
            return response()->json([
              'message'=>'Commity edited successfully' , $commity ], 200);
          } else 
          {
            return response()->json([
                'message'=>'Erreur'
            ]);
          }

        }
    ////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////

    public function DeleteEmployee(Request $request) {


        $validatedData = Validator::make($request->all(), [
            'id' => 'required|integer', 
          ],
          [
           'id.required' => 'The id field is required',
           'id.integer' => 'The id field is integer ',
          ]);
  
          if ($validatedData->fails()) {
              return response()->json(['error' => $validatedData->errors()], 422);
          }

        $employee = Employee::find($request->id);
       if ($employee->active == true ){$employee->active = false ; return 'EMPLOYEE DELETED ' ; } 
         else {return 'YOU CAN NOT DELETE IT BEACAUSE HE IS ALREADY OFF' ; }
       

    }


    ////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////


    public function DeleteCommity(Request $request) {


        $validatedData = Validator::make($request->all(), [
            'id' => 'required|integer', 
          ],
          [
           'id.required' => 'The id field is required',
           'id.integer' => 'The id field is integer ',
          ]);
  
          if ($validatedData->fails()) {
              return response()->json(['error' => $validatedData->errors()], 422);
          }

        $commity = Commity::find($request->id)->delete() ;
      
         return 'Commity member deleted' ; }
       

    


    ////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////

    public function ImportEmployee(Request $request) { 
        
        $validatedData = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls,csv|max:2048' 
            
          ],
          [
            'file.required' => 'The file field is required',
            'file.max' => 'The file size must less then 2 mb',
            'file.mimes' => 'The file  is wrong type',
            
  
          ]);
  
          if ($validatedData->fails()) {
              return response()->json(['error' => $validatedData->errors()], 422);
          }
        
        $import = new EmployeeImport() ; 
        Excel::import($import ,$request->file('file'));

        return 'Good' ;
        } 

    

    ////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////


    public function ImportCommity(Request $request) { 
        
        $validatedData = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls,csv|max:2048' 
            
          ],
          [
            'file.required' => 'The file field is required',
            'file.max' => 'The file size must less then 2 mb',
            'file.mimes' => 'The file  is wrong type',
            
  
          ]);
  
          if ($validatedData->fails()) {
              return response()->json(['error' => $validatedData->errors()], 422);
          }
        
        $import = new CommityImport() ; 
        Excel::import($import ,$request->file('file'));

        return 'Good' ;
        } 

    ////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////


    public function ExportEmployees() 
    {
        return Excel::download(new EmployeeExport, 'employees.xlsx');
    }

    ///////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////

    public function ExportCommities() 
    {
        return Excel::download(new CommityExport, 'commities.xlsx');
    }


        }











    
    
    
//Search + custom validateur unique a b c 


        
















    



