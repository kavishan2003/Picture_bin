<div>
    <div>
        
        {{-- Session Messages --}}
        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 "
                role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif


        @if ($images->isEmpty())
            <p class="text-gray-600 dark:text-gray-400">No images uploaded yet. Start by uploading some!</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-8 mx-5">
                @foreach ($images as $image)
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg shadow-[0_20px_30px_rgba(0,0,0,0.8)] overflow-hidden transform transition duration-300 hover:scale-105">
                        <img src="{{ $image->image_path }}" alt="{{ $image->original_name }}"
                            class="w-full h-48 object-cover">
                        <div class="p-4">
                            <p class="text-lg font-semibold truncate text-gray-800 dark:text-white">
                                {{ $image->original_name }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ round($image->size / 1024 / 1024, 2) }} MB</p>
                            <button wire:click="deleteImage({{ $image->id }})"
                                onclick="confirm('Are you sure you want to delete this image?') || event.stopImmediatePropagation()"
                                class="mt-3 bg-red-500 hover:bg-red-600 text-white text-sm py-1 px-3 rounded-md transition duration-200 ease-in-out">
                                Delete
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
            {{-- If you use pagination, add: --}}
            {{-- <div class="mt-8">
            {{ $images->links() }}
        </div> --}}
        @endif
    </div>




</div>
