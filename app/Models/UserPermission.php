<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPermission extends Model
{
    protected $fillable = [
        'user_id',
        'excel_file_id',
        'can_view',
        'can_delete'
    ];

    /**
     * Get the user that owns the permission.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the excel file that the permission is for.
     */
    public function excelFile(): BelongsTo
    {
        return $this->belongsTo(ExcelFile::class);
    }
}