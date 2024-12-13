<?php

namespace App\Repositories\Interfaces;

interface CUDRepositoryInterface
{
    public function create(array $data);

    public function updateById(array $data, int $id);

    public function deleteById(int $id);
}
