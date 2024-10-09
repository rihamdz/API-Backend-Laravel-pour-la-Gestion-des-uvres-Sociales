<?php
namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Silfa;
use App\Models\Minha;
use App\Models\Commity;
use Illuminate\Support\Str;




class EmployeeController extends Controller
{
    
    public function index()
    {
        return Employee::select('id','email','phoneNumber', 'name', 'salary')->get();
    }

    
   
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email'=>  'required|email',
            'name'	=>  'required|string|',
            'phoneNumber'=>'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:10',
            'salary'=> 'required|numeric' ,

        ],[
            'email.required' => 'Please Instert the email .' , 'email.email' => 'Please Instert valid email .' , 
            'name.required' => 'Please Instert the name' , 'name.string' => 'Please Instert correct name' , 
            'phoneNumber.required' => 'Please Instert the the phone ' , 'phoneNumber.numeric' => 'Please Instert correct phone number' , 


        ]);
       Employee::create($request->post());
        $employee = Employee::create($request->only('email', 'name', 'phoneNumber', 'salary'));
        return response()->json([
            'message'=>'Item add succsufly' ,
            $employee 


        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee )
    {
        return   response()->json([
            'employee'=>$employee
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'name' => 'required',
            'phoneNumber'=>'required',
            'salary' => 'required|numeric',
        ]);
        $employee->fill($request->post())->update();
        return response()->json([
            'message'=>'Item update succsufly'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();
        return response()->json([
            'message'=>'Item deleted succsufly'
        ]);
    }
///////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////

public function ShowDemands(Request $request)
{
    $validatedData = Validator::make($request->all(), [
        'employee_id' => 'required|exists:employees,id',
    ],
    [
        'employee_id.required' => 'The id field is required',
        'employee_id.exists' => 'no employee with this id',
    ]);

    if ($validatedData->fails()) {
        return response()->json(['error' => $validatedData->errors()], 422);
    }
    // Retrieve demands made by the employee with the specified ID
    $silfa = Silfa::where('employee_id', $request->employee_id)->get();
    $minha = Minha::where('employee_id', $request->employee_id)->get();

    // Pass the demands data to the view for rendering
    
    $combinedDemands = $silfa->concat($minha);

    return $combinedDemands ;
        }



///////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////

public function ConsulterState(Request $request)
{
    $validatedData = Validator::make($request->all(), [
        'employee_id' => 'required|exists:employees,id',
    ],
    [
        'employee_id.required' => 'The id field is required',
        'employee_id.exists' => 'no employee with this id',
    ]);

    if ($validatedData->fails()) {
        return response()->json(['error' => $validatedData->errors()], 422);
    }
    // Retrieve demands made by the employee with the specified ID
    $silfa = Silfa::where('employee_id', $request->employee_id)->get();
    $minha = Minha::where('employee_id', $request->employee_id)->get();

    // Pass the demands data to the view for rendering
    
    $combinedDemands = $silfa->concat($minha);

    return $combinedDemands ;
        }
///////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////


public function ShowProfile(Request $request)
{
    $validatedData = Validator::make($request->all(), [
        'role' => 'required',
        'id' => 'required',

    ],
    [
        'role.required' => 'The role field is required',
        'id.exists' => 'The id field is required',
    ]);

    if ($validatedData->fails()) {
        return response()->json(['error' => $validatedData->errors()], 422);
    }
      
    if($request->role == 'employee') {

        $employee = Employee::where('id', $request->id)->first();
        return response()->json([
            'employee' => $employee,
            'role' => 'employee',
        ], 200);
    }
    if($request->role == 'commity') {

        $commity = Commity::where('id', $request->id)->first();
        return response()->json([
            'commity' => $commity,
            'role' => $commity->type,
        ], 200);
    }}
//////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////


public function AddPhoto(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'role' => 'required',
            'id' => 'required',

        ], [
            'photo.mimes' => 'Please Upload an image .',
            'photo.max' => 'The image may not be greater than 2048 kilobytes.',
            'role.required' => 'The role field is required',
            'id.required' => 'The id field is required',
            
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);}


        if($request->role == 'employee') {

                $employee = Employee::where('id', $request->id)->first();

                if ($request->hasFile('photo')) {
                    $image = $request->file('photo');
                    $filename = time() . '_' . $image->getClientOriginalName() . Str::random(10);
                    $path = $image->storeAs('public/images', $filename);
                    $employee->avatar = $path; }

                return response()->json([
                    'employee' => $employee,
                    'role' => 'employee',
                ], 200);
            }
        if($request->role == 'commity') {
        
                $commity = Commity::where('id', $request->id)->first();

                if ($request->hasFile('photo')) {
                    $image = $request->file('photo');
                    $filename = time() . '_' . $image->getClientOriginalName() . Str::random(10);
                    $path = $image->storeAs('public/images', $filename);
                    $commity->avatar = $path; }




                return response()->json([
                    'commity' => $commity,
                    'role' => $commity->type,
                ], 200);
            }}


//////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////


public function SetNewPassword(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'role' => 'required',
            'id' => 'required',
            'password' =>'required|confirmed'

        ], [
            
            'role.required' => 'The role field is required',
            'id.required' => 'The id field is required',
            
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);}


        if($request->role == 'employee') {

                $employee = Employee::where('id', $request->id)->first();

                if($employee->password == $request->old_password) { 

                    $employee->password = $request->password ; 
                    return response()->json([
                        'employee' => $employee,
                        'role' => 'employee',
                    ], 200);
                }

                return response()->json([
                    'message' => "wrong password",
                ], 404);
            }
        if($request->role == 'commity') {
        
                $commity = Commity::where('id', $request->id)->first();
                if($commity->password == $request->old_password) { 

                    $commity->password = $request->password ; 
                    return response()->json([
                        'commity' => $commity,
                    ], 200);
                }
                
                return response()->json([
                    'message' => "wrong password",
                ], 404);



               
            }








}}