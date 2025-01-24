<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\VerifyCode;
use App\Mail\SendCode;
use App\Models\User;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class VerifyController extends Controller
{
    use ApiTrait;

    public function sendCode(Request $request){
        $token = $request->header('Authorization');
        $token = str_replace('Bearer ', '', $token);
        $authanticated_user = Auth::user();
        $user = User::find($authanticated_user->id);
        // generate code and code_expired_at 
        $code = rand(10000,99999);
        $code_expired_at = now()->addMinutes(3);
        // update code and code_expired_at in db
        $user->code = $code;
        $user->code_expired_at = $code_expired_at;
        $user->save();
        // send mail to user authantication
        $stringCode = (string) $code;
        Mail::to($authanticated_user->email)->send(new SendCode($stringCode, $authanticated_user->first_name, $authanticated_user->last_name));
        return $this->data(compact('code', 'token'),'Send Code Successfully');
    }
    public function checkCode(VerifyCode $request){
        $token = $request->header('Authorization');
        $token = str_replace('Bearer ', '', $token);
        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        $now = now();
        if($request->code != $user->code){
            return $this->errorsMessage(['error' => 'Code Is Invalid']);
        }
        if($now > $user->code_expired_at){
            return $this->errorsMessage(['error' => 'Code Is Expired, Please Resend Code Again']);
        }
        $user->status = 'active';
        $user->email_verified_at = $now;
        $user->save();
        return $this->data(compact('user', 'token'), 'Verify Successfully');
    }
}
