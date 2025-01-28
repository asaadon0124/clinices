<?php

namespace App\Http\Controllers\Appointments;

use App\Models\User;
use App\Traits\ApiTrait;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Appointments\AppointmentsRequest;

class AppointmentsController extends Controller
{
    use ApiTrait;

    public function index()
    {
        $user = Auth::user();
       
        if($user->role === 'admin')
        {
            $appointment = Appointment::all();

        } else if($user->role === 'user')
        {
            $appointment = User::find($user->id)->appointments;
        }

        if($appointment->isEmpty())
        {
            return $this->successMessage('No Data Here');
        }
        return $this->data(compact('appointment'));
    }

    public function store(AppointmentsRequest $request){
        $user_id = Auth::user()->id;
        Appointment::create(
        [
            'date' => Carbon::createFromFormat('Y-m-d', $request->date)
            ->format('Y-m-d H:i:s'),
            'user_id' => $user_id
        ]);
        return $this->successMessage('Created Successfully');
    }

    public function update(AppointmentsRequest $request, $id){
        $appointment = Appointment::find($id);
        $appointment->date =  Carbon::createFromFormat('Y-m-d', $request->date)
        ->format('Y-m-d H:i:s');
        $appointment->save();
        return $this->successMessage('Updated Successfully');

    }
    


    public function delete($id)
    {
        $appointment = Appointment::find($id);
        $appointment->delete();
        return $this->successMessage('Deleted Successfully');
    }

}
