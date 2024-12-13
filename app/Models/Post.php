<?php

namespace App\Models;

use App\Models\Pivots\PostTagsPivot;
use App\Traits\ShardResolver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Post
 * @package App\Models
 * @property int id
 * @property string title
 * @property string description
 * @property int user_id
 */
class Post extends Model
{
    use ShardResolver;

    protected $fillable = [
        'title',
        'description',
        'user_id',
    ];

    public function getConnectionName()
    {
        return $this->getShardConnection($this->user_id);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tags')
            ->using(PostTagsPivot::class);
    }
}
