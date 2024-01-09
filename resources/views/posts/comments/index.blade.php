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

                    <form action="{{ route('comments.store', $post->id) }}" method="post" class="mt-4" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label for="temat" class="block text-gray-700 text-sm font-bold mb-2">Treść komentarza:</label>
                            <textarea name="temat" id="temat" rows="3" class="border rounded w-full px-3 py-2"></textarea>
                        </div>
                        <div class="mb-4">
                            <label for="image" class="block text-gray-700 text-sm font-bold mb-2">Załącz obraz:</label>
                            <input type="file" name="image" id="image" class="border rounded w-full px-3 py-2">
                        </div>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Dodaj komentarz
                        </button>
                    </form>

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
                                    <img src="{{ asset('storage/' . $comment->image_path) }}" alt="Obraz komentarza" class="max-w-full mt-2 rounded-md">
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    @endsection
</x-app-layout>
