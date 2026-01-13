<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsComment;
use Illuminate\Http\Request;

class NewsCommentController extends Controller
{
    public function index(News $news)
    {
        abort(501, 'News comment moderation not implemented yet.');
    }

    public function approve(NewsComment $comment)
    {
        abort(501, 'News comment approval not implemented yet.');
    }

    public function reject(NewsComment $comment)
    {
        abort(501, 'News comment rejection not implemented yet.');
    }
}
