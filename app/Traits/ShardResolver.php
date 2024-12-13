<?php

namespace App\Traits;

trait ShardResolver
{
    use Sharding;

    public function getShardConnection($key)
    {
        $shards = $this->getShards();

        // Simple way of spreading data among shards evenly
        $shardIndex = $key % count($shards);

        return $shards[$shardIndex];
    }
}
