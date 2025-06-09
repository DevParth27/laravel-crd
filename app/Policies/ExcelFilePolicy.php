<?php
namespace App\Policies;

use App\Models\User;
use App\Models\ExcelFile;

class ExcelFilePolicy
{
    public function view(User $user, ExcelFile $file)
    {
        return true;
    }

    public function delete(User $user, ExcelFile $file)
    {
        return $user->role === 'admin'; 
    }
}
