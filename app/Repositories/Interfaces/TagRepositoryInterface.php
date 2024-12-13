<?php

namespace App\Repositories\Interfaces;

use Illuminate\Support\Collection;

interface TagRepositoryInterface extends CUDRepositoryInterface
{
    public function search($keyword = null) : Collection;

    public function findOrCreateMany(array $names) : Collection;
}
