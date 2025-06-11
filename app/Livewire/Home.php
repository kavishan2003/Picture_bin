<?php

namespace App\Livewire;

use App\Models\Images;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
// use Intervention\Image\ImageManagerStatic as ImageIntervention; 

class Home extends Component
{
    use WithFileUploads;
    public $images = []; // To hold temporary file objects for newly selected images
    public $uploadedImages = []; // To hold paths/information of successfully uploaded images
    public $maxImages = 6; // Define the maximum number of images allowed
    public $limitReached = false; // State to inform the UI if the limit is reached        

    protected $rules = [
        'images.*' => 'image|max:2048', // 2MB Max, image type
    ];

    public function updatedImages()
    {
        // Reset limitReached state before validation/checks
        $this->limitReached = false;

        // 1. Validate individual files for type/size
        try {
            $this->validate([
                'images.*' => 'image|max:2048',
            ]);
        } catch (ValidationException $e) {
            // If some files are invalid, filter them out and re-index the array
            $validFiles = collect($this->images)->filter(function ($image, $key) use ($e) {
                return !isset($e->errors()['images.' . $key]);
            })->values()->toArray();

            // Set the images property to only include valid files
            $this->images = $validFiles;

            // Flash a generic error for individual file issues
            session()->flash('error', 'Some selected files were not valid images or exceeded size limits.');
        }

        // 2. Enforce the total image limit
        if (count($this->images) > $this->maxImages) {
            // Trim the array to the maximum allowed number of images
            $this->images = array_slice($this->images, 0, $this->maxImages);
            $this->limitReached = true;
            session()->flash('limitExceeded', 'You can only select a maximum of ' . $this->maxImages . ' images at a time.');
        }

        // You might want to do some client-side preview logic here if not using a separate JS library
    }

    public function uploadImages()
    {
        try {
            $this->validate(); // Validate all selected images

            foreach ($this->images as $image) {
                // Generate a unique filename
                $filename = md5($image->getClientOriginalName() . time()) . '.' . $image->getClientOriginalExtension();

                // Store the image in the 'public/uploads' directory
                $path = $image->storeAs('uploads', $filename, 'public');

                // Add the path to uploadedImages array
                $this->uploadedImages[] = [
                    'name' => $image->getClientOriginalName(),
                    'path' => Storage::url($path), // Get the public URL
                    'original_path' => $path, // Store the actual storage path for removal
                    'size' => $image->getSize(),
                ];

                // Create a new record in the 'images' table
                Images::create([
                  
                    'image_path' => Storage::url($path), // Save the public URL
                    'original_name' => $image->getClientOriginalName(),
                    'size' => $image->getSize(),
                ]);


            }

            // Clear the temporary images after successful upload
            $this->images = [];

            session()->flash('message', 'Images uploaded successfully!');
        } catch (ValidationException $e) {
            $this->addError('images', 'Please check image types and sizes.'); // Generic error for UI
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred during upload: ' . $e->getMessage());
        }
    }

    public function removeImage($index)
    {
        // Remove from the temporary selection (before actual upload)
        if (isset($this->images[$index])) {
            unset($this->images[$index]);
            $this->images = array_values($this->images); // Re-index the array
        }
    }

    public function removeUploadedImage($index)
    {
        if (isset($this->uploadedImages[$index])) {
            $imageToRemove = $this->uploadedImages[$index];

            // Delete the file from storage
            Storage::disk('public')->delete($imageToRemove['original_path']);

            // Remove from the uploadedImages array
            unset($this->uploadedImages[$index]);
            $this->uploadedImages = array_values($this->uploadedImages); // Re-index the array

            session()->flash('message', 'Image removed successfully!');
        }
    }



    public function render()
    {

        return view('livewire.home');
    }
}
