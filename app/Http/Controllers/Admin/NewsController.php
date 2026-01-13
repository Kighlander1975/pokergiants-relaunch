<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        abort(501, 'News admin index not implemented yet.');
    }

    public function create()
    {
        abort(501, 'News admin create form not implemented yet.');
    }

    public function store(Request $request)
    {
        abort(501, 'News admin store not implemented yet.');
    }

    public function edit(News $news)
    {
        abort(501, 'News admin edit form not implemented yet.');
    }

    public function update(Request $request, News $news)
    {
        abort(501, 'News admin update not implemented yet.');
    }

    public function destroy(News $news)
    {
        abort(501, 'News admin destroy not implemented yet.');
    }

    public function publish(News $news)
    {
        abort(501, 'News publish not implemented yet.');
    }

    public function unpublish(News $news)
    {
        abort(501, 'News unpublish not implemented yet.');
    }
}
