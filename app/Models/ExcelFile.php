<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExcelFile extends Model
{
    protected $fillable = ['file_name', 'headers'];
}
