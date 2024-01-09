<x-app-layout>
    <x-slot name="header">
        <div class="container">
            <h2>Nowy Post</h2>
        </div>
    </x-slot>

    <div class="container my-custom-container"> 
        <div class="flex items-center justify-center">
            <form action="{{ route('posts.store') }}" method="post" class="max-w-md w-full" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="Temat" class="block text-gray-700 text-sm font-bold mb-2">Temat:</label>
                    <input type="text" name="Temat" class="form-input border rounded-md p-2 w-full h-10" required>
                </div>

                <div class="mb-4">
                    <label for="image" class="block text-gray-700 text-sm font-bold mb-2">Załaduj plik:</label>
                    <input type="file" name="image" class="form-input border rounded-md p-2 w-full" accept="image/*,video/*">
                    <small class="text-gray-500">Akceptowane formaty: obraz (jpeg, png, jpg, gif)</small>
                </div>

                <div class="mb-4">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Dodaj Post
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>