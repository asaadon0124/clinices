<?php

namespace App\Http\Controllers\Reviews;

use App\Models\Review;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Reviews\ReviewsRequest;

class ReviewsController extends Controller
{
    use ApiTrait;
    public function index()
    {
        // $reviews = Review::where('')
    }

    public function store(ReviewsRequest $request)
    {
        $user = Auth::user();

           Review::create(
            [
                'comment'   => $request->comment,
                'rate'      => $request->rate,
                'user_id'   => $user->id,
                // 'doctor_id'   => $user->id,
            ]);
        }
}
