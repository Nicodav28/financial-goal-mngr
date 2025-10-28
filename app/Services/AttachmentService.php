<?php

namespace App\Services;

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

    public function createAttachment(array $data)
    {
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
