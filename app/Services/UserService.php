<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

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
            $data['password'] = Hash::make($data['password'], ['rounds' => 12]);
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

    public function validateLoginData(array $data): User|null
    {
        try {
            $user = $this->userRepository->findByEmail($data['email']);

            if (!$user || !Hash::check($data['password'], $user->password)) {
                return null;
            }

            return $user;
        } catch (\Exception $e) {
            throw new \Exception('Error validating login data: ' . $e->getMessage());
        }
    }
}
