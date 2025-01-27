<?php

namespace App\Http\Controllers\Feeses;

use App\Http\Controllers\Controller;
use App\Http\Requests\Fesses\StoreRequest;
use App\Models\Feese;
use App\Models\User;
use App\Traits\ApiTrait;
use FFI;
use Illuminate\Support\Facades\Auth;

class FeesesController extends Controller
{
    use ApiTrait;

    public function index(){
        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        if($user->role === 'admin'){
            $feeses = Feese::all();
        } else if($user->role === 'doctor'){
            $feeses = User::find($user_id)->feese;
        }
        if($feeses->isEmpty()){
            return $this->successMessage('No Data Here');
        }
        return $this->data(compact('feeses'));
    }

    public function store(StoreRequest $request){
        $user_id = Auth::user()->id;
        $data = Feese::create([
            'price' => $request->price,
            'user_id' => $user_id
        ]);
        return $this->successMessage('Created Successfully');
    }

    public function update(StoreRequest $request, $id){
        $fesse = Feese::find($id);
        $fesse->price = $request->price;
        $fesse->save();
        return $this->data(compact('fesse'));
    }

}
