<x-app-layout>
    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <form method="POST" action="{{route('pios.update',$pio)}}">
            @csrf
            @method('patch')
            <textarea name="title"
            placeholder="{{__('Título')}}"
            class="block w-full border-gray-300 focus:border-indigo-300
            focus:ring focus-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
            >{{old("title",$pio->title)}}
            </textarea><br>
            <textarea id="message" name="message"
            placeholder="{{__('¿En qué estas pensando?')}}"
            class="block w-full border-gray-300 focus:border-indigo-300
            focus:ring focus-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
            >{{ old("message",$pio->message)}}
            </textarea><br>
            <x-input-error :messages="$errors->get('title')" class="mt-2"/>
            <x-input-error :messages="$errors->get('message')" class="mt-2"/>
            <div class="mt-4 space-x-2">
                <x-primary-button>{{__('Save')}}</x-primary-button>
                <a href="{{ url()->previous() }}">{{__('Cancel')}}</a>
            </div>
        </form>
    </div>
</x-app-layout>
