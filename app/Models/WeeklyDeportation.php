<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WeeklyDeportation extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $fillable = [
        'sheet_id',
        'user_id',
        'production',
        'consumption',
        'deportation_note',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sheet()
    {
        return $this->belongsTo(Sheet::class);
    }
}
