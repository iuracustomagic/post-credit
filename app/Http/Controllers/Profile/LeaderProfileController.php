<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaderProfileController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $user = User::findOrFail( $userId);

        return view('profile.leader_profile', compact('user'));
    }
}
