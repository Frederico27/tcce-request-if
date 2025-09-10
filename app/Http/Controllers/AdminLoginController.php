<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminLoginController extends Controller
{
    public function logginAdmin (){
        $user = User::where('nik', 12345);
        return auth()->login($user->first());
    }
}
