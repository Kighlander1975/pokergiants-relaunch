<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $query = News::query();

        if ($status === 'published') {
            $query->where('published', true);
        }

        if ($status === 'draft') {
            $query->where('published', false);
        }

        if ($status === 'scheduled') {
            $query->whereNotNull('auto_publish_at')->where('auto_publish_at', '>', now());
        }

        $news = $query->orderByDesc('created_at')->paginate(12)->withQueryString();

        return view('admin.news.index', compact('news', 'status'));
    }

    public function create()
    {
        return view('admin.news.create', ['news' => null]);
    }

    public function store(Request $request)
    {
        $validated = $this->validatePayload($request);

        News::create($this->buildPayload($request, $validated));

        return Redirect::route('admin.news.index')->with('success', 'News wurde angelegt.');
    }

    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        $validated = $this->validatePayload($request);

        $news->update($this->buildPayload($request, $validated, $news));

        return Redirect::route('admin.news.index')->with('success', 'News wurde aktualisiert.');
    }

    public function destroy(News $news)
    {
        $news->delete();

        return Redirect::back()->with('success', 'News wurde gelöscht.');
    }

    public function publish(News $news)
    {
        $news->update(['published' => true]);

        return Redirect::back()->with('success', 'News wurde veröffentlicht.');
    }

    public function unpublish(News $news)
    {
        $news->update(['published' => false]);

        return Redirect::back()->with('success', 'News wurde zurückgezogen.');
    }

    private function validatePayload(Request $request): array
    {
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'author_external' => ['nullable', 'string', 'max:255'],
            'tags' => ['nullable', 'string', 'max:255'],
            'category' => ['required', Rule::in(array_keys(News::categories()))],
            'source_text' => ['nullable', 'string', 'max:255'],
            'source_url' => ['nullable', 'url', 'max:500'],
            'comments_allowed' => ['sometimes', 'boolean'],
            'published' => ['sometimes', 'boolean'],
            'auto_publish_at' => ['nullable', 'date'],
            'content' => ['required', 'string'],
        ];

        $messages = [
            'author_external.required_if' => 'Ein externer Autor ist für externe News erforderlich.',
        ];

        $validated = $request->validate($rules, $messages);

        if ($validated['category'] === News::CATEGORY_EXTERNAL) {
            $request->validate([
                'author_external' => ['required'],
            ]);

            if (empty($validated['source_text']) && empty($validated['source_url'])) {
                \Illuminate\Validation\ValidationException::withMessages([
                    'source_text' => 'Für externe News muss bitte mindestens eine Quelle angegeben werden.',
                ])->throw();
            }
        }

        return $validated;
    }

    private function buildPayload(Request $request, array $validated, News $news = null): array
    {
        $source = array_filter([
            'text' => $validated['source_text'] ?? null,
            'url' => $validated['source_url'] ?? null,
        ]);

        $tags = collect(explode(',', $validated['tags'] ?? ''))
            ->map(fn($tag) => trim($tag))
            ->filter()
            ->unique()
            ->values()
            ->all();

        return [
            'title' => $validated['title'],
            'slug' => News::generateSlug($validated['title'], $news?->id ?? null),
            'author' => $validated['author'],
            'author_external' => $validated['author_external'] ?? null,
            'tags' => $tags ?: null,
            'category' => $validated['category'],
            'source' => $source ?: null,
            'comments_allowed' => $request->boolean('comments_allowed', true),
            'published' => $request->boolean('published', false),
            'auto_publish_at' => $validated['auto_publish_at'] ? Carbon::parse($validated['auto_publish_at']) : null,
            'content' => $validated['content'],
        ];
    }
}
