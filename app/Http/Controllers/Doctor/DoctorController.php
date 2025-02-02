<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\User;
use App\Models\UserDocumentation;
use App\Traits\ApiTrait;
use App\Traits\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DoctorController extends Controller
{
    use ApiTrait, Model;
    public function getAllReservations(){
        $doctor_id = Auth::user()->id;
        $reservations = Reservation::where('doctor_id', $doctor_id)->get();
        return $this->data(compact('reservations'));
    }

    public function completeReservations($id){
        $reservation = Reservation::find($id);
        if($reservation->status === 'pendding'){
            $reservation->status = 'complete';
            $reservation->save();
            return $this->successMessage('Completed Successfully');
        }
    }

    public function getUsers()
    {
        $doctor_id = Auth::user()->id;
    
        $reservations_users = Reservation::where('doctor_id', $doctor_id)
            ->get()
            ->groupBy('user_id')
            ->map(function ($reservations) {
                return $reservations->first(); 
            })
            ->values(); 
    
        return response()->json($reservations_users);
    }
    
    public function getUser($id){
        $doctor_id = Auth::user()->id;
        $documentation = UserDocumentation::where('user_id', $id)
                         ->with('userDocsImages')->get();
        return $this->data(compact('documentation'));
    }

    public function updateDocs(Request $request, $id){
        $documentation = UserDocumentation::where('id', $id)
        ->with('userDocsImages')->first();
        DB::beginTransaction();
        $documentation->update([
            'type' => $request->type,
            'desc' => $request->desc,
        ]);
        if($request->hasFile('image')){
            $this->deleteDocsImages($documentation);
            $this->storeImages($request, $documentation);
        }
        DB::commit();
        return $this->successMessage('Updated Successfully');
        DB::rollBack();
    }

    public function storeDocs(Request $request){
        $doctor_id = Auth::user()->id;
        $user_id = $request->user_id;
        $documentation = UserDocumentation::create([
            'type' => $request->type,
            'desc' => $request->desc,
            'doctor_id' => $doctor_id,
            'user_id' => $user_id
        ]);
        if($request->hasFile('image')){
            $this->storeImages($request, $documentation);
        }
        return $this->successMessage('Created Successfully', 201);
    }
}
