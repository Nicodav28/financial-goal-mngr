<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Repositories\Contracts\AttachmentRepositoryInterface;

class AttachmentService
{
    public function __construct(private readonly AttachmentRepositoryInterface $attachmentRepository)
    {
        //
    }

    public function getAllAttachments()
    {
        return $this->attachmentRepository->findAll();
    }

    public function getAttachmentById($id)
    {
        return $this->attachmentRepository->findById($id);
    }

    public function createAttachment(Request $request)
    {
        $file = $request->file('file');
        //todo: hacer que el path corresponda a el contribution_id y user_id ej user_1/contribution_5/filename.JPG
        $path = $file->store('attachments', 'public');

        $data = [
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type'    => $file->getClientMimeType(),
            'size'         => $file->getSize(),
            'description'  => $request->input('description'),
            'contribution_id' => $request->input('contribution_id'),
        ];

        return $this->attachmentRepository->create($data);
    }

    public function updateAttachment($id, array $data)
    {
        return $this->attachmentRepository->update($id, $data);
    }

    public function deleteAttachment($id)
    {
        return $this->attachmentRepository->delete($id);
    }
}
