<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\ForgetPasswordRequest;
use App\Http\Requests\Users\LoginRequest;
use App\Http\Requests\Users\RegisterRequest;
use App\Mail\SendCode;
use App\Models\User;
use App\Traits\ApiTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    use ApiTrait;
    private function insertData($request)
    {
        $age = Carbon::parse($request->birth_date)->age;
        return 
        [
            'first_name'    => $request->first_name,
            'last_name'     => $request->last_name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'age'           => $age, 
            'phone'         => $request->phone
        ];
    }
    public function register(RegisterRequest $request){
        $data = $this->insertData($request);
        $user = User::create($data);
        $token = $user->createToken('token')->plainTextToken;
        return $this->data(compact('user','token'),'',201);
    }

    public function login(LoginRequest $request)
    {
        // return  $request;
        $user = User::where('email', $request->email)->first();
        if(!Hash::check($request->password, $user->password)){
            return $this->errorsMessage(['error' => 'Email Or Password Is Not Valid']);
        }
        if(is_null($user->email_verified_at)){
            $token = $user->createToken('token')->plainTextToken;
            return $this->data(compact('user', 'token'), 'You Must Verify Your Email', 403);
        }
        $user->status = 'active';
        $user->save();
        $token = $user->createToken('token')->plainTextToken;
        $user->image_url = asset('images/users/'.$user->image);
        return $this->data(compact('user','token'), 'Login Suuccessfully');
    }

    public function logout(){
        $user = Auth::user();
        $user->currentAccessToken()->delete();
        $user_db = User::find($user->id);
        $user_db->status = 'un_active';
        $user_db->save();
        return $this->successMessage('Logout Successfully');
    }
    
    public function forgetPassword(ForgetPasswordRequest $request){
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();
        $user->image_url = asset('images/users/'.$user->image);
        $token = $user->createToken('token')->plainTextToken;
        return $this->data(compact('user', 'token'));
    }  
    
    public function test()
    {
        return __('message.name');
    }
}
