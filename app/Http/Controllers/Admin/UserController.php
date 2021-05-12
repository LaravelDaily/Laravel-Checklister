<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('is_admin', 0)->latest()->paginate(50);

        return view('admin.users.index', compact('users'));
    }
    public function changeUserStatus($userId): RedirectResponse
    {
        $user = User::find($userId);
        $user->is_active = $user->is_active ? '0' : '1';
        $user->save();

        return back()->with('message', $user->name . ' user status updated');
    }
}
