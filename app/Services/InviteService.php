<?php

namespace App\Services;

use App\Repositories\Contracts\InviteRepositoryInterface;

class InviteService
{
    public function __construct(private readonly InviteRepositoryInterface $inviteRepository)
    {
        //
    }

    public function getAllInvites()
    {
        return $this->inviteRepository->findAll();
    }

    public function getInviteById($id)
    {
        return $this->inviteRepository->findById($id);
    }

    public function createInvite(array $data)
    {
        return $this->inviteRepository->create($data);
    }

    public function updateInvite($id, array $data)
    {
        return $this->inviteRepository->update($id, $data);
    }

    public function deleteInvite($id)
    {
        return $this->inviteRepository->delete($id);
    }

    public function acceptInvite($inviteCode)
    {
        $invite = $this->inviteRepository->findByInviteCode($inviteCode);

        if (!$invite) {
            throw new \Exception('Invalid invite code.');
        }

        if ($invite->invite_status !== 1) {
            throw new \Exception('Invite has already been used or is inactive.');
        }

        return $this->inviteRepository->update($invite->id, ['invite_status' => 2]);
    }

}
