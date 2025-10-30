<?php

namespace App\Repositories\Eloquent;

use App\Models\Attachment;
use App\Repositories\Contracts\AttachmentRepositoryInterface;

class AttachmentRepository implements AttachmentRepositoryInterface
{
    public function findAll()
    {
        return Attachment::all();
    }

    public function findById($id)
    {
        return Attachment::findOrFail($id);
    }

    public function create(array $data)
    {
        return Attachment::create($data);
    }

    public function update($id, array $data)
    {
        $attachment = Attachment::findOrFail($id);
        $attachment->update($data);
        return $attachment;
    }

    public function delete($id)
    {
        return Attachment::destroy($id);
    }
}
