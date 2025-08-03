<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    use HasFactory;

    protected $fillable = ['group_id','account_number', 'message'];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
    public function banks()
    {
        return $this->hasMany(Bank::class,'general_setting_id');
    }

}
