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

Route::get('/face-recog', [App\Http\Controllers\FaceRecogDashboardController::class, 'index']);
Route::get('/face-recog-dashboard', [App\Http\Controllers\FaceRecogDashboardController::class, 'index_face_dashboard']);


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
Route::resource('/cpanel/home', App\Http\Controllers\Administrator\AdminHomeController::class);


Route::resource('/cpanel/users', App\Http\Controllers\Administrator\UserController::class);
Route::get('/cpanel/get-users', [App\Http\Controllers\Administrator\UserController::class, 'getUsers']);


Route::post('/user-reset-password/{id}', [App\Http\Controllers\Administrator\UserController::class, 'resetPassword']);


/*     ADMINSITRATOR          */


//MAIN PAGE
Route::get('/', [App\Http\Controllers\MainPageController::class, 'index']);





Route::get('/session', function(){
    return Session::all();
});


Route::get('/before', function(){
    //return Session::all();


    $beforeDay = date('Y-m-d H:i', strtotime('+24 hour', strtotime(date('Y-m-d H:i'))));

    $data = \DB::table('appointments')
        ->where('appoint_date', date('Y-m-d', strtotime($beforeDay)))
        ->where('appoint_time', date('H:i', strtotime($beforeDay)))
        ->where('is_notify', 0)
        ->get();

    foreach($data as $i){

        $user = User::find($i->user_id);

        $msg = 'Hi '.$user->lname . ', ' . $user->fname . ', this is just a reminder that you have an appointment tomorrow. Your ref no. is: ' . $i->qr_code . '.';
        try{
            Http::withHeaders([
                'Content-Type' => 'text/plain'
            ])->post('http://'. env('IP_SMS_GATEWAY') .'/services/api/messaging?Message='.$msg.'&To='.$user->contact_no.'&Slot=1', []);
        }catch(Exception $e){} //just hide the error

        $appoint = Appointment::find($i->appointment_id);
        $appoint->is_notify = 1;
        $appoint->save();
    }

    //$beforeDay = date($today, strtotime('-1 day'));
    return $data;
});




Route::get('/collect', function(){
    return $collection = collect([1, 2, 3]);
});


Route::get('/applogout', function(Request $req){
    \Auth::logout();
    $req->session()->invalidate();
    $req->session()->regenerateToken();


});
