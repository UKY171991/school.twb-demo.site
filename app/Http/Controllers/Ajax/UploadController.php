<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'folder' => 'nullable|string|max:50'
        ]);

        try {
            $folder = $request->folder ?? 'uploads';
            $filename = Str::random(20) . '.' . $request->file('image')->getClientOriginalExtension();
            $path = $request->file('image')->storeAs($folder, $filename, 'public');

            return response()->json([
                'success' => true,
                'message' => 'Image uploaded successfully',
                'data' => [
                    'path' => $path,
                    'url' => Storage::disk('public')->url($path),
                    'filename' => $filename
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload image: ' . $e->getMessage()
            ], 500);
        }
    }

    public function uploadDocument(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf,doc,docx,txt|max:10240', // 10MB max
            'folder' => 'nullable|string|max:50'
        ]);

        try {
            $folder = $request->folder ?? 'documents';
            $filename = Str::random(20) . '.' . $request->file('document')->getClientOriginalExtension();
            $path = $request->file('document')->storeAs($folder, $filename, 'public');

            return response()->json([
                'success' => true,
                'message' => 'Document uploaded successfully',
                'data' => [
                    'path' => $path,
                    'url' => Storage::disk('public')->url($path),
                    'filename' => $filename,
                    'original_name' => $request->file('document')->getClientOriginalName(),
                    'size' => $request->file('document')->getSize()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload document: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteFile(Request $request)
    {
        $request->validate([
            'path' => 'required|string'
        ]);

        try {
            if (Storage::disk('public')->exists($request->path)) {
                Storage::disk('public')->delete($request->path);
                
                return response()->json([
                    'success' => true,
                    'message' => 'File deleted successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'File not found'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete file: ' . $e->getMessage()
            ], 500);
        }
    }
}
