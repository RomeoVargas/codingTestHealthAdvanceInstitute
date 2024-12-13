<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\TagRepositoryInterface;
use Illuminate\Http\Request;

class TagController extends Controller
{
    private $tagRepository;

    public function __construct(TagRepositoryInterface $repository)
    {
        $this->tagRepository = $repository;
    }

    public function index(Request $request)
    {
        return response()->json([
            'tags' => $this->tagRepository->search(
                $request->get('search')
            )
        ]);
    }
}
