<?php

namespace App\Models\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PostTagsPivot extends Pivot
{
    // Dynamically set the connection
    protected $connection;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (isset($this->parent)) {
            $this->setConnection($this->parent->getConnectionName());
        }
    }
}
