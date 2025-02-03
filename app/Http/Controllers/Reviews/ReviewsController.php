<?php

namespace App\Http\Controllers\Reviews;

use App\Models\Review;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Reviews\ReviewsRequest;
use App\Models\Reservation;

class ReviewsController extends Controller
{
    use ApiTrait;


    public function store(ReviewsRequest $request)
    {
        $user_id = Auth::id(); 
        $doctor_id = $request->doctor_id;
    
        $reservations = Reservation::where('user_id', $user_id)
            ->where('doctor_id', $doctor_id)
            ->get();
    
        $completedReservation = $reservations->firstWhere('status', 'complete');
    
        if ($completedReservation) {
            Review::create([
                'comment'   => $request->comment,
                'rate'      => $request->rate,
                'user_id'   => $user_id,
                'doctor_id' => $doctor_id,
            ]);
            return $this->successMessage('Created Successfully');
        } elseif ($reservations->isNotEmpty()) {
            return $this->errorsMessage(['error' => 'You Must Complete a Reservation']);
        } else {
            return $this->errorsMessage(['error' => 'You Must Have a Reservation']);
        }
    }

    public function update(Request $request, $id){
        $review = Review::find($id);
        $review->update([
            'comment' => $request->comment,
            'rate' => $request->rate
        ]);
        return $this->successMessage('Updated Successfully');
    }


    public function delete($id){
        $review = Review::find($id);
        $review->delete();
        return $this->successMessage('Deleted Successfully');
    }
}
