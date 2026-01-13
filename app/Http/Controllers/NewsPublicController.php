<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsPublicController extends Controller
{
    public function index()
    {
        abort(501, 'News listing not implemented yet.');
    }

    public function show(News $news)
    {
        abort(501, 'News detail not implemented yet.');
    }
}
