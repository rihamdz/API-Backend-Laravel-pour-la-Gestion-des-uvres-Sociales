<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\MinhaController;
use App\Http\Controllers\SilfaContoller;
use App\Http\Controllers\MeetController;
use App\Http\Controllers\Controller;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


/////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////
Route::prefix('admin')->group(function () {

Route::post('/login', [AdminController::class, 'login']);
Route::post('/add-employee', [AdminController::class, 'AddEmployee']);
Route::post('/add-commity', [AdminController::class, 'AddCommity']);
Route::get('/show-employees', [AdminController::class, 'ShowEmployees']);
Route::get('/show-commity', [AdminController::class, 'ShowCommities']);
Route::post('/edit-employee', [AdminController::class, 'EditEmployee']);
Route::post('/edit-commity', [AdminController::class, 'EditCommity']);
Route::post('/delete-employee', [AdminController::class, 'DeleteEmployee']);
Route::post('/delete-commity', [AdminController::class, 'DeleteCommity']);
Route::get('/find-employee/{$id}', [AdminController::class, 'FindEmployee']);
Route::get('/find-commity/{$id}', [AdminController::class, 'FindCommity']);
Route::post('/import-employees', [AdminController::class, 'ImportEmployee']);
Route::post('/import-commities', [AdminController::class, 'ImportCommity']);
Route::get('/export-employees', [AdminController::class, 'ExportEmployees']);
Route::get('/export-commities', [AdminController::class, 'ExportCommities']);
});

/////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////

Route::prefix('auth-employee')->group(function () { 
    Route::post('/register', [Controller::class, 'RegisterEmployee']);
    Route::get('/confirm-email/{token}', [Controller::class, 'Email_Confirmation']);
    Route::post('/login', [Controller::class, 'LoginEmployee']);
    Route::post('/forgot-password', [Controller::class, 'ForgotPassword']);
    Route::post('/verify-otp', [Controller::class, 'VerifyOtp']);
    Route::post('/set-new-password', [Controller::class, 'SetNewPassword']);
    });

/////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////








/////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////


Route::prefix('leaders')->group(function () { 
Route::post('/add-categorie', [CategorieController::class, 'AddCategorie']);
Route::post('/edit-categorie', [CategorieController::class, 'EditCategorie']);
Route::post('/delete-categorie', [CategorieController::class, 'DeleteCategorie']);
Route::get('/show-categories', [CategorieController::class, 'ShowCategories']);
Route::post('/add-offer', [OfferController::class, 'AddOffer']);
Route::post('/edit-offer', [CategorieController::class, 'EditOffer']);
Route::get('/delete-offer/{id}', [OfferController::class, 'deleteOffer']);
Route::post('/update-offer/{id}', [OfferController::class, 'editOffer']);
Route::post('/update-status/{id}', [OfferController::class, 'updateStatus']);
Route::get('/show-meets', [MeetController::class, 'ShowMeets']);
Route::post('/add-meet', [MeetController::class, 'AddMeet']);
Route::post('/add-meet-pv', [MeetController::class, 'AddPv']);
Route::post('/consulter-meet-pv', [MeetController::class, 'ConsulerPv']);



});




/////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////


Route::get('/show-offer/{id}', [OfferController::class, 'ShowOffer']);
Route::get('/show-offer/{state}', [OfferController::class, 'ShowOfferState']);

Route::get('/show-offers', [OfferController::class, 'ShowOffers']);
Route::get('/show-offer-by-title', [OfferController::class, 'FilterByTitle']);
Route::get('/show-offer-by-categorie', [OfferController::class, 'FilterByCategorie']);
Route::post('/add-minha', [MinhaController::class, 'StoreDemande']);
Route::post('/add-silfa', [SilfaContoller::class, 'StoreDemande']);
Route::post('/show-demandes', [EmployeeController::class, 'ShowDemands']);
Route::post('/show-profile', [EmployeeController::class, 'ShowProfile']);
Route::post('/add-photo', [EmployeeController::class, 'AddPhoto']);


/////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////







































Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::apiResource('employees', EmployeeController::class);
Route::apiResource('user-roles', UserRoleController::class);


//Route::middleware(['ComissionPresedent'])->group(function () {

//});



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
    
});