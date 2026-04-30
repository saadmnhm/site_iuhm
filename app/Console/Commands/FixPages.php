<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Page;

class FixPages extends Command
{
    protected $signature = 'app:fix-pages';

    public function handle()
    {
        $pages = [
            "services" => "Nos Services",
            "resources" => "Livrables",
            "about" => "À propos",
            "observateur-urbain" => "Observateur Urbain"
        ];

        foreach($pages as $slug => $title) {
            Page::updateOrCreate(
                ["slug" => $slug],
                [
                    "title" => ["fr" => $title, "en" => $title, "ar" => $title],
                    "excerpt" => ["fr" => "", "en" => "", "ar" => ""],
                    "is_published" => true,
                    "published_at" => now()
                ]
            );
        }
        $this->info("Pages fixed");
    }
}
