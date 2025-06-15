<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Images;
use Livewire\Component;
use Illuminate\Support\Str;
use App\Models\UploadAttempt;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Http;
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
    public $turnstileToken;
    protected $rules = [
        'images.*' => 'image|max:2048', // 2MB Max, image type
    ];

    public function updatedImages()
    {
        //
    }

    public function uploadImages()
    {
        $ip = request()->ip();
        $today = Carbon::today();

        $attempt = UploadAttempt::firstOrCreate(
            ['ip_address' => $ip, 'date' => $today],
            ['upload_count' => 0]
        );

        if ($attempt->upload_count >= 5) { // limit = 5 uploads/day
            session()->flash('error', 'Upload limit reached for today.');
            return;
        }

        // Verify Turnstile
        $response = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
            'secret' => config('services.turnstile.secret'),
            'response' => $this->turnstileToken,
            'remoteip' => request()->ip(),
        ]);

        if (!$response->json('success')) {
            session()->flash('error', 'Captcha verification failed. Please try again.');
            return;
        }

        if(count($this->images)  > 1  ){

            session()->flash('error', '❌ Only one image can uploaded at a time');

        }
        

        try {
            $this->validate(); // Validate all selected images

            foreach ($this->images as $image) {

                
                // Generate a unique filename
                $filename = md5($image->getClientOriginalName() . time()) . '.' . $image->getClientOriginalExtension();

                // Store the image in the 'public/uploads' directory
                $path = Storage::disk('s3')->put('uploads/' . $filename, $image, 'public');

                $image_path = Storage::disk('s3')->url($path);

                $fake_hash = md5($image_path);

                $fake_path = url(config('app.image_url_prefix') . $fake_hash);


                // Add the path to uploadedImages array
                $this->uploadedImages[] = [
                    'name' => $image->getClientOriginalName(),
                    // 'path' => Storage::url($path), 

                    'path' => Storage::disk('s3')->url($path),
                    'fake_path' =>  $fake_path,
                    'original_path' => $path, // Store the actual storage path for removal
                    'size' => $image->getSize(),
                ];

                // Create a new record in the 'images' table
                Images::create([

                    // 'image_path' => Storage::url($path), 
                    'image_path' => $image_path,
                    'hash' => $fake_hash,
                    'fake_path' =>  $fake_path,
                    'original_name' => $image->getClientOriginalName(),
                    'size' => $image->getSize(),
                ]);
            }

            // Clear the temporary images after successful upload
            $this->reset('images');
            // $this->images = [];
            // dd($image);
            $attempt->increment('upload_count');

            session()->flash('message', 'Images uploaded successfully!');
        } catch (ValidationException $e) {
            $this->addError('images', 'Please check image types and sizes.'); // Generic error for UI
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred during upload: ' . $e->getMessage());
        }
        // return redirect()->back()->with('alert', $this->images);
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
        return view('livewire.home', [
            'images' => Images::latest()->get(),


        ]);
    }
}
