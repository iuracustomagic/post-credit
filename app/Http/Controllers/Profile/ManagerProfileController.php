<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagerProfileController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $user = User::findOrFail( $userId);
        $managers = User::where('manager_id', $userId)->get();
//        dd($managers);

        return view('profile.manager_profile', compact('user', 'managers'));
    }
}
