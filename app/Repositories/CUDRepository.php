<?php

namespace App\Repositories;

use App\Repositories\Interfaces\CUDRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

abstract class CUDRepository implements CUDRepositoryInterface
{
    /** @var Model $model */
    protected mixed $model;

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function updateById(array $data, int $id)
    {
        return $this->model
            ->findOrFail($id)
            ->update($data);
    }

    public function deleteById(int $id)
    {
        return $this->model
            ->findOrFail($id)
            ->delete();
    }
}
