<?php

namespace App\Repositories\Interfaces;

use App\Models\Post;

interface PostRepositoryInterface extends CUDRepositoryInterface
{
    public function getAll();

    public function setShardByUserId(int $userId);
}
