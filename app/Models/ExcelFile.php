<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ExcelFile extends Model
{
    protected $fillable = ['file_name', 'original_name', 'headers'];
    
    /**
     * Get the users that have permissions for this file.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_permissions')
                    ->withPivot('can_view', 'can_delete')
                    ->withTimestamps();
    }
}
