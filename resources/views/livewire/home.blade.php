<div>

    <!-- Header / Navigation Bar -->

    <header class="bg-gray-800  text-white p-4 shadow-lg">
        <nav
            class="container mx-auto flex flex-col lg:flex-row px-[100px]  lg:items-start  justify-between items-center">
            <!-- Logo -->
            <a href="{{ url('/') }}" class="text-3xl  text-blue-400 font-['poppins']"> PictureBin</a>

            <!-- Navigation Links -->
            <div class="flex space-x-6">
                <a href="{{ url('/') }}" wire:navigate
                    class="text-gray-300 hover:text-blue-300 transition duration-300 ease-in-out font-medium rounded-md hover:bg-gray-700 p-2">Home</a>
                <a href="{{ url('/gallery') }}" wire:navigate
                    class="text-gray-300 hover:text-blue-300 transition duration-300 ease-in-out font-medium rounded-md hover:bg-gray-700 p-2">My
                    Gallery</a>
            </div>
        </nav>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow flex items-center justify-center p-4 h-[90vh] bg-repeat ">


        <div x-data="{ isDragging: false }" @dragover.prevent="isDragging = true" @dragleave.prevent="isDragging = false"
            @drop.prevent="
        isDragging = false;
        let droppedFiles = Array.from($event.dataTransfer.files);
        @this.uploadMultiple('images', droppedFiles);
    "
            @paste.prevent="
        let pastedFiles = Array.from($event.clipboardData.files);
        if (pastedFiles.length > 0) {
            @this.uploadMultiple('images', pastedFiles);
        }
    "
            :class="{ 'border-blue-500 bg-blue-50': isDragging }"
            class="bg-white border-1 border-blue-200 rounded-lg shadow-[0_15px_30px_rgba(0,0,0,0.5)] p-10
            hover:border-green-700 transition-all duration-300 ease-in-out
            flex flex-col items-center justify-center text-center"
            style="min-height: 250px; width:800px;">

            {{-- Session Messages --}}
            @if (session()->has('message'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 6000)" x-show="show" x-transition:leave.duration.500ms
                    class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                    role="alert">
                    <span class="block sm:inline">{{ session('message') }}</span>
                </div>
            @endif



            @if (session()->has('error'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 6000)" x-show="show" x-transition:leave.duration.500ms
                    class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                    role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif


            {{-- Limit Exceeded Alert --}}
            @if (session()->has('limitExceeded'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 6000)" x-show="show" x-transition:leave.duration.500ms
                    class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4"
                    role="alert">
                    <span class="block sm:inline">{{ session('limitExceeded') }}</span>
                </div>
            @endif

            {{-- Image Uploader Section --}}
            <div
                class="bg-white rounded-lg shadow-md p-12 mb-6 text-center border-2 border-dashed border-blue-400 transition-colors duration-200">
                <h3 class="text-xl font-semibold text-gray-700 mb-4">
                    Drag & Drop your images here, <br> copy (ctrl + c) & paste (ctrl + v) or
                    <label for="image-upload-input"
                        class="text-blue-600 hover:text-blue-800 cursor-pointer underline font-medium">Browse</label>
                </h3>
                <input type="file" id="image-upload-input" multiple wire:model="images" accept="image/*"
                    class="hidden">

                {{-- Display validation errors for the images input --}}
                @error('images.*')
                    <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
                @enderror
                @error('images')
                    <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
                @enderror



                @if (count($images) > 0)
                    <div class="selected-images mt-6 pt-4 border-t border-gray-200">
                        <h4 class="text-lg font-semibold text-gray-700 mb-4">Selected Images (Ready for Upload):</h4>
                        <div class="flex flex-wrap gap-4 justify-center mt-4">
                            @foreach ($images as $index => $image)
                                <div class="relative border border-gray-300 rounded-lg p-4 text-center w-48 shadow-md">
                                    <img src="{{ $image->temporaryUrl() }}" alt="Preview"
                                        class="w-full h-32 object-cover rounded-md mb-2">
                                    <span class="text-sm truncate block">{{ $image->getClientOriginalName() }}</span>
                                    <button type="button" wire:click="removeImage({{ $index }})"
                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm font-bold cursor-pointer shadow">
                                        &times;
                                    </button>
                                </div>
                            @endforeach
                        </div>
                        <button wire:click="uploadImages" wire:loading.attr="disabled"
                            class="mt-6 bg-blue-600 hover:bg-green-700 cursor-pointer text-white font-bold py-2 px-6 rounded-lg focus:outline-none focus:shadow-outline disabled:opacity-50 disabled:cursor-not-allowed">
                            <span wire:loading.remove wire:target="uploadImages">Upload Images
                                ({{ count($images) }})</span>
                            <span wire:loading wire:target="uploadImages">Uploading...</span>
                        </button>
                    </div>
                @endif
            </div>
            @if (count($uploadedImages) > 0)
                <div class="mt-10 w-full max-w-3xl mx-auto">
                    <h4 class="text-lg font-semibold text-gray-700 mb-4 text-center">Uploaded Images & Shareable URLs
                    </h4>
                    <div class="space-y-4">
                        @foreach ($uploadedImages as $index => $img)
                            <div class="flex items-center justify-between bg-gray-100 rounded-lg p-3 shadow-sm relative"
                                x-data="{ copied: false }">
                                <div class="truncate text-sm text-gray-800 flex-1 mr-4">
                                    {{ $img['fake_path'] }}
                                </div>
                                <button
                                    @click="navigator.clipboard.writeText('{{ $img['fake_path'] }}'); copied = true; setTimeout(() => copied = false, 2000);"
                                    class="text-blue-600 hover:text-green-600 text-sm font-semibold px-3 py-1 rounded transition"
                                    title="Copy to clipboard">
                                    <template x-if="!copied">
                                        <span>Copy</span>
                                    </template>
                                    <template x-if="copied">
                                        <span class="text-green-500">Copied!</span>
                                    </template>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- cloud fare --}}
            <input type="hidden" id="cf-turnstile-response" wire:model.defer="turnstileToken">
            <div wire:ignore x-data x-init="window.initTurnstile && initTurnstile($el)" class="my-6 flex justify-center">
                <div class=" text-gray-500 text-center  rounded-xl p-5 cf-turnstile flex items-center justify-center"
                    data-sitekey="{{ config('services.turnstile.key') }}" data-theme="{{ $theme ?? 'light' }}"
                    data-callback="onTurnstileSuccess" data-size="normal">
                    {{-- <p class="text-sm">Please complete the captcha</p> --}}
                </div>
            </div>

        </div>
    </main>
    {{-- Care about people's approval and you will be their prisoner. --}}
</div>
<script>
    function triggerFileInput() {
        document.getElementById('fileInput').click();
    }
</script>
<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
<script>
    function onTurnstileSuccess(token) {
        @this.set('turnstileToken', token);
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('image-upload-input');
        const maxFileSizeMB = 2;
        const maxFilesAllowed = 6;
        const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];

        fileInput.addEventListener('change', function(event) {
            const files = Array.from(event.target.files);

            if (files.length > maxFilesAllowed) {
                alert(`You can upload a maximum of ${maxFilesAllowed} images.`);
                fileInput.value = ''; // Clear selection
                return;
            }

            for (let file of files) {
                const isValidType = allowedTypes.includes(file.type);
                const isValidSize = file.size <= maxFileSizeMB * 1024 * 1024;

                if (!isValidType) {
                    alert(
                        `File "${file.name}" is not a supported format. Only PNG and JPG are allowed.`
                    );
                    fileInput.value = '';
                    return;
                }

                if (!isValidSize) {
                    alert(`File "${file.name}" exceeds the 2MB size limit.`);
                    fileInput.value = '';
                    return;
                }
            }
        });
    });
</script>
