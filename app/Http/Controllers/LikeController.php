<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pio;
use Illuminate\Support\Facades\Redirect;

class LikeController extends Controller
{
    public function store(Request $request, $pio_id){
        $user = auth()->user();

        $pios = Pio::findOrFail($pio_id);

        if ($pios->isLikedBy($user)) {
            $pios->removeLike($user);
            $likesCount = $pios->likes()->count();
            return Redirect::back()->with('pios', 'likesCount'); // Retorna la vista con el botón actualizado
        } else {
            $pios->addLike($user);
            $likesCount = $pios->likes()->count();
            return Redirect::back()->with('pios', 'likesCount'); // Retorna la vista con el botón actualizado
        }
    }
}
