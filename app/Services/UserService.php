<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;

class UserService
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
        //
    }

    public function getAllUsers()
    {
        try {
            return $this->userRepository->getAll();
        } catch (\Exception $e) {
            throw new \Exception('Error retrieving users: ' . $e->getMessage());
        }
    }

    public function getUserById($id)
    {
        try {
            return $this->userRepository->find($id);
        } catch (\Exception $e) {
            throw new \Exception('Error retrieving user: ' . $e->getMessage());
        }
    }

    public function createUser(array $data)
    {
        try {
            return $this->userRepository->create($data);
        } catch (\Exception $e) {
            throw new \Exception('Error creating user: ' . $e->getMessage());
        }
    }

    public function updateUser($id, array $data)
    {
        try {
            return $this->userRepository->update($id, $data);
        } catch (\Exception $e) {
            throw new \Exception('Error updating user: ' . $e->getMessage());
        }
    }

    public function deleteUser($id)
    {
        try {
            $this->userRepository->delete($id);
        } catch (\Exception $e) {
            throw new \Exception('Error deleting user: ' . $e->getMessage());
        }
    }
}
