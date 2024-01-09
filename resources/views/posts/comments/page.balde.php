<!-- resources/views/comments/page.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Komentarze do posta') }}
        </h2>
    </x-slot>

    @section('content')
        <div class="flex items-center justify-center min-h-screen">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="fixed-width bg-gray-200 dark:bg-gray-700 rounded-md p-4 mb-4">
                    <div class="font-semibold text-lg text-gray-800 dark:text-gray-200">
                        <x-nav-link :href="route('users.showProfil', ['user' => $post->user->id])">
                            {{ $post->user->name }}
                        </x-nav-link>
                    </div>
                    <div class="text-gray-700 dark:text-gray-300">{{ $post->temat }}</div>

                    @if($post->comments)
                        @foreach($post->comments as $comment)
                            <div class="bg-gray-300 p-2 mt-2 rounded">
                                @if($comment->user)
                                    <div class="font-semibold text-gray-800 dark:text-gray-200">
                                        <x-nav-link :href="route('users.showProfil', ['user' => $comment->user->id])">
                                            {{ $comment->user->name }}
                                        </x-nav-link>
                                    </div>
                                @endif
                                <div class="text-gray-700 dark:text-gray-300">{{ $comment->temat }}</div>
                                @if($comment->image_path)
                                    <img src="{{ asset('storage/' . $comment->image_path) }}" alt="Obraz" class="max-w-full rounded-md mt-2">
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    @endsection
</x-app-layout>
