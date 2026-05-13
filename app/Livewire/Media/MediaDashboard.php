<?php

namespace App\Livewire\Media;

use App\Models\Media\BlogPost;
use App\Models\Media\News;
use App\Models\Media\Newsletter;
use App\Models\Media\Deliverable;
use App\Models\Media\MediaFile;
use Livewire\Component;

class MediaDashboard extends Component
{
    public function render()
    {
        $stats = [
            'totalBlog'            => BlogPost::count(),
            'publishedBlog'        => BlogPost::where('is_published', true)->count(),
            'draftBlog'            => BlogPost::where('is_published', false)->count(),
            'totalNews'            => News::count(),
            'publishedNews'        => News::where('is_published', true)->count(),
            'totalNewsletters'     => Newsletter::count(),
            'publishedNewsletters' => Newsletter::where('is_published', true)->count(),
            'totalDeliverables'    => Deliverable::count(),
            'totalMedia'           => MediaFile::count(),
        ];

        $stats_card = [
            [
                'label'     => 'Articles blog',
                'icon'      => 'newspaper',
                'sub'       => $stats['publishedBlog'] . ' publiés',
                'total'     => $stats['totalBlog'],
                'color'     => 'text-indigo-600',
                'bg'        => 'bg-indigo-50',
                'route'     => 'admin.media.blog',
            ],
            [
                'label'     => 'Actualités',
                'icon'      => 'rss',
                'sub'       => $stats['publishedNews'] . ' publiées',
                'total'     => $stats['totalNews'],
                'color'     => 'text-blue-600',
                'bg'        => 'bg-blue-50',
                'route'     => 'admin.media.news',
            ],
            [
                'label'     => 'Infolettres',
                'icon'      => 'mail',
                'sub'       => $stats['publishedNewsletters'] . ' publiées',
                'total'     => $stats['totalNewsletters'],
                'color'     => 'text-emerald-600',
                'bg'        => 'bg-emerald-50',
                'route'     => 'admin.media.newsletters',
            ],
            [
                'label'     => 'Livrables',
                'icon'      => 'folder',
                'sub'       => $stats['totalDeliverables'] . ' au total',
                'total'     => $stats['totalDeliverables'],
                'color'     => 'text-amber-600',
                'bg'        => 'bg-amber-50',
                'route'     => 'admin.media.deliverables',
            ],
            [
                'label'     => 'Médias',
                'icon'      => 'photo',
                'sub'       => $stats['totalMedia'] . ' fichiers',
                'total'     => $stats['totalMedia'],
                'color'     => 'text-rose-600',
                'bg'        => 'bg-rose-50',
                'route'     => 'admin.media.dashboard',
            ],
        ];

        $recentBlog = BlogPost::where('is_published', true)
            ->latest('published_at')
            ->take(5)
            ->get();

        $recentNews = News::where('is_published', true)
            ->latest('published_at')
            ->take(5)
            ->get();

        $recentNewsletters = Newsletter::latest()->take(4)->get();

        return view('livewire.media.media-dashboard', compact(
            'stats', 'stats_card', 'recentBlog', 'recentNews', 'recentNewsletters'
        ))->layout('layouts.app');
    }
}
