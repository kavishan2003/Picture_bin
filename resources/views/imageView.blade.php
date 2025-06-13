{{-- resources/views/imageView.blade.php --}}
@extends('layouts.app')

@section('content')

    {{-- Easiest: load S3/CDN URL directly --}}
    {{-- <img
        src="{{ $imageUrl }}"
        alt="Image {{ $image->id }}"
        class=" w-100 h-100 object-cover rounded shadow"
    /> --}}

    <div class="min-h-[80vh] flex items-center justify-center px-4">
        <figure class="group">
            <img src="{{ $imageUrl }}" alt="Image {{ $image->id }}"
                class="
                    w-full max-w-5xl            {{-- don’t blow past 5 xl on big monitors --}}
                    max-h-[80vh]                {{-- never spill off the viewport --}}
                    object-contain
                    rounded-2xl shadow-xl ring-1 ring-black/5
                    transition-transform duration-300
                    group-hover:scale-[1.02]    {{-- gentle zoom on hover --}}
                ">

            {{-- ✨ Optional caption --}}
            <figcaption class="mt-3 text-center text-sm text-gray-500 dark:text-gray-400">
                Uploaded {{ $image->created_at->toFormattedDateString() }}
            </figcaption>
        </figure>
    </div>
@endsection
