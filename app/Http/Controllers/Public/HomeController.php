<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Gallery;
use App\Models\News;
use App\Models\Program;

class HomeController extends Controller
{
    public function index()
    {
        $programs = Program::active()->orderBy('order')->limit(4)->get();
        $news = News::published()->latest('published_at')->limit(3)->get();
        $announcements = Announcement::published()->latest('published_at')->limit(4)->get();
        $galleries = Gallery::published()->orderBy('order')->limit(6)->get();

        return view('public.home', compact('programs', 'news', 'announcements', 'galleries'));
    }
}
