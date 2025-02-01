<?php

namespace App\Http\Controllers\Reservations;

use App\Models\User;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ReservationsController extends Controller
{
    use ApiTrait;

    public function store(Request $request)
    {
        $user = Auth::user();
        $reservations = User::find($user->id)->reservations_user;
        return $reservations;
        if ($reservations->count() == 0) 
        {
            # code...
        }
    }
}
