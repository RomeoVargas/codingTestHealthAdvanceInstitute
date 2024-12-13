<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Foolz\SphinxQL\SphinxQL;
use Foolz\SphinxQL\Drivers\Pdo\Connection as PdoConnection;

class SphinxService
{
    protected $connection;

    public function __construct()
    {
        $pdo = new \PDO(
            sprintf(
                'mysql:host=%s;port=%s',
                config('database.connections.sphinx.host'),
                config('database.connections.sphinx.port')
            )
        );
        $this->connection = new PdoConnection($pdo);
    }

    public function getCount($query)
    {
        $sphinx = new SphinxQL($this->connection);

        return $sphinx->query(
            "SELECT id FROM distributed_posts_index WHERE MATCH('{$query}')"
        )->execute()->count();
    }

    public function search($query, $page = 1, $limit = 10)
    {
        $sphinx = new SphinxQL($this->connection);
        $offset = ($page - 1) * $limit;

        $results = $sphinx->query(
            "SELECT * FROM distributed_posts_index WHERE MATCH('{$query}') LIMIT {$offset}, {$limit}"
        )->execute()->fetchAllAssoc();

        return collect($results)->map(function ($result) {
            $result['tags'] = explode(',', $result['tags']);
            return $result;
        });
    }
}

