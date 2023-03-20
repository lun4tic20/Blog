<?php


namespace App\Http\Controllers;

use App\Models\Pio;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{

    public function create(Pio $pios){
    $comments = $pios->comments()->with('user')->get();

    return view('comments.create', [
        'pios' => $pios,
        'comments' => $comments
    ]);
}

    public function store(Request $request, Pio $pios){
        $validated = $request->validate([
            'comment' => 'required',
        ]);

        $comment = new Comment;
        $comment->comment = $validated['comment'];
        $comment->user_id = auth()->id();
        $comment->pio_id = $pios->id;
        $comment->save();

        return redirect()->back()->with($pios->id);
    }

}

?>
