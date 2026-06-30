<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssetController extends Controller
{
    public function index(Project $project)
    {
        abort_unless($project->user_id === auth()->id(), 403);
        return response()->json($project->assets()->latest()->get());
    }

    public function store(Request $request, Project $project)
    {
        abort_unless($project->user_id === auth()->id(), 403);
        $request->validate([
            'file' => 'required|file|image|max:10240', // Max 10MB
        ]);

        $file = $request->file('file');
        
        $supabaseUrl = env('SUPABASE_URL');
        $supabaseKey = env('SUPABASE_ANON_KEY');
        $bucket = env('SUPABASE_STORAGE_BUCKET', 'studiopro-assets');
        $path = '';

        if ($supabaseUrl && $supabaseKey) {
            $ext = $file->getClientOriginalExtension();
            $filename = (string) \Illuminate\Support\Str::uuid() . ($ext ? '.' . $ext : '');
            $uploadPath = 'uploads/' . $filename;
            
            // To upload to Supabase, we can use a direct PUT request to the storage API
            $client = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'Bearer ' . $supabaseKey,
                'apikey' => $supabaseKey,
                'Content-Type' => $file->getMimeType(),
            ]);

            if (app()->environment('local', 'testing')) {
                $client = $client->withoutVerifying();
            }

            $response = $client->withBody(file_get_contents($file->getRealPath()), $file->getMimeType())
                ->put($supabaseUrl . '/storage/v1/object/' . $bucket . '/' . $uploadPath);

            if ($response->successful()) {
                $path = $supabaseUrl . '/storage/v1/object/public/' . $bucket . '/' . $uploadPath;
            } else {
                // Fallback to local public disk
                $path = $file->store('uploads', 'public');
            }
        } else {
            $path = $file->store('uploads', 'public');
        }
        
        $dimensions = null;
        try {
            $imageInfo = @getimagesize($file->getRealPath());
            if ($imageInfo) {
                $dimensions = [
                    'width' => $imageInfo[0],
                    'height' => $imageInfo[1]
                ];
            }
        } catch (\Exception $e) {
            // Ignore if dimensions extraction fails
        }

        $asset = $project->assets()->create([
            'filename' => $file->getClientOriginalName(),
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'dimensions' => $dimensions,
        ]);

        return response()->json($asset, 201);
    }

    public function destroy(Project $project, Asset $asset)
    {
        abort_unless($project->user_id === auth()->id(), 403);
        abort_unless($asset->project_id === $project->id, 404);

        $supabaseUrl = env('SUPABASE_URL');
        $supabaseKey = env('SUPABASE_ANON_KEY');
        $bucket = env('SUPABASE_STORAGE_BUCKET', 'studiopro-assets');

        if ($supabaseUrl && $supabaseKey && str_starts_with($asset->path, $supabaseUrl)) {
            $parts = explode('/' . $bucket . '/', $asset->path);
            if (count($parts) > 1) {
                $storagePath = $parts[1];
                $client = \Illuminate\Support\Facades\Http::withHeaders([
                    'Authorization' => 'Bearer ' . $supabaseKey,
                    'apikey' => $supabaseKey,
                ]);

                if (app()->environment('local', 'testing')) {
                    $client = $client->withoutVerifying();
                }

                $client->delete($supabaseUrl . '/storage/v1/object/' . $bucket, [
                    'prefixes' => [$storagePath]
                ]);
            }
        } else {
            Storage::disk('public')->delete($asset->path);
        }

        // Delete database record
        $asset->delete();

        return response()->json(['success' => true]);
    }
}
