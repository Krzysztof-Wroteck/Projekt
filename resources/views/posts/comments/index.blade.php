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

                        <form action="{{ route('posts.comments.store', $post->id) }}" method="post" class="mt-4" enctype="multipart/form-data">                        @csrf
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
                <div class="flex justify-end mb-2">
                @can('edit', $comment)
    <a href="{{ route('comments.edit', ['comment' => $comment->id]) }}" class="flex items-center text-gray-600 mr-2">
        <i class="fa-solid fa-pen"></i>
        Edit
    </a>
@endcan


                            @if(Auth::check() && (Auth::user()->isAdmin() || Auth::user()->id === $comment->user_id))
    <button class="flex items-center text-gray-600 delete" data-post-id="{{ $post->id }}" data-comment-id="{{ $comment->id }}" data-type="comment">
        <i class="fa-regular fa-trash-can"></i> Usuń
    </button>
@endif


                        </div>
                    <x-nav-link :href="route('users.showProfil', ['user' => $comment->user->id])">
                        {{ $comment->user->name }}
                        @if($comment->created_at != $comment->updated_at)
            (edited)
        @endif
                    </x-nav-link>
                </div>
            @endif
            <div class="text-gray-700 dark:text-gray-300">{{ $comment->temat }}</div>
            @if($comment->image_path)
                <img src="{{ asset('storage/' . $comment->image_path) }}" alt="Obraz komentarza" class="max-w-full mt-2 rounded-md">
            @endif
        </div>
        <div class="flex items-center mt-4">
        <form action="{{ route('comments.like', $comment->id) }}" method="post">
                @csrf
    <button type="submit" class="flex items-center text-gray-600 mr-2">
        @if(Auth::user()->likes()->where('comment_id', $comment->id)->exists())
            <i class="fa-solid fa-thumbs-up mr-1"></i>
        @else
            <i class="fa-regular fa-thumbs-up mr-1"></i>
        @endif
        <span class="text-gray-600 mr-2">{{ $comment->likesCount() }} polubień</span>
    </button>
</form>
                            </div>

    @endforeach
@endif

                </div>
            </div>
        </div>
    @endsection



    @section('javascript')
    
    <script src="{{ asset('js/deleteCom.js') }}">
    @endsection

</x-app-layout>
