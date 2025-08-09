<?php

namespace App\Http\Controllers\Web\BackEnd;

use App\Enums\PermissionEnum;
use App\Http\Controllers\Controller;
use App\Models\Attachment;
use App\Models\Proposal;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public function store(Request $request, Proposal $proposal)
    {
        try {
            Gate::authorize(PermissionEnum::CREATE_ATTACHMENT->value);

            $validated = $request->validate([
                'attachments'   => 'required|array',
                'attachments.*' => 'file|mimes:pdf|between:100,512',
            ]);

            $savedAttachments = [];

            foreach ($validated['attachments'] as $file) {
                // Simpan file ke storage (misal disk 'public' di folder 'attachments')
                $filePath = $file->store('attachments', 'public');

                // Simpan data attachment ke DB
                $attachment = $proposal->attachments()->create([
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $filePath,
                ]);

                $savedAttachments[] = $attachment;
            }

            return back()->with('success', count($savedAttachments) . ' attachments uploaded successfully.');
        } catch (AuthorizationException $e) {
            Log::error($e->getMessage());
            abort(403, 'This action is unauthorized.');
        } catch (Exception $e) {
            Log::error('Error uploading attachments: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while uploading attachments.');
        }
    }

    public function destroy($id)
    {
        try {
            Gate::authorize(PermissionEnum::DELETE_ATTACHMENT->value);

            $attachment = Attachment::findOrFail($id);
            $originalName = $attachment->original_name;

            // Hapus file dari storage
            Storage::disk('public')->delete($attachment->file_path);

            // Hapus data attachment
            $attachment->delete();

            return back()->with('success', "Attachment '{$originalName}' deleted successfully.");
        } catch (AuthorizationException $e) {
            Log::error($e->getMessage());
            abort(403, 'This action is unauthorized.');
        } catch (Exception $e) {
            Log::error("Error deleting attachment (ID: {$id}): " . $e->getMessage());
            return back()->with('error', 'An error occurred while deleting the attachment.');
        }
    }
}
