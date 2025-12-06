<?php

namespace App\Http\Controllers;

use App\Services\AttachmentService;
use App\Shared\ResponseHandler;
use Illuminate\Http\Request;

class AttachmentController extends Controller
{
    public function __construct(private readonly AttachmentService $attachmentService)
    {
        //
    }

    public function index()
    {
        try {
            $attachments = $this->attachmentService->getAllAttachments();
            return ResponseHandler::response(200, 'Attachment:index', null, null, $attachments);
        } catch (\Exception $e) {
            return ResponseHandler::response(500, 'Attachment:index', 'Failed to retrieve attachments', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $attachment = $this->attachmentService->getAttachmentById($id);
            if (!$attachment) {
                return ResponseHandler::response(404, 'Attachment:show', 'Attachment not found', null);
            }
            return ResponseHandler::response(200, 'Attachment:show', null, null, $attachment);
        } catch (\Exception $e) {
            return ResponseHandler::response(500, 'Attachment:show', 'Failed to retrieve attachment', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $attachment = $this->attachmentService->createAttachment($request);
            return ResponseHandler::response(201, 'Attachment:store', null, null, $attachment);
        } catch (\Exception $e) {
            return ResponseHandler::response(500, 'Attachment:store', 'Failed to create attachment', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $attachment = $this->attachmentService->updateAttachment($id, $request->all());
            return ResponseHandler::response(200, 'Attachment:update', null, null, $attachment);
        } catch (\Exception $e) {
            return ResponseHandler::response(500, 'Attachment:update', 'Failed to update attachment', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->attachmentService->deleteAttachment($id);
            return ResponseHandler::response(200, 'Attachment:destroy', 'Attachment deleted successfully', null);
        } catch (\Exception $e) {
            return ResponseHandler::response(500, 'Attachment:destroy', 'Failed to delete attachment', $e->getMessage());
        }
    }
}
