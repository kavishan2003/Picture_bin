<?php

namespace App\Http\Controllers;

use App\Models\Images;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;


class ImageController extends Controller
{
    public function getImage(Request $request, $hash )
    {
        try {
            // $hash = $request->route('hash');
            // dd($hash);
            // Example decoding: base64 decode (adjust based on your hash method)
            // $decodedUrl = base64_decode($hash);

            // dd($decodedUrl);

            // if (!is_numeric($decodedId)) {
            //     abort(404); // Invalid decoded ID
            // }

             $image = Images::where('hash', $hash)->first();

            if (!$image || !$image->image_path) {
                abort(404); // Not found
            }

            return redirect($image->image_path);
        } catch (\Exception $e) {
            // Handle errors gracefully
            abort(404);
        }
    }
}
