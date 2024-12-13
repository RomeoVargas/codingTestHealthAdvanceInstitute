<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\PostRepositoryInterface;
use App\Repositories\Interfaces\TagRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    private PostRepositoryInterface $postRepository;
    private TagRepositoryInterface $tagRepository;

    public function __construct(
        PostRepositoryInterface $postRepository,
        TagRepositoryInterface  $tagRepository,
    )
    {
        $this->postRepository = $postRepository;
        $this->tagRepository = $tagRepository;
    }

    public function index(Request $request)
    {
        $posts = $this->postRepository->getAll();
        Cache::put('posts', $posts);

        // For some reason it doesn't get to index any record so this won't be added for now
        // $sphinxPosts = app(SphinxService::class)->search($request->get('search'));

        // This is not paginated anymore due to lack of time
        return response()->json([
            'records' => $posts
        ]);
    }

    public function create(Request $request)
    {
        try {
            $post = $this->postRepository->create(
                $request->only(['title', 'description', 'user_id'])
            );
            if (!empty($tags = $request->get('tags'))) {
                $tags = $this->tagRepository->findOrCreateMany($tags);
                $post->tags()->sync($tags);
            }
            // New entry is added so cache needs to be reset
            Cache::delete('posts');

            return response()->json([
                'post' => $post,
                'message' => 'Post created successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json([
                'message' => 'Something went wrong. The post was not created.',
            ], 401);
        }
    }

    public function delete($userId, $postId)
    {
        try {
            $this->postRepository
                ->setShardByUserId($userId)
                ->deleteById($postId);


            // An entry is deleted so cache needs to be reset
            Cache::delete('posts');

            return response()->json([
                'message' => 'Post deleted successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json([
                'message' => 'Something went wrong. The post was not deleted.',
            ], 401);
        }
    }

    public function update(Request $request, $userId, $postId)
    {
        try {
            $post = $this->postRepository
                ->setShardByUserId($userId)
                ->updateById(
                    $request->only(['title', 'description', 'user_id']),
                    $postId
                );
            if (!empty($tags = $request->get('tags'))) {
                $tags = $this->tagRepository->findOrCreateMany($tags);
                $post->tags()->sync($tags);
            }

            // An entry is updated so cache needs to be reset
            Cache::delete('posts');

            return response()->json([
                'message' => 'Post updated successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json([
                'message' => 'Something went wrong. The post was not updated.',
            ], 401);
        }
    }
}
