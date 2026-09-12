<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Gallery;

class GalleryController extends Controller
{
    public function index()
    {
        $categories = Gallery::published()
            ->distinct()
            ->pluck('category')
            ->filter()
            ->values();

        $galleries = Gallery::published()->orderBy('order')->get();

        return view('public.gallery.index', compact('categories', 'galleries'))
            ->with('title', 'Galeri');
    }
}
