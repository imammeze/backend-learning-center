<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Program;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::query()->where('is_published', true);

        if ($request->has('program_code') && $request->program_code !== 'all') {
            $program = Program::where('code', $request->program_code)->first();
            if ($program) {
                $query->where('program_id', $program->id);
            } else {
                // Jika kode program ada tapi tidak ditemukan di DB, jangan kembalikan artikel apapun
                $query->whereRaw('1 = 0');
            }
        }

        $articles = $query->latest()->get()->map(function ($article) {
            return [
                'id' => $article->id,
                'title' => $article->title,
                'slug' => $article->slug,
                'thumbnail' => $article->thumbnail ? url('storage/' . $article->thumbnail) : null,
                'content' => $article->content,
                'created_at' => $article->created_at->format('d M Y'),
                'program' => $article->program ? $article->program->name : null,
                'program_code' => $article->program ? $article->program->code : 'umum',
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $articles
        ]);
    }

    public function show($slug)
    {
        $article = Article::where('slug', $slug)->where('is_published', true)->first();

        if (!$article) {
            return response()->json([
                'status' => 'error',
                'message' => 'Artikel tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $article->id,
                'title' => $article->title,
                'slug' => $article->slug,
                'thumbnail' => $article->thumbnail ? url('storage/' . $article->thumbnail) : null,
                'content' => $article->content,
                'created_at' => $article->created_at->format('d M Y'),
                'program' => $article->program ? $article->program->name : null,
                'program_code' => $article->program ? $article->program->code : 'umum',
            ]
        ]);
    }
}
