<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiTrait;
use App\Models\Specializion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SpecializionsRequest;

class SpecializionsController extends Controller
{

    use ApiTrait;
    public function index()
    {

        $lang =  app()->getLocale();
        $auth_user = Auth::user();

        if ($auth_user->role == 'admin') 
        {
            $specializions = Specializion::all();

        }elseif($auth_user->role == 'doctor')
        {
            $specializions = User::find($auth_user->id)->specializion;
        }

        if ($specializions->isEmpty()) 
        {
            return $this->errorsMessage(['error' => 'no data here']);
        }
        
        return $this->data(compact('specializions'));
    }


    public function store(SpecializionsRequest $request)
    {
        // return  Auth::user()->id;
        
        $create = Specializion::create(
            [
                'name_ar'       => $request->name_ar,
                'name_en'       => $request->name_en,
                'created_by'    => Auth::user()->id,
            ]);
        return $this->data(compact('create'));
    }

    public function update(Request $request,$id)
    {
        
    }
}
