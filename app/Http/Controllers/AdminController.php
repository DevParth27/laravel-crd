<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ExcelFile;
use App\Models\UserPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        return view('admin.index');
    }
    
    /**
     * Display the user management page.
     */
    public function users()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }
    
    /**
     * Get users data for DataTables.
     */
    public function getUsersData()
    {
        $users = User::all();
        
        return DataTables::of($users)
            ->addColumn('actions', function ($user) {
                $editUrl = route('admin.users.edit', $user->id);
                $deleteUrl = route('admin.users.destroy', $user->id);
                
                $buttons = '<a href="'.$editUrl.'" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i> Edit</a>';
                
                // Don't allow deleting self or the main admin
                if ($user->id !== auth()->id() && $user->email !== 'admin@example.com') {
                    $buttons .= ' <form action="'.$deleteUrl.'" method="POST" class="d-inline" onsubmit="return confirm(\'Delete this user?\')">'.
                        csrf_field().method_field('DELETE').
                        '<button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Delete</button></form>';
                }
                
                return $buttons;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
    
    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }
    
    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,user',
        ]);
        
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);
        
        return redirect()->route('admin.users')->with('success', 'User created successfully');
    }
    
    /**
     * Show the form for editing a user.
     */
    public function edit(User $user)
    {
        $excelFiles = ExcelFile::all();
        
        // Get user's permissions for each file
        $permissions = [];
        foreach ($excelFiles as $file) {
            $permission = UserPermission::where('user_id', $user->id)
                                      ->where('excel_file_id', $file->id)
                                      ->first();
            
            $permissions[$file->id] = [
                'can_view' => $permission ? $permission->can_view : false,
                'can_delete' => $permission ? $permission->can_delete : false,
            ];
        }
        
        return view('admin.users.edit', compact('user', 'excelFiles', 'permissions'));
    }
    
    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'role' => 'required|in:admin,user',
            'password' => 'nullable|string|min:8',
        ]);
        
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];
        
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }
        
        $user->update($userData);
        
        // Update file permissions
        $filePermissions = $request->input('permissions', []);
        
        foreach ($filePermissions as $fileId => $permissions) {
            UserPermission::updateOrCreate(
                ['user_id' => $user->id, 'excel_file_id' => $fileId],
                [
                    'can_view' => isset($permissions['can_view']),
                    'can_delete' => isset($permissions['can_delete']),
                ]
            );
        }
        
        return redirect()->route('admin.users')->with('success', 'User updated successfully');
    }
    
    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        // Prevent deleting self or the main admin
        if ($user->id === auth()->id() || $user->email === 'admin@example.com') {
            return redirect()->route('admin.users')->with('error', 'Cannot delete this user');
        }
        
        $user->delete();
        
        return redirect()->route('admin.users')->with('success', 'User deleted successfully');
    }
}