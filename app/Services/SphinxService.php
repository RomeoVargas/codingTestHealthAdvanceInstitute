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

    public function search($query)
    {
        $sphinx = new SphinxQL($this->connection);

        return $sphinx
            ->select('*')
            ->from('distributed_posts_index')
            ->match('title, description, tags', $query) // Searches all fields
            ->execute();
    }
}

