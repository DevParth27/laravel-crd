<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExcelData extends Model
{
    protected $fillable = ['file_name', 'row_number', 'row_data'];
}
