<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Posty') }}
        </h2>
    </x-slot>

    @section('content')
        <div class="flex items-center justify-center min-h-screen">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">

                    <div class="flex items-center justify-between mb-4">
                        <div></div>
                        <form action="{{ route('posts.index') }}" method="GET">
                            <input type="text" name="query" placeholder="Wyszukaj..." class="border rounded px-2 py-1">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Szukaj
                            </button>
                        </form>
                    </div>

                    <div></div>
                    <a href="{{ route('posts.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4">
                        Dodaj nowy post
                    </a>
                </div>

                @foreach($posts as $post)
                    <div class="fixed-width bg-gray-200 dark:bg-gray-700 rounded-md p-4 mb-4">
                        <div class="flex justify-end mb-2">
                          @can('edit', $post)
    <a href="{{ route('posts.edit', ['post' => $post->id]) }}" class="flex items-center text-gray-600 mr-2">
        <i class="fa-solid fa-pen"></i>
        Edit
    </a>
@endcan

                            @if (auth()->check() && (auth()->user()->isAdmin() || auth()->user()->id === $post->user_id))
    <button class="flex items-center text-gray-600 delete" type="button" data-id="{{ $post->id }}" data-user-id="{{ auth()->user()->id }}">
        @csrf
        <i class="fa-regular fa-trash-can"></i>Delete
    </button>
@endif

                        </div>
                        <div class="font-semibold text-lg text-gray-800 dark:text-gray-200">
                            <x-nav-link :href="route('users.showProfil', ['user' => $post->user->id])">
                                {{ $post->user->name }}
                                @if($post->created_at != $post->updated_at)
                                    (edited)
                                @endif
                            </x-nav-link>
                        </div>
                        <div class="text-gray-700 dark:text-gray-300">{{ $post->temat }}</div>

                        @if($post->image_path)
                            <div class="mt-4">
                                @if(pathinfo($post->image_path, PATHINFO_EXTENSION) == 'gif')
                                    <img src="{{ asset('storage/' . $post->image_path) }}" alt="GIF" class="max-w-full rounded-md">
                                @elseif(pathinfo($post->image_path, PATHINFO_EXTENSION) == 'mp4')
                                    <video width="100%" controls>
                                        <source src="{{ asset('storage/' . $post->image_path) }}" type="video/mp4">
                                    </video>
                                @else
                                    <img src="{{ asset('storage/' . $post->image_path) }}" alt="Obraz" class="max-w-full rounded-md">
                                @endif
                            </div>
                        @else
                            <div class="text-gray-500 mt-2">Brak obrazu</div>
                        @endif

                        <div class="flex items-center mt-4">
                        <form class="like-form" action="{{ route('posts.like.api', $post->id) }}" method="post" data-post-id="{{ $post->id }}">
    @csrf
    <button type="submit" class="flex items-center text-gray-600 mr-2 like-button">
        @if(Auth::user()->likes()->where('post_id', $post->id)->exists())
            <i class="fa-solid fa-thumbs-up mr-1"></i>
        @else
            <i class="fa-regular fa-thumbs-up mr-1"></i>
        @endif
        <span class="text-gray-600 mr-2 likes-count">{{ $post->likesCount() }} likes</span>
    </button> 
</form>


                            <form action="{{ route('posts.shere', $post->id) }}" method="post">
                                @csrf
                                <button type="submit" class="flex items-center text-gray-600 mr-2 shere" data-id="{{ $post->id }}">
                                    @if(Auth::user()->sheres()->where('post_id', $post->id)->exists())
                                        <i class="fa-solid fa-share-from-square"></i>
                                    @else
                                        <i class="fa-regular fa-share-from-square"></i>
                                    @endif
                                    <span class="text-gray-600 mr-2 sheres-count">{{ $post->sheresCount() }} sheres</span>
                                </button>
                            </form>

                            <button class="flex items-center text-gray-600">
                                <i class="fa-regular fa-pen-to-square mr-1"></i>
                                <a href="{{ route('comments.index', $post->id) }}" class="text-gray-600">{{ $post->comments()->count() }} Comment</a>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endsection

    @section('javascript')
        <script src="{{ asset('js/delete.js') }}">
        <script src="{{ asset('js/like.js') }}">
        <script src="{{ asset('js/shere.js') }}">
    @endsection
</x-app-layout>
