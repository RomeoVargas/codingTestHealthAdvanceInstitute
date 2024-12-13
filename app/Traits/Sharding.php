<?php

namespace App\Traits;

trait Sharding
{
    protected function getShards(): array
    {
        return [
            'mysql_shard1',
            'mysql_shard2',
            'mysql_shard3',
        ];
    }
}
