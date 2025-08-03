<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SheetUserChange extends Model
{
    use HasFactory;

     protected $fillable = [
        'sheet_id',
        'user_id',
        'field',
        'old_value',
        'new_value',
        'reverted',
        'changed_by',
         'admin_id'
    ];

    public function sheet()
    {
        return $this->belongsTo(Sheet::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
