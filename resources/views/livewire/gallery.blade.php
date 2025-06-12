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
                        class="bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-2xl flex flex-col h-full">

                        {{-- Image Section --}}
                        <div class="relative w-full h-48 bg-gray-200 dark:bg-gray-700 overflow-hidden">
                            <img src="{{ $image->image_path }}" alt="{{ $image->original_name }}"
                                class="w-full h-full object-cover transition duration-300 ease-in-out group-hover:scale-110">
                            {{-- Optional: Overlay for hover effects or indicators --}}
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
                                {{-- Could add a small icon or text here if desired --}}
                            </div>
                        </div>

                        {{-- Content Section --}}
                        <div class="p-4 flex flex-col flex-grow">
                            {{-- File Name --}}
                            <p class="text-lg font-semibold truncate text-gray-800 dark:text-white mb-1">
                                {{ $image->original_name }}
                            </p>

                            {{-- File Size --}}
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">
                                {{ round($image->size / 1024 / 1024, 2) }} MB
                            </p>

                            {{-- Link Display and Copy Button --}}
                            <div class="flex items-center space-x-2 bg-gray-100 dark:bg-gray-700 p-2 rounded-md mb-4"
                                x-data="{ copied: false }">
                                <p class="flex-grow text-sm truncate text-gray-700 dark:text-gray-300">
                                    {{ $image->image_path }}
                                </p>
                                <button
                                    @click="
                        navigator.clipboard.writeText('{{ $image->image_path }}');
                        copied = true;
                        setTimeout(() => copied = false, 2000);
                    "
                                    class="p-2 text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200"
                                    title="Copy link to clipboard">
                                    <span x-show="!copied">
                                        {{-- Copy Icon (Heroicons outline: clipboard) --}}
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                            </path>
                                        </svg>
                                    </span>
                                    <span x-show="copied" class="text-green-500">
                                        {{-- Checkmark Icon (Heroicons solid: check-circle) --}}
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                                </button>
                            </div>

                            {{-- Delete Button (at the bottom) --}}


                            <div class="mt-auto">
                                <button type="button" {{-- Important for Alpine.js buttons within forms to prevent accidental form submission --}} x-data="{}"
                                    {{-- Initialize Alpine.js scope if not already initialized by a parent element --}}
                                    @click="
                                            if (confirm('Are you sure you want to delete this image?')) {
                                                $wire.deleteImage({{ $image->id }});
                                            } else {
                                                // Do nothing if user cancels
                                            }
                                        "
                                    class="w-full bg-gradient-to-r from-red-600 to-red-800 hover:from-red-700 hover:to-red-900 text-white font-bold py-2 px-4 rounded-md transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 flex items-center justify-center space-x-2">
                                    {{-- SVG Delete Icon (Heroicons outline: trash) --}}
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                    <span>Delete</span>
                                </button>
                            </div>

                            {{-- <div class="mt-auto">
                                <button wire:click="deleteImage({{ $image->id }})"
                                    onclick="confirm('Are you sure you want to delete this image?') || event.stopImmediatePropagation()"
                                    class="w-full bg-gradient-to-r from-red-600 to-red-800 hover:from-red-700 hover:to-red-900 text-white font-bold py-2 px-4 rounded-md transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 flex items-center justify-center space-x-2">
                                    <i class="fa-solid fa-trash-can"></i> {{-- Font Awesome Trash Can Icon 
                                    <span>Delete</span> {{-- Text remains for clarity 
                                </button>
                            </div> --}}


                        </div>
                    </div>
                @endforeach
                {{-- @foreach ($images as $image)
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg shadow-[0_20px_30px_rgba(0,0,0,0.8)] overflow-hidden transform transition duration-300 hover:scale-105">
                        <img src="{{ $image->image_path }}" alt="{{ $image->original_name }}"
                            class="w-full h-48 object-cover">
                        <div class="p-4">
                            <p class="text-lg font-semibold truncate text-gray-800 dark:text-white">
                                {{ $image->original_name }}</p>
                            <div class="border-1 border-white">
                                <p class="text-lg truncate text-gray-800 dark:text-white">
                                    {{ $image->image_path }}</p>
                            </div>    

                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ round($image->size / 1024 / 1024, 2) }} MB</p>
                            <button wire:click="deleteImage({{ $image->id }})"
                                onclick="confirm('Are you sure you want to delete this image?') || event.stopImmediatePropagation()"
                                class="mt-3 bg-red-500 hover:bg-red-600 text-white text-sm py-1 px-3 rounded-md transition duration-200 ease-in-out">
                                Delete
                            </button>
                        </div>
                    </div>
                @endforeach --}}
            </div>
            {{-- If you use pagination, add: --}}
            {{-- <div class="mt-8">
            {{ $images->links() }}
        </div> --}}
        @endif
    </div>




</div>
