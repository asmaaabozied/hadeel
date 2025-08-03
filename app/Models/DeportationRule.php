<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeportationRule extends Model
{
    use HasFactory;

    protected $fillable = ['min_production', 'max_production', 'adjustment'];

}
