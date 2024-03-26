<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Polylines extends Model
{
    use HasFactory;

    protected $table = 'table_polylines'; //nama table

    protected $guarded = ['id']; //id tidak boleh diisi secara manual
}
