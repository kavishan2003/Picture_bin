<?php

namespace App\Http\Controllers;

use App\Models\Images;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ImageController extends Controller
{
    // public function getImage(Request $request, $hash)
    // {
    //     try {

    //         $image = Images::where('hash', $hash)->first();


    //         // ➊ Look up the record
    //         $image = Images::where('hash', $hash)->firstOrFail();
    //         $url   = $image->image_path;              // full https://… URL

    //         // ➋ Fetch the object with Guzzle in streaming mode
    //         $client   = new Client();
    //         $response = $client->request('GET', $url, ['stream' => true]);

    //         if ($response->getStatusCode() !== 200) {
    //             abort(404);                           // S3 says “not found”
    //         }

    //         $stream = $response->getBody();           // PSR‑7 stream

    //         // dd($stream);

    //         // ➌ Relay the bytes to the browser
    //         return response()->stream(
    //             function () use ($stream) {
    //                 while (! $stream->eof()) {
    //                     echo $stream->read(1024 * 64);   // 64 KB chunks
    //                 }
    //             },
    //             200,
    //             [
    //                 'Content-Type'        => $response->getHeaderLine('Content-Type') ?: 'image/jpeg',
    //                 'Content-Length'      => $response->getHeaderLine('Content-Length'),
    //                 'Content-Disposition' => 'inline; filename="' . basename($url) . '"',
    //                 'Cache-Control'       => 'public, max-age=86400',
    //             ]
    //         );

    //         // return redirect($image->image_path);
    //     } catch (\Exception $e) {
    //         // Handle errors gracefully
    //         abort(404);
    //     }
    // }
    /** Show the Blade view and pass the image record */
    public function show(string $hash)
    {
        $image = Images::where('hash', $hash)->firstOrFail();

        // Just hand the Eloquent model (or the URL only) to the Blade file
        return view('imageView', [
            'image'    => $image,            // full model
            'imageUrl' => $image->image_path // or just the URL
        ]);
    }
}
