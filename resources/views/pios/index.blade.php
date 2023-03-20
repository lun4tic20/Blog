<x-app-layout>
    <head>
        <script src="{{ asset('http://[::1]:5173/vendor/tinymce/tinymce/tinymce.min.js') }}"></script>
        <link rel="stylesheet" href="{{ asset('style.css') }}">
    </head>
    <form action="{{ route('pios.searchByTag') }}" method="GET">
        <input type="text" name="tag" placeholder="Buscar por etiqueta">
        <button type="submit">Buscar</button>
    </form>
    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <form action="{{ route('pios.store') }}" method="POST">
            @csrf
            <textarea name="title"
            placeholder="{{__('Título')}}"
            class="block w-full border-gray-300 focus:border-indigo-300
            focus:ring focus-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
            >{{old('title')}}</textarea><br>
            <textarea id="message" name="message"
            placeholder="{{__('¿En qué estas pensando?')}}"
            class="block w-full border-gray-300 focus:border-indigo-300
            focus:ring focus-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
            >{{old('message')}}</textarea><br>
            <textarea name="tags"
            placeholder="{{__('Etiquetas (separadas por comas)')}}"
            class="block w-full border-gray-300 focus:border-indigo-300
            focus:ring focus-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
            >{{ old('tags') }}</textarea>
            <script>
                tinymce.init({
                    selector: '#message',
                });
            </script>
            @if ($errors->any())
            <x-input-error :messages="$errors->get('title')" class="mt-2"/>
            <x-input-error :messages="$errors->get('message')" class="mt-2"/>
            <x-input-error :messages="$errors->get('tag')" class="mt-2"/>
            @endif
            <x-primary-button class="mt-4">{{__('Post')}} </x-primary-button>
        </form>
        <div class="mt-6 bg-white shadow-sm rounded-lg divide-y">
            @foreach ($pios as $pio)
            @if (!empty($pio->user->name))
            <div class="p-6 flex-column space-x-2">
                <div class="fixed-top">
                @foreach ($pio->tags as $tag)
                    <span class="tag">{{$tag->tag}}</span>
                @endforeach
                </div>
                <div class="flex-1">
                    <div class="flex justify-between items-center">
                        <div>
                            <svg class="icono" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                            </svg>
                            <span class="text-gray-700">{{$pio->user->name}}</span>
                            <span class="ml-2 text-sm text-gray-500">{{$pio->created_at->format('j M Y,g:i a')}}</span>
                            @unless ($pio->created_at->eq($pio->updated_at))
                                <small class="text-sm text-gray-600">&middot;{{__('edited')}}</small>
                            @endunless
                        </div>
                        @if ($pio->user->is(auth()->user()) || auth()->user()->rol_id === 1)
                            <x-dropdown>
                                <x-slot name="trigger">
                                    <button>
                                        <svg class="h-4 w-4 text-gray-400 " xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM12.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM18.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                                        </svg>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link href="{{route('pios.edit',$pio)}}">
                                        {{__('Edit')}}
                                    </x-dropdown-link>
                                    <form method="POST" action="{{route('pios.destroy',$pio)}}">
                                        @csrf
                                        @method('delete')
                                        <x-dropdown-link href="{{route('pios.destroy',$pio)}}" onclick="event.preventDefault(); this.closest('form').submit();">
                                            {{__('Delete')}}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        @endif

                    </div>
                        <p class="mt-4 text-xl text-black-900"><b>{{$pio->title}}</b></p>
                        <p class="mt-4 text-lg text-gray-900">{!! $pio->message !!}</p><br>
                        <div>
                        <form method="POST" action="{{ route('likes.store', $pio) }}">
                            @csrf
                            <button id="btn-like-{{ $pio->id }} btn-like" class="btn-like float-right" data-post-id="{{ $pio->id }}">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path class="like-icon" d="M12 20.195l-.695-.634C5.844 15.406 2 12.386 2 8.5 2 5.42 4.42 3 7.5 3c1.781 0 3.445.836 4.5 2.164C13.055 3.836 14.719 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.886-3.844 6.906-9.305 11.06L12 20.195z"/>
                                </svg>
                                <span class="like-count">{{ $pio->likesCount() }}</span>
                            </button>
                        </form>
                        </div>
                        <form method="POST" action="{{ route('comments.store', $pio->id)}}">
                            @csrf
                            <div>
                                <textarea name="comment" id="comment" rows="3" placeholder="Escribe un comentario" required>{{ old('comment')}}</textarea>
                                @error('comment')
                                    <div>{{$message}}</div>
                                @enderror
                            </div>
                            <div>
                                <button type="submit">Agregar Comentario</button>
                            </div>
                        </form>
                        @if(!empty($pio->comments) && count($pio->comments) > 0)
                            <h2>Comentarios:</h2>
                            <ul>
                                @foreach($pio->comments as $comment)
                                <li>
                                    <strong>{{ $comment->user->name }}:</strong>
                                    {{ $comment->comment }}
                                </li>
                                @endforeach
                            </ul>
                        @endif

                </div>
            </div>
            @endif
            @endforeach
        </div>
    </div>

</x-app-layout>
