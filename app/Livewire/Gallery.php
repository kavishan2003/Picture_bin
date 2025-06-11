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
    public function getImages() {}


    public function render()
    {
        return view('livewire.gallery');
    }
}
