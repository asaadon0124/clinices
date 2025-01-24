<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\RegisterRequest;
use App\Traits\ApiTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class UsersController extends Controller
{

    use ApiTrait;
    public function register(RegisterRequest $request)
    {
    

        $data = $this->insertData($request);
       
        $user = User::create($data);
        $token = $user->createToken('token')->plainTextToken;
        return $this->data(compact('user','token'),'',201);
    }


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
}
