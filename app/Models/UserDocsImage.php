<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDocsImage extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function user_doc()
    {
        return $this->belongsTo(UserDocumentation::class);
    }

}
