<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

use App\Models\Appointment;
use App\Models\User;
use App\Models\DentistSchedule;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/cpanel', function () {
    // if(Auth::check()){
    //     $user = Auth::user();
    //     return view('welcome')
    //         ->with('user', $user->only(['lname', 'fname', 'mname', 'suffix', 'role', 'remark', 'office_id']));
    // }
    return view('cpanel-login');
});





Auth::routes([
    'login' => 'false'
]);

Route::get('/load-user', function(){
    if(Auth::check()){
        return Auth::user();
    }
});


//face recognition routes
Route::get('/face-recog', [App\Http\Controllers\FaceRecogDashboardController::class, 'index']);
Route::get('/face-recog-dashboard', [App\Http\Controllers\FaceRecogDashboardController::class, 'index_face_dashboard']);


Route::get('/face-register', [App\Http\Controllers\Administrator\FaceRegisterController::class, 'index']);
//Route::post('/face-register', [App\Http\Controllers\Administrator\FaceRegisterController::class, 'store']);


Route::post('/store-descriptions', [App\Http\Controllers\Administrator\UserController::class, 'store']);
Route::get('/load-descriptions', [App\Http\Controllers\EmployeeController::class, 'loadDescriptions']);

Route::post('/store-dtr', [App\Http\Controllers\DailyTimeRecordController::class, 'storeDTR']);




Route::post('/cpanel/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);

Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout']);

Route::get('/sample',[App\Http\Controllers\SampleController::class,'index']);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/sign-up', [App\Http\Controllers\SignUpController::class, 'index']);
Route::post('/sign-up', [App\Http\Controllers\SignUpController::class, 'store']);


Route::get('/get-user/{id}', [App\Http\Controllers\OpenUserController::class, 'getUser']);






//ADDRESS
Route::get('/load-provinces', [App\Http\Controllers\AddressController::class, 'loadProvinces']);
Route::get('/load-cities', [App\Http\Controllers\AddressController::class, 'loadCities']);
Route::get('/load-barangays', [App\Http\Controllers\AddressController::class, 'loadBarangays']);


/*     ADMINSITRATOR
      */
Route::resource('/home', App\Http\Controllers\Administrator\AdminHomeController::class);



//user === account
Route::resource('/accounts', App\Http\Controllers\Administrator\UserController::class);
Route::get('/get-accounts', [App\Http\Controllers\Administrator\UserController::class, 'getUsers']);

Route::post('/account-reset-password/{id}', [App\Http\Controllers\Administrator\UserController::class, 'resetPassword']);



Route::resource('/employees', App\Http\Controllers\Administrator\AdminEmployeeController::class);
Route::get('/get-browse-employees', [App\Http\Controllers\Administrator\AdminEmployeeController::class, 'getBrowseEmployees']); //use in the ModalBrowseEmployee



Route::resource('/daily-time-records', App\Http\Controllers\Administrator\AdminDTRController::class);
Route::get('/get-daily-time-records', [App\Http\Controllers\Administrator\AdminDTRController::class, 'getDTRs']);
Route::get('/display-dtr/{id}', [App\Http\Controllers\Administrator\AdminDTRController::class, 'displayDTR']);

/*     ADMINSITRATOR          */


//MAIN PAGE
Route::get('/', [App\Http\Controllers\MainPageController::class, 'index']);



Route::get('/session', function(){
    return Session::all();
});



Route::get('/collect', function(){
    return $collection = collect([1, 2, 3]);
});


Route::get('/applogout', function(Request $req){
    \Auth::logout();
    $req->session()->invalidate();
    $req->session()->regenerateToken();


});
