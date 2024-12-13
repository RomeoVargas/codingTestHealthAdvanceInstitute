<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\PostRepositoryInterface;
use App\Repositories\Interfaces\TagRepositoryInterface;
use App\Services\SphinxService;
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
        $sphinxService = new SphinxService();
        $page = $request->get('page', 1);
        $limit = $request->get('perPage', 10);
        $keyword = $request->get('keyword');

        $cacheRecordsKeyword = "posts:data{$keyword}_{$page}_$limit";
        $cacheCountKeyword = "posts:count{$keyword}_{$page}_$limit";
        if (!Cache::has($cacheRecordsKeyword) || !Cache::has($cacheCountKeyword)) {
            Cache::put(
                $cacheRecordsKeyword,
                $sphinxService->search(
                    $keyword,
                    $request->get('page', 1),
                    $request->get('perPage', 10)
                )
            );

            Cache::put(
                $cacheCountKeyword,
                $sphinxService->getCount($keyword)
            );
        }

        return response()->json([
            'records' => Cache::get($cacheRecordsKeyword),
            'total' => (int) Cache::get($cacheCountKeyword)
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
            Cache::clear();

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
            Cache::clear();

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
            Cache::clear();

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
