<?php
namespace App\Policies;

use App\Models\User;
use App\Models\ExcelFile;
use App\Models\UserPermission;

class ExcelFilePolicy
{
    public function view(User $user, ExcelFile $file)
    {
        // Admin can always view
        if ($user->isAdmin()) {
            return true;
        }
        
        // Check if user has specific permission to view this file
        $permission = UserPermission::where('user_id', $user->id)
                                   ->where('excel_file_id', $file->id)
                                   ->first();
                                   
        return $permission ? $permission->can_view : false;
    }

    public function delete(User $user, ExcelFile $file)
    {
        // Admin can always delete
        if ($user->isAdmin()) {
            return true;
        }
        
        // Check if user has specific permission to delete this file
        $permission = UserPermission::where('user_id', $user->id)
                                   ->where('excel_file_id', $file->id)
                                   ->first();
                                   
        return $permission ? $permission->can_delete : false;
    }
}
