<?php

namespace App\Http\Controllers;

use App\Models\Pio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class DashboardController extends Controller{

    public function index()
    {
        $user_rol = auth()->user()->rol_id;

        $user = User::findOrFail($user_rol);

        if ($user->rol_id == 1) {
            return view('dashboard',[
                'pios'=>Pio::with('user')->latest()->get(),
            ]);
        } elseif ($user->rol_id == 2) {
            return view('dashboardAdmin',[
                'users'=>User::with('pios')->latest()->get(),
            ]);
        }

    }
    public function update(Request $request, $id){

        $user = User::findOrFail($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->save();
        return redirect()->route('dashboard')->with('success', 'Usuario actualizado exitosamente.');
    }
    public function destroy(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('dashboard')->with('success', 'Usuario eliminado exitosamente.');
    }
}
?>
