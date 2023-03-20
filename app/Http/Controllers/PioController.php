<?php

namespace App\Http\Controllers;

use App\Models\Pio;
use Illuminate\Http\Request;
use App\Models\Tag;
use App\Models\PostHasTag;
use Illuminate\Support\Facades\Redirect;

class PioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd(Pio::with('user')->latest()->get());
        // return "Hola Pio";
        return view('pios.index',[
            'pios'=>Pio::with('user')->latest()->get(),
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Pio $pio)
    {
        $request->validate([
            'title' => 'required|max:255',
            'message' => 'required',
            'tags' => 'nullable|string',
        ]);

        // 1. Obtener los tags separados por comas del request
        $tags = $request->input('tags');

        // 2. Convertir la cadena de tags en un arreglo usando la función `explode()`
        $tagArray = explode(',', $tags);

        // 3. Crear el post y guardar la instancia del modelo en una variable
        $pios = new Pio;
        $pios->title = $request->input('title');
        $pios->message = $request->input('message');
        $pios->user_id = auth()->user()->id;
        $pios->save();

        // 4. Iterar sobre el arreglo de tags y para cada tag, crear una instancia del modelo `Tag`,
        // guardarla en la base de datos y obtener su ID
        foreach ($tagArray as $tagName) {
            $tagName = trim($tagName); // Remove any whitespace around the tag name
            if (!empty($tagName)) { // Make sure the tag name is not empty
                $tag = new Tag;
                $tag->tag = $tagName;
                $tag->save();
                $tagId = $tag->id;

                // 5. Crear una nueva instancia del modelo `PostHasTag` y guardarla en la base de datos,
                // utilizando el ID del post y el ID del tag
                $postHasTag = new PostHasTag;
                $postHasTag->pio_id = $pios->id;
                $postHasTag->tag_id = $tagId;
                $postHasTag->save();
            }
        }

        return redirect()->back();

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pio  $pio
     * @return \Illuminate\Http\Response
     */
    public function show(Pio $pio)
    {
        $pio = $pio->load('comments');
        return view('pios.index', [
            'pio' => $pio,
        ]);
        $tags = $pio->tags;
        return view('pio.index');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pio  $pio
     * @return \Illuminate\Http\Response
     */
    public function edit(Pio $pio)
    {
        $this->authorize('update',$pio);
        return view('pios.edit',[
            'pio'=>$pio,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pio  $pio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pio $pio){
    $user = auth()->user();

    $this->authorize('update', [$pio, $user]);

    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'message' => 'required|string|max:255',
    ]);

    $pio->update($validated);

    return redirect(route('pios.index'));
}


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pio  $pio
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pio $pio)
    {
        $this->authorize('delete',$pio);
        $pio->delete();
        return redirect(route('pios.index'));
    }

    public function searchByTag(Request $request)
{
    $tag = $request->input('tag');

    // Validar que se haya ingresado una etiqueta para buscar
    $request->validate([
        'tag' => 'required'
    ]);

    // Realizar la búsqueda por etiqueta y obtener los pios que contienen la etiqueta
    $pios = Pio::whereHas('tags', function ($query) use ($tag) {
        $query->where('tag', 'like', '%' . $tag . '%');
    })->orderByDesc('created_at')->get();

    // Retornar la vista de pios con los resultados de la búsqueda
    return view('pios.index', ['pios' => $pios]);
}

}
