<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('is_admin', 0)->latest()->paginate(50);

        return view('admin.users.index', compact('users'));
    }

    public function toggle_free_access(User $user)
    {
        $user->update(['has_free_access' => ((int)$user->has_free_access + 1) % 2]);

        return redirect()->route('admin.users.index')->with('message', __('Operation successful.'));
    }
}
