<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class BlogController extends Controller
{
    public function index(): View
    {
        return view('blogs.index', [
            'blogs' => Blog::with('user')->latest()->get(),
        ]);
    }
    
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|max:100',
            'message' => 'required|string',
            'author' => 'required|string|max:50',
        ]);
 
        $request->user()->blogs()->create($validated);
 
        return redirect(route('blogs.index'));
    }

    public function edit(Blog $blog): View
    {
        Gate::authorize('update', $blog);
 
        return view('blogs.edit', [
            'blog' => $blog,
        ]);
    }

    public function update(Request $request, Blog $blog): RedirectResponse
    {
        Gate::authorize('update', $blog);
 
        $validated = $request->validate([
            'message' => 'required|string',
        ]);
 
        $blog->update($validated);
 
        return redirect(route('blogs.index'));
    }

    public function destroy(Blog $blog): RedirectResponse
    {
        Gate::authorize('delete', $blog);
 
        $blog->delete();

        return redirect(route('blogs.index'));
    }
}