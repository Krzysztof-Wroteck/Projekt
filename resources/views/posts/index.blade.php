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
                    <div></div>
                    <a href="{{ route('posts.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4">
                        Dodaj nowy post
                    </a>
                </div>

                @foreach($posts as $post)
                    <div class="fixed-width bg-gray-200 dark:bg-gray-700 rounded-md p-4 mb-4">
                        <div class="flex justify-end mb-2">
                            <button class="flex items-center text-gray-600 mr-2">
                                <i class="fa-solid fa-pen">Edit</i>
                            </button>
                            <button class="flex items-center text-gray-600">
                                <i class="fa-regular fa-trash-can">Delete</i>
                            </button>
                        </div>
                        <div class="font-semibold text-lg text-gray-800 dark:text-gray-200">{{ $post->user->name }}</div>
                        <div class="text-gray-700 dark:text-gray-300">{{ $post->temat }}</div>

                        @if($post->image_path)
                            <div class="mt-4">
                                @if(pathinfo($post->image_path, PATHINFO_EXTENSION) == 'gif')
                                    <img src="{{ asset('storage/' . $post->image_path) }}" alt="GIF" class="max-w-full rounded-md">
                                @elseif(pathinfo($post->image_path, PATHINFO_EXTENSION) == 'mp4')
                                    <video width="100%" controls>
                                        <source src="{{ asset('storage/' . $post->image_path) }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                @else
                                    <img src="{{ asset('storage/' . $post->image_path) }}" alt="Obraz" class="max-w-full rounded-md">
                                @endif
                            </div>
                        @else
                            <div class="text-gray-500 mt-2">Brak obrazu</div>
                        @endif

                        <div class="flex items-center mt-4">
                            <button class="flex items-center text-gray-600 mr-2">
                                <i class="fa-regular fa-thumbs-up mr-1"></i>
                                Like
                            </button>
                            <button class="flex items-center text-gray-600 mr-2">
                                <i class="fa-regular fa-share-from-square mr-1"></i>
                                Share
                            </button>
                            <button class="flex items-center text-gray-600">
                                <i class="fa-regular fa-pen-to-square mr-1"></i>
                                Comment
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </x-app-layout>

    @push('styles')
        <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    @endpush