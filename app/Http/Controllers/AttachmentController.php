<?php

namespace App\Http\Controllers;

use App\Services\AttachmentService;
    use Illuminate\Http\Request;

class AttachmentController extends Controller
{
    public function __construct(private readonly AttachmentService $attachmentService)
    {
        //
    }

    public function index()
    {
        return $this->attachmentService->getAllAttachments();
    }

    public function show($id)
    {
        return $this->attachmentService->getAttachmentById($id);
    }

    public function store(Request $request)
    {
        return $this->attachmentService->createAttachment($request);
    }

    public function update(Request $request, $id)
    {
        return $this->attachmentService->updateAttachment($id, $request->all());
    }

    public function destroy($id)
    {
        return $this->attachmentService->deleteAttachment($id);
    }
}
