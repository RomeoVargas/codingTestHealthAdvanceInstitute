<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserRepository extends CUDRepository implements UserRepositoryInterface
{
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function create(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        return parent::create($data);
    }

    public function updateById(array $data, int $id)
    {
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return parent::updateById($data, $id);
    }
}
