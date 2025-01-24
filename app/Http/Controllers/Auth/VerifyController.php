<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class VerifyController extends Controller
{
    use ApiTrait;

    // public function sendCode(){
    //     $authanticated_user = Auth::user();
    //     $user = User::find($authanticated_user->id);

    //     $code = rand(10000,99999);
    //     $code_expired_at = date('Y-m-d', strtotime('+3 minutes'));

    //     $user->code = $code;
    //     $user->code_expired_at = $code_expired_at;
    //     $user->save();

    //     // Mail::to($authanticated_user->email)->send(new );
    // }
}
