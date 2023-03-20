<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NavController extends Controller
{
    public function index()
    {
        $user_rol = auth()->user()->rol_id;

        $user = User::findOrFail($user_rol);


            return view('dashboardAdmin')->with('user', $user);;

    }
}
