<x-app-layout>
    <x-slot name="header">
        <div class="container">
            <h2>Edytuj Post</h2>
        </div>
    </x-slot>

    <div class="container my-custom-container"> 
        <div class="flex items-center justify-center">
            <form action="{{ route('posts.update', $post->id) }}" method="post" class="max-w-md w-full" enctype="multipart/form-data">
                @csrf
                @method('PUT') 

                <div class="mb-4">
                    <label for="Temat" class="block text-gray-700 text-sm font-bold mb-2">Temat:</label>
                    <input type="text" name="Temat" value="{{ $post->temat }}" class="form-input border rounded-md p-2 w-full h-10" required>
                </div>

                <div class="mb-4">
                    <label for="image" class="block text-gray-700 text-sm font-bold mb-2">Załaduj plik:</label>
                    @if ($post->image_path)
                        <img src="{{ asset('storage/' . $post->image_path) }}" alt="Current Image" class="max-w-full mb-2 rounded-md">
                        <div class="mb-2">
                            <input type="checkbox" name="remove_image" id="remove_image">
                            <label for="remove_image" class="text-sm text-gray-700">Usuń aktualne zdjęcie</label>
                        </div>
                    @endif
                    <input type="file" name="image" class="form-input border rounded-md p-2 w-full" accept="image/*,video/*">
                    <small class="text-gray-500">Akceptowane formaty: obraz (jpeg, png, jpg, gif) lub video (mp4)</small>
                </div>

                <div class="mb-4">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Zapisz zmiany
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>