<?php

namespace App\Repositories;

use App\Models\Tag;
use App\Repositories\Interfaces\TagRepositoryInterface;
use Illuminate\Support\Collection;

class TagRepository extends CUDRepository implements TagRepositoryInterface
{
    public function __construct(Tag $tag)
    {
        $this->model = $tag;
    }

    public function search($keyword = null): Collection
    {
        return $this->model
            ->newQuery()
            ->where('name', 'like', "%$keyword%")
            ->pluck('name');
    }

    public function findOrCreateMany(array $names): Collection
    {
        $tags = collect();
        foreach ($names as $name) {
            $tags->push(
                $this->model
                    ->newQuery()
                    ->where('name', $name)
                    ->firstOrCreate([
                        'name' => $name
                    ])
            );
        }

        return $tags;
    }
}
