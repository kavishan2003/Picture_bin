<?php

namespace App\Livewire;

use App\Models\Images;


use Livewire\Component; // Assuming you have an Article model
use App\Models\FakenessCheck;
use Illuminate\Support\Facades\DB; // Important: Make sure to import your Image model
use Illuminate\Support\Facades\Storage; // Not strictly needed for fetching, but good for context if you had other file ops



class Gallery extends Component
{

    public $images;

    public function mount()
    {
        // Fetch all images from the database
        // You can order them, paginate them, or filter them as needed
        $this->images = Images::latest()->get(); // Example: get all images, ordered by creation date (newest first)
        // $this->images = Image::paginate(12); // Example: for pagination
    }
    public function deleteImage($id)
    {
        // 1. Find the image record
        $image = Images::find($id);

        if ($image) {
            // 2. Update the 'is_deleted' column to true
            $image->update(['is_deleted' => true]);

            // 3. Re-fetch the images to update the UI (showing only non-deleted images)
            $this->images = Images::where('is_deleted', false)->latest()->get();

            session()->flash('message', 'Image moved to trash successfully!');
        } else {
            session()->flash('error', 'Image not found!');
        }
    }




    public function render()
    {
        $this->images = Images::where('is_deleted', false)->latest()->get();
        return view('livewire.gallery');
    }
}
