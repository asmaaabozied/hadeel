<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sheet extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'week_start_date',
        'week_end_date',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('production', 'consumption', 'note', 'note_type', 'admin_id','type')
            ->withTimestamps()->where('admin_id', auth()->id());
    }

    public function allusers()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('production', 'consumption', 'note', 'note_type', 'admin_id','type')
            ->withTimestamps();
    }

}
