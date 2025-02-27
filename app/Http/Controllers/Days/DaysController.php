<?php

namespace App\Http\Controllers\Days;

use App\Http\Controllers\Controller;
use App\Models\Day;
use App\Traits\ApiTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DaysController extends Controller
{
    use ApiTrait;

    public function updateDate() {
        $today = Carbon::now();
        $days = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
    
        foreach ($days as $day) {
            // إذا كان اليوم هو نفسه، خليه النهارده، غير كده هاته في الاسبوع ده
            $date = ($today->format('l') === $day) 
                    ? $today->toDateString() 
                    : $today->copy()->next($day)->toDateString();
    
            Day::where('day', $day)->update([
                'date' => $date
            ]);
        }
    
        return $this->successMessage('Days updated successfully');
    }
    
    
}
