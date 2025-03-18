<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Day;
use App\Models\Reservation;
use App\Models\Review;
use App\Models\User;
use App\Traits\ApiTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    use ApiTrait;

    public function makeAdmin($id){
        $user = User::find($id);
        $user->role = 'admin';
        $user->save();
        return $this->successMessage('Added Admin Successfully');
    }

    public function getUsers(){
        $users = User::where('role', 'user')->get();
        return $this->data(compact('users'));
    }

    public function getUser($id){
        $user = User::where('id', $id)->with([
            'reservations_users',
            'reviews_users',
            'user_docs.userDocsImages'
            ])->first();
        return $this->data(compact('user'));
    }

    public function deleteUser($id){
        User::find($id)->delete();
        return $this->successMessage('Deleted Successfully');
    }

    public function getDoctors(){
        $doctors = User::where('role', 'doctor')->get();
        return $this->data(compact('doctors'));
    }

    public function getDoctor($id){
        $doctor = User::where('id', $id)->with([
            'reservations_doctor' => function($q) {
                $q->where('status', '!=', 'finished');
            },
            'reviews_doctors',
            'doctor_docs.userDocsImages'
            ])->first();
        return $this->data(compact('doctor'));
    }

    public function deleteReview($id){
        Review::findOrFail($id)->delete();
        return $this->successMessage('Deleted Successfully');
    }

    public function makeDoctor($id){
        $user = User::find($id);
        $user->role = 'doctor';
        $user->save();
        return $this->successMessage('Added Doctor Successfully');
    }

    public function allContentInDashboard(){
        
        $users_count_of_reservations = Reservation::all()->groupBy('user_id')->map(function($reservation) {
            return $reservation->first();
        })->count();
        
        $weakly_appointments = Day::withCount(['appointments' => function($q) {
            $q->where('status', '!=', 'active');
        }])->get();

        $weekly_earnings = Day::with(['reservations' => function($query) {
            $query->where('status', '!=', 'finished');
        }, 'reservations.feese'])
        ->orderBy('date', 'asc')
        ->get()
        ->mapWithKeys(function ($day) {
            return [$day->day => ['total_price' => $day->reservations->sum(fn($reservation) => $reservation->feese?->price ?? 0)]];
        });

        $users_count = User::where('role', 'user')->count();
        $doctors_count = User::where('role', 'doctor')->count();

        $today = Carbon::today()->toDateString();

        $today_reservations = Day::with(['reservations' => function($reservation) {
            $reservation->where('status', '!=', 'finished');
        }, 'reservations.user'])->where('date', $today)->get();

        return $this->data(compact('users_count_of_reservations', 'weakly_appointments', 'weekly_earnings', 'users_count', 'doctors_count', 'today_reservations'));
    }

}
