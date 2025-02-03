<?php

namespace App\Http\Controllers\Reservations;

use App\Models\User;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Reservations\StoreReservation;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;

class ReservationsController extends Controller
{
    use ApiTrait;

    public function index(){
        $user_id = Auth::user()->id;
        $reservations = Reservation::where('user_id', $user_id)->get();
        return $this->data(compact('reservations'));
    }

    public function store(StoreReservation $request)
    {
        // send notifications to doctor 
        $user = Auth::user();
        $doctor_id = $request->doctor_id;
        $reservation = Reservation::where('user_id', $user->id)
                       ->where('doctor_id', $doctor_id)->first();
        if (!is_null($reservation)){
            Reservation::create([
                'review' => '1',
                'status' => $request->status,
                
                'appointment_id' => $request->appointment_id,
                'doctor_id' => $doctor_id,
                'user_id' => $user->id,
                'feese_id' => $request->feese_id
            ]);
        }else {
            Reservation::create([
                'review' => '0',
                'status' => $request->status,

                'appointment_id' => $request->appointment_id,
                'doctor_id' => $doctor_id,
                'user_id' => $user->id,
                'feese_id' => $request->feese_id
            ]);
        }
        return $this->successMessage('Created Successfully', 201);
    }


    public function cancel($id){
        $reservation = Reservation::find($id);
        if($reservation->status === 'pendding'){
            $reservation->status = 'cancel';
            $reservation->save();
            return $this->data(compact('reservation'));
        }        
    }

}
