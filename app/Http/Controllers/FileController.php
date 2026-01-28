<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class FileController extends Controller
{
    public function showPublicFile($path)
    {
        // Basic security to prevent directory traversal
        if (str_contains($path, '..')) {
            abort(403, 'Invalid path');
        }

        $disk = Storage::disk('public');
        
        if (!$disk->exists($path)) {
            abort(404, 'File not found.');
        }

        $file = $disk->get($path);
        $mimeType = $disk->mimeType($path);

        return response($file)->header('Content-Type', $mimeType);
    }
}