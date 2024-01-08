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
                        <a href="{{ route('posts.edit', $post->id) }}" class="flex items-center text-gray-600 mr-2">
                       <i class="fa-solid fa-pen"></i>
                              Edit
                         </a>

                            <button class="flex items-center text-gray-600 delete" data-id="{{ $post->id }}">
                                <i class="fa-regular fa-trash-can">Delete</i>
                            </button>
                        </div>
                        <div class="font-semibold text-lg text-gray-800 dark:text-gray-200">

                        <x-nav-link :href="route('users.posts', ['user' => $post->user->id])">
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
                                     Nie dziala         
                                                               </video>
                                @else
                                    <img src="{{ asset('storage/' . $post->image_path) }}" alt="Obraz" class="max-w-full rounded-md">
                                @endif
                            </div>
                        @else
                            <div class="text-gray-500 mt-2">Brak obrazu</div>
                        @endif

                        <div class="flex items-center mt-4">
              <form action="{{ route('posts.like', $post->id) }}" method="post">
                      @csrf
                     <button type="submit" class="flex items-center text-gray-600 mr-2">
                   @if(Auth::user()->likes()->where('post_id', $post->id)->exists())
                        <i class="fa-solid fa-thumbs-up mr-1"></i>
                        @else
                        <i class="fa-regular fa-thumbs-up mr-1"></i>
                            @endif
                            <span class="text-gray-600 mr-2">{{ $post->likesCount() }} likes</span>
                                 </button>
                         </form>
                         <form action="{{ route('posts.shere', $post->id) }}" method="post">
                      @csrf
                     <button type="submit" class="flex items-center text-gray-600 mr-2">
                   @if(Auth::user()->sheres()->where('post_id', $post->id)->exists())
                   <i class="fa-solid fa-share-from-square"></i>
                        @else
                        <i class="fa-regular fa-share-from-square"></i></i>
                            @endif
                            <span class="text-gray-600 mr-2">{{ $post->sheresCount() }} sheres</span>
                                 </button>
                         </form>
                            <button class="flex items-center text-gray-600">
                                <i class="fa-regular fa-pen-to-square mr-1"></i>
                                Comment
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endsection



        @section('javascript')
        $(document).ready(function() {
    $('.delete').on('click', function() {
        const postId = $(this).data('id');
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: "btn btn-success",
                cancelButton: "btn btn-danger"
            },
            buttonsStyling: false
        });

        swalWithBootstrapButtons.fire({
            title: "Jesteś pewny, że chcesz usunąć ten post?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Tak",
            cancelButtonText: "Nie",
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    method: "DELETE",
                    url: 'http://127.0.0.1:8000/posts/list/' + postId, 
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                }).done(function(data) {
                    if (data.status === 'success') {
                        window.location.reload();
                    } else {
                        Swal.fire("Error", "Wystąpił błąd podczas usuwania.", "error");
                    }
                }).fail(function(data) {
                    Swal.fire("Error", data.responseJSON.message, data.responseJSON.status);
                });
            }
        });
    });
});

        @endsection


    </x-app-layout>

