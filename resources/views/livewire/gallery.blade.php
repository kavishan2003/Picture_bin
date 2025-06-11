<div>
    <div>

        {{-- Session Messages --}}
        @if (session()->has('message'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 6000)" x-show="show" x-transition:leave.duration.500ms
                class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 "
                role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        @endif

        @if (session()->has('error'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 6000)" x-show="show" x-transition:leave.duration.500ms
                class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif


        @if ($images->isEmpty())
            <div
                class="flex flex-col items-center justify-center p-10 bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700 min-h-[200px]">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-16 h-16 text-gray-400 dark:text-gray-600 mb-4">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-5.664-1.818 2.909 2.909m-7.886-3.818H15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 15 4.5H9.75A2.25 2.25 0 0 0 7.5 6.75v10.5A2.25 2.25 0 0 1 4.5 15a2.25 2.25 0 0 0-2.25 2.25v2.25" />
                </svg>

                <p class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    Your gallery is empty!
                </p>
                <p class="text-gray-500 dark:text-gray-400 text-center">
                    Looks like you haven't uploaded any images yet. <br>
                    Head back to the <a href="{{ url('/') }}" wire:navigate
                        class="text-blue-500 hover:underline">Home page</a> to get started!
                </p>
            </div>
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
