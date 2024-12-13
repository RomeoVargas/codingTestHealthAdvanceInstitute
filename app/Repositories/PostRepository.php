<?php

namespace App\Repositories;

use App\Models\Post;
use App\Repositories\Interfaces\PostRepositoryInterface;
use App\Traits\ShardResolver;
use Illuminate\Support\Facades\DB;

class PostRepository extends CUDRepository implements PostRepositoryInterface
{
    use ShardResolver;

    protected $shard;

    public function __construct(Post $post)
    {
        $this->model = $post;
    }

    public function getAll()
    {
        $results = collect();

        foreach ($this->getShards() as $shard) {
            // Set the connection dynamically for the model
            $records = DB::connection($shard)
                ->table($this->model->getTable())
                ->get();

            // Merge the results from this shard
            $results = $results->merge($records);
        }

        return $results;
    }

    public function setShardByUserId(int $userId)
    {
        $this->shard = $this->getShardConnection($userId);

        return $this;
    }

    public function updateById(array $data, int $id)
    {
        DB::connection($this->shard)
            ->table($this->model->getTable())
            ->where('id', $id)
            ->update($data);
    }

    public function deleteById(int $id)
    {
        DB::connection($this->shard)
            ->table('post_tags')
            ->where('post_id', $id)
            ->delete();

        DB::connection($this->shard)
            ->table($this->model->getTable())
            ->where('id', $id)
            ->delete();
    }
}
