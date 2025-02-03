<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class UsersController extends Controller
{
    use ApiTrait;

    public function getDoctors(){
        $doctors = User::where('role', 'doctor')->get();
        return $this->data(compact('doctors'));
    }

    public function showDoctor($id){
        $doctor = User::where('id', $id)->with('reviews_doctors')->with('appointments')->first();
        return $this->data(compact('doctor'));
    }

}
